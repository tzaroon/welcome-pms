<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatesController extends Controller
{
     /**
     * List all resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $countryId) : JsonResponse
    {
        $states = State::where('country_id', $countryId)->get();

        return response()->json($states);
    }
}