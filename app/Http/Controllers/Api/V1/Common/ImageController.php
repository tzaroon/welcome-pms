<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class ImageController extends Controller
{
     /**
     * List all resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function uploadIdImage(Request $request) : JsonResponse
    {
        $postData = $request->all();

        $validator = Validator::make($postData, [
            'id_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ], [], [
            'id_image' => 'File'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }        

        if ($request->hasFile('id_image')) {
            
            $idImagePath = $request->file('id_image')->hashName();
            $request->file('id_image')->store('public');
        }

        return response()->json($idImagePath);
    }
}