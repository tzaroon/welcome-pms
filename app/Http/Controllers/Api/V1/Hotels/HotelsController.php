<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Dto\BookingQuery;
use App\User;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RateType;
use App\Models\RoomType;
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
        
        $postData = $request->all();

        $validator = Validator::make($postData, [
            'name' => 'required|string|max:191',
            'property' => 'required|string|max:191',
            'address' => 'required|string',
            'zip' => 'required|string',
            'state_id' => 'required',
            'country_id' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'currency_id' => 'required',
            'logo' => 'mimes:jpeg,png|max:4096',
            'logo_email' => 'mimes:jpeg,png|max:4096'
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

        if ($request->hasFile('logo')) {
            
            $logoPath = $request->file('logo')->hashName();
            $request->file('logo')->store('public');
            $hotel->logo = $logoPath;
        }
        
        if ($request->hasFile('logo_email')) {
            
            $emailLogoPath = $request->file('logo_email')->hashName();
            $request->file('logo_email')->store('public');
            $hotel->logo_email = $emailLogoPath;
        }

        $hotel->save();

        return response()->json($hotel);
    }

    /**
     * Show a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Hotel $hotel
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Hotel $hotel) : JsonResponse
    {
        return response()->json($hotel);
    }

    /**
     * Update a resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Hotel $hotel) : JsonResponse
    {
        $user = auth()->user();
        
        $postData = $request->all();

        $validator = Validator::make($postData, [
            'name' => 'required|string|max:191',
            'property' => 'required|string|max:191',
            'address' => 'required|string',
            'zip' => 'required|string',
            'state_id' => 'required',
            'country_id' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'currency_id' => 'required',
            'logo' => 'mimes:jpeg,png,jpg|max:4096',
            'logo_email' => 'mimes:jpeg,png,jpg|max:4096'
        ], [], [
            'state_id' => 'State',
            'country_id' => 'Country',
            'currency_id' => 'Currency'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $hotel->fill($postData);
        $hotel->company_id = $user->company_id;
        if ($request->hasFile('logo')) {
            
            $logoPath = $request->file('logo')->hashName();
            $request->file('logo')->store('public');
            $hotel->logo = $logoPath;
        }
        
        if ($request->hasFile('logo_email')) {
            
            $emailLogoPath = $request->file('logo_email')->hashName();
            $request->file('logo_email')->store('public');
            $hotel->logo_email = $emailLogoPath;
        }

        $hotel->update();
        return response()->json($hotel);
    }

    /**
     * Destroy a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Model\Hotel $hotel
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Hotel $hotel) : JsonResponse
    {
       
        $hotel->delete();

        return response()->json(array('message' => 'Hotel deleted successfully'));
    }

    public function loadRoomTypeRateType(Request $request, $hotel) {

       $roomTypes = RoomType::where('hotel_id', $hotel)->get();

       $keyedRoomTypes = [];
       if($roomTypes) {
            $count = 0;
           foreach($roomTypes as $roomType) {

                $rateTypes = $roomType->rateTypes;
                if($rateTypes) {
                    
                    foreach($rateTypes as $rateType) {
                        $keyedRoomTypes[$count]['id'] = $rateType->id;
                        $keyedRoomTypes[$count]['name'] = $roomType->roomTypeDetail->name . ' ' . $rateType->detail->name;
                        $count++;
                    }
                }
           }
       }
       
       return response()->json($keyedRoomTypes);
    }

    public function loadRateTypesWithRateCalculated(Request $request) {

        $user = auth()->user();
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'hotel_id' => 'required',
            'adult_count' => 'required',
            'nights' => 'required',
            'reservation_from' => 'required',
            'reservation_to' => 'required'
        ], [], [
            'reservation_from' => 'Reservation from',
            'reservation_to' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $rateTypes = RateType::whereHas('roomType', function($q) use($postData){
            $q->where('hotel_id', $postData['hotel_id']);
        })->get();

        $bookingQuery = BookingQuery::fromRequest($postData);

       

        $roomTypes = [];
        if($rateTypes->count()) {
            foreach($rateTypes as $rate) 
            {
                $calculated = $rate->calculateRate($bookingQuery);
                if($calculated) {
                    $roomTypes[] = $calculated;
                }
            }
        }

        return response()->json($roomTypes);
    }
}
