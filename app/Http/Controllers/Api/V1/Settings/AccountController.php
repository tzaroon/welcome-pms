<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class AccountController extends Controller
{
    public function edit(Request $request) {

        $user = auth()->user();
        return response()->json($user);
    }
    /**
     * Update a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\User $user
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user) : JsonResponse
    {
        $postData = $request->getContent();
       
        $postData = json_decode($postData, true);
        $user = auth()->user();

        $validator = Validator::make($postData, [
            'title' => 'required|string|max:191',
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'gender' => 'required|in:female,male',
            'language_id' => 'required',
            'phone_number' => 'required|string|max:14',
            'email' => "required|email|unique:users,email,{$user->id},id,deleted_at,NULL"
        ]);
        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        
        $user->fill($postData);
        $user->update();

        return response()->json($user);
    }

    /**
     * Store the user's avatar.
     *
     * @param Illuminate\Http\Request $request
     * @param App\User $user
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function storeAvatar(Request $request, User $user) : JsonResponse
    {
        if ($user->upload($request->files->get('avatar'))) {
            return response()->json($user);
        }

        return response()->json('Unable to process the upload', 422);
    }

    /**
     * Destroy the user's avatar.
     *
     * @param Illuminate\Http\Request $request
     * @param App\User $user
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function destroyAvatar(Request $request, User $user)  : JsonResponse
    {
        if ($user->destroyUpload()) {
            return response()->json($user);
        }

        return response()->json('Uploaded file not removed', 422);
    }
}
