<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Verify\Service;

class SessionsController extends Controller
{
    /**
     * Verification service
     *
     * @var Service
     */
    protected $verify;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Service $verify)
    {
        $this->verify = $verify;
    }

    /**
     * Identify if the given username exists.
     *
     * @param Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function identify(Request $request) : JsonResponse
    {
        $request->validate([
            'username' => "required|exists:users,{$this->identifier($request)},deleted_at,NULL",
        ]);

        return response()->json(
            User::where(
                $this->identifier($request),
                $request->input('username')
            )->first()->email
        );
    }

    /**
     * Authenticate the user and give the token data.
     *
     * @param Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin(Request $request) : JsonResponse
    {
        $request->validate([
            'username' => "required|exists:users,{$this->identifier($request)}",
            'password' => 'required'
        ]);

        if ($token = $this->attempt($request)) {

            $user = JWTAuth::setToken($token)->toUser();
            $phone = $user->phone_number;
            $channel = $request->post('channel', 'sms');
            $verification = $this->verify->startVerification($phone, $channel);

            if (!$verification->isValid()) {
                return response()->json($verification->getErrors());
            } else {
                return response()->json(['success' => true, 'token' => $token]);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function verify(Request $request)
    {
        $code = $request->post('code');
        $token = $request->post('token');
        $user = JWTAuth::setToken($token)->toUser();
        
        $verification = $this->verify->checkVerification($user->phone_number, $code);

        if ($verification->isValid()) {
            $this->guard()->logout();
            if ($token = $this->attempt($request)) {
            
                return $this->respondWithToken($token);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        return response()->json($verification->getErrors());
    }

    /**
     * Try to authenticate the user.
     *
     * @param Illuminate\Http\Request
     *
     * @return string/null
     */
    protected function attempt(Request $request) : ?string
    {
        return $this->guard()->attempt([
            $this->identifier($request) => $request->input('username'),
            'password' => $request->input('password')
        ]);
    }

    /**
     * Get the identifier for the user.
     *
     * @param Illuminate\Http\Request
     *
     * @return string
     */
    protected function identifier(Request $request) : string
    {
        return filter_var(
            $request->input('username'),
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'username';
    }

    /**
     * Get the authenticated user's Id.
     *
     * @param  string $authToken
     *
     * @return Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($authToken) : JsonResponse
    {
        $user = JWTAuth::setToken($authToken)->toUser();

        $this->saveAuthToken($authToken, $user);

        return response()->json(
            [
                'token' => _token_payload($authToken),
                'user' => $user
            ]
        );
    }

    /**
     * Save Auth Token.
     *
     * @param string $token
     * @param App\User $user
     *
     * @return bool
     */
    protected function saveAuthToken(string $token, User $user) : bool
    {
        $user->api_token = $token;
        $user->last_signin = now();

        return $user->update();
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user() : JsonResponse
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Refresh the token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Sign the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signout() : JsonResponse
    {
        $user = $this->guard()->user();

        $user->auth_token = null;
        $user->update();

        $this->guard()->logout();

        return response()->json(['message' => 'Successfully signed out']);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard() : Guard
    {
        return Auth::guard('api');
    }
}
