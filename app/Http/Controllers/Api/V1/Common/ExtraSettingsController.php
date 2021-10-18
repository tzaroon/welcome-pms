<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use App\Models\ExtraSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExtraSettingsController extends Controller
{
     /**
     * List all resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $extraSetting = ExtraSetting::get();

        return response()->json($extraSetting);
    }
}