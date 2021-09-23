<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Dto\BookingQuery;
use App\User;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\RateType;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use DB;

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
        // return response()->json($postData);
        
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
        ], [], [
            'state_id' => 'State',
            'country_id' => 'Country',
            'currency_id' => 'Currency'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function () use ($user, $postData, &$hotel, &$hotelImages) {
            
            $hotel = new Hotel();
            // $hotel->fill($postData);
            $hotel->fill([
                'company_id' => $user->company_id,
                'country_id' => $postData['country_id'],
                'state_id' => $postData['state_id'],
                'name' => $postData['name'],
                'property' => $postData['property'],
                'address' => $postData['address'],
                'zip' => $postData['zip'],
                'phone' => $postData['phone'],
                'email' => $postData['email'],
                'vat_number' => $postData['vat_number'],
                'taxes' => $postData['taxes'],
                'additional_taxes' => $postData['additional_taxes'],
                'currency_id' => $postData['currency_id'],
                'max_booking_hour' => array_key_exists('max_booking_hour',$postData) ? $postData['max_booking_hour'] : 0,
                'round_price' => $postData['round_price'] ? : null,
                'cleaning_days' => $postData['cleaning_days'] ? : null,
                'logo_email' => array_key_exists('logo_email',$postData) ? $postData['logo_email'] : null,
                'logo' => $postData['logo'],
                'description' => array_key_exists('description',$postData) ?  $postData['description'] : null,

            ]);

            $hotel->save();
            
            $hotelImages = [];
            foreach($postData['images'] as $image){
                $hotelImage = new HotelImage;
                $hotelImage->company_id = $hotel->company_id;
                $hotelImage->hotel_id = $hotel->id;
                $hotelImage->image = $image;
                $hotelImages[] = $hotelImage;
                $hotelImage->save();
            }
        });

        return response()->json([
            'hotel' => $hotel,
            'hotelImages' => $hotelImages
        ]);
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
        $hotel->images;
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
        $postData = $request->getContent();        
        $postData = json_decode($postData, true);
        // return response()->json($hotel);

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
        ], [], [
            'state_id' => 'State',
            'country_id' => 'Country',
            'currency_id' => 'Currency'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function () use ($user, $postData, &$hotel, &$hotelImages) {
            
            $hotel->fill([
                'company_id' => $user->company_id,
                'country_id' => $postData['country_id'],
                'state_id' => $postData['state_id'],
                'name' => $postData['name'],
                'property' => $postData['property'],
                'address' => $postData['address'],
                'zip' => $postData['zip'],
                'phone' => $postData['phone'],
                'email' => $postData['email'],
                'vat_number' => $postData['vat_number'],
                'taxes' => $postData['taxes'],
                'additional_taxes' => $postData['additional_taxes'],
                'currency_id' => $postData['currency_id'],
                'max_booking_hour' => array_key_exists('max_booking_hour',$postData) ? $postData['max_booking_hour'] : 0,
                'round_price' => $postData['round_price'] ? : null,
                'cleaning_days' => $postData['cleaning_days'] ? : null,
                'logo_email' => array_key_exists('logo_email',$postData) ? $postData['logo_email'] : null,
                'logo' => $postData['logo'],
                'description' => array_key_exists('description',$postData) ?  $postData['description'] : null,
            ]);

            $hotel->save();
            
            if(array_key_exists('images', $postData) && $postData['images']){
                foreach($postData['images'] as $image){
                    $hotelImage = new HotelImage;
                    $hotelImage->company_id = $hotel->company_id;
                    $hotelImage->hotel_id = $hotel->id;
                    $hotelImage->image = $image;
                    $hotelImage->save();
                }
            }
            
        });

        $hotelImages = HotelImage::where('hotel_id',$hotel->id)->get();

        return response()->json([
            'hotel' => $hotel,
            'hotelImages' => $hotelImages
        ]);
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


    public function deleteHotelImage($hotelImageId){
        
        $hotelImage = HotelImage::find($hotelImageId);

        if(!$hotelImage){
            return response()->json(['message' => 'Hotel image not found!']);
        }

        $hotelImage->delete();
        return response()->json(['message' => 'Hotel image has been deleted successfully!']);
    }
}


