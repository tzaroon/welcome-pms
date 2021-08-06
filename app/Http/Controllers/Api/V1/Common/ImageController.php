<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        
        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $base64String = $postData['base_64'];

        $validator = Validator::make($postData, [
            'base_64' => 'required'
        ], [], [
            'base_64' => 'Image'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }        

        $path = '/home/ChicStays/chicstays-frontend/dist/';
        $idImagePath = "assets/id-".time().".png";
        $path .= $idImagePath;

        $img = substr($base64String, strpos($base64String, ",")+1);
        $data = base64_decode($img);

        $success = file_put_contents($path, $data);
        
        return response()->json($idImagePath);
    }
}