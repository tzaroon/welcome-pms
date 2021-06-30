<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $role = null, $roleId = null)
    {

        $user = auth()->user();

        $user = User::where(['company_id' => $user->company_id])
            ->where('is_system_user', 1);

        if ($roleId) {
            $user->where('role_id', $roleId);
        }

        $systemUsers =  $user->get();

        if (0 == $systemUsers->count()) {
            return response()->json(['message' => 'no data found'], 201);
        }
        return response()->json($systemUsers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $authUser = auth()->user();

        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'gender' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:8',
            'doc' => 'mimes:jpeg,png,pdf|max:4096',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')
                    ->where('email', $postData['email'])
                    ->where('company_id', $authUser->company_id)
                    ->WhereNull('deleted_at')
            ],
        ], [], [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'email' => 'Email',
            'password' => 'Password',
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $user = new User();
        $user->fill($postData);
        $user->company_id = $authUser->company_id;
        $user->is_system_user = true;
        $user->password = Hash::make($postData['password']);
        $user->save();
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $booking, $id)
    {

        $user =  User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $user =  User::find($id);

        if (!$user) {

            return response()->json(array('errors' => ['user' => 'user not found']), 422);
        }

        $validator = Validator::make($postData, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'gender' => 'required|string',
            'email' => 'required|string',
            'doc' => 'mimes:jpeg,png,pdf|max:4096',
        ], [], [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'email' => 'Email',
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $user->first_name = $postData['first_name'];
        $user->last_name = $postData['last_name'];
        $user->gender = $postData['gender'];
        $user->email = $postData['email'];
        $user->role_id = array_key_exists('role_id', $postData) ? $postData['role_id'] : null;

        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $user =  User::where('id', $id)->first();

        if (!$user) {

            return response()->json(['message' => 'User not found']);
        }

        $user->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}
