<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\User;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class HotelsController extends Controller
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
        $user = auth()->user();
        $hotels = Hotel::where(['company_id' => $user->company_id])->get();

        $data = $this->paginate($hotels);

        return response()->json($data);
    }
}
