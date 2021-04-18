<?php

namespace App\Http\Controllers\Api\V1\WuBook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;

class PushNotificationController extends Controller
{
    public function index(Request $request)
    {
        file_put_contents(storage_path('no.test'), print_r($_POST, true));
        return response()->json(['success' => true]);
    }
}