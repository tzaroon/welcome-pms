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

    /**
     * Store a new resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $user = auth()->user();
        
        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'name' => 'required|string|max:191',
            'property' => 'required|string|max:191',
            'address' => 'required|string',
            'zip' => 'required|string',
            'state_id' => 'required',
            'country_id' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'currency_id' => 'required'
        ], [], [
            'state_id' => 'State',
            'country_id' => 'Country',
            'currency_id' => 'Currency'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $hotel = new Hotel();
        $hotel->fill($postData);
        $hotel->company_id = $user->company_id;
        $hotel->save();

        return response()->json($hotel);
    }
}
