<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RateType;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\DailyPrice;
use App\Dto\BookingQuery;
use App\Models\BookingHasRoom;
use Validator;
use App\Models\Booking;

class RelocateReservationController extends Controller
{
    public function checkAvalibility(Request $request) {      
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'hotel_id' => 'required',            
            'arrivel_date' => 'required',
            'departure_date' => 'required'
        ], [], [
            'arrivel_date' => 'Reservation from',
            'departure_date' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $startDate = Carbon::parse($postData['arrivel_date']);
        $endDate = Carbon::parse($postData['departure_date']);
        $days = $endDate->diffInDays($startDate);

        $hotel = Hotel::find($postData['hotel_id']);

        $roomTypes = $hotel->roomTypes;

        $calendarStartDate = Carbon::parse($postData['arrivel_date']);
        $availabilityData = []; 
        $k = 0;
        $isAvaliable = false ;     

            foreach($roomTypes as $roomType)
            {
                $rooms = $roomType->rooms;   
                
                foreach($rooms as $room)
                { 
                    for($i=0; $i < $days; $i++)
                    {                   
                        $isAvaliable = $room->isAvailable($room->id, $calendarStartDate);

                        if(!$isAvaliable){
                            break;
                        }

                        $calendarStartDate->addDay();
                    }

                    if($isAvaliable){
                        $arrRoom = [
                            'id' =>$room->id,
                            'name'=>$room->name . ' - ' . $room->roomType->roomTypeDetail->name ,
                        ];
                        $availabilityData[$k++] = $arrRoom;                        
                    }
                }
            }        

        return response()->json($availabilityData);
    }

    public function loadRateTypes(Request $request)
    {

        $postData = $request->getContent();
        $user = auth()->user();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'room_id' => 'required'           
            
        ], [], [
            'room_id' => 'Room',            
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $startDate = Carbon::parse($postData['arrivel_date']);
        $endDate = Carbon::parse($postData['departure_date']);
        $days = $endDate->diffInDays($startDate);


        $room = Room::find($postData['room_id']);

        $rateTypes = $room->roomType->rateTypes;

        $processedData = [];
        
        foreach($rateTypes as $rateType){
            $totalprice = 0;
            $arrDailyPrice = [];
            $date = Carbon::parse($postData['arrivel_date']);
            for($i= 0 ; $i< $days ; $i++ )
            {

                $rateDate = $date->format('Y-m-d');
                $price = 0;

                $dailyPrice = new DailyPrice();
                        $dailyPrice = $dailyPrice->where('date', $rateDate)
                            ->where('rate_type_id', $rateType->id)
                            ->first();
                if($dailyPrice)
                {
                    $price = $dailyPrice->product->price->price;
                }
                
                $arrDailyPrice[] = [
                    'date'=> $rateDate,
                    'price'=>  $price 
                ];
                $totalprice+=$price; 
                $date = $date->addDay();
            }
            $processedData [] = 
                [
                    'rate_type_id' => $rateType->id,
                    'rate_type_name' => $rateType->detail->name,
                    'dialy_prices' => $arrDailyPrice,
                    'total_price' => $totalprice
                ];
        }

        return response()->json($processedData);


    }

    public function relocateBooking(Request $request , Booking $booking)
    {
        $postData = $request->getContent();
       
        $postData = json_decode($postData, true);
       

        $startDate = Carbon::parse($postData['arrivel_date']);
        $endDate = Carbon::parse($postData['departure_date']);       
        $days = $endDate->diffInDays($startDate);
        $date = $startDate;
        $priceIds = [];

        $oldRoomId = array_key_exists('old_room_id', $postData) ? $postData['old_room_id'] : null;
        $roomId = array_key_exists('room_id', $postData) ? $postData['room_id'] : null;
        $rateTypeId = array_key_exists('rate_type_id', $postData) ? $postData['rate_type_id'] : null;
        $discount = array_key_exists('discount', $postData) ? $postData['discount'] : null;
        $dailyPrices = array_key_exists('daily_price', $postData) ? $postData['daily_price'] : null;

        $oldBookingHasRoom = BookingHasRoom::where('booking_id', $booking->id)
            ->where('room_id', $oldRoomId)->get()->first();

        if($oldBookingHasRoom->booking->productPrice) {
            foreach($oldBookingHasRoom->booking->productPrice as $productPrice) {
                $priceIds[$productPrice->pivot->booking_has_room_id]['product_price_id'] = $productPrice->pivot->product_price_id;
                $priceIds[$productPrice->pivot->booking_has_room_id]['booking_has_room_id'] = $productPrice->pivot->booking_has_room_id;
            }
        }

        $oldBookingHasRoom->room_id = $roomId;
        $oldBookingHasRoom->rate_type_id = $rateTypeId;
        $oldBookingHasRoom->save();

        if($dailyPrices)
        {
            foreach($dailyPrices as $dailyPrice)
            {
                for($i=0; $i < $days; $i++) 
                {
                    $rateDate = $date->format('Y-m-d');
                    $dailyPrice = new DailyPrice();
                    $dailyPrice = $dailyPrice->where('date', $rateDate)
                        ->where('rate_type_id', $rateTypeId)
                        ->first();

                    $priceIds[$oldBookingHasRoom->id]['product_price_id'] = $dailyPrice->product->price->id;
                    $priceIds[$oldBookingHasRoom->id]['booking_has_room_id'] =  $oldBookingHasRoom->id;
                    $date = $date->addDay();
                }
            }
        }

        $oldBookingHasRoom->booking->productPrice()->sync($priceIds);

        $booking->reservation_from = Carbon::parse($postData['arrivel_date'])->format('Y-m-d');
        $booking->reservation_to = Carbon::parse($postData['departure_date'])->format('Y-m-d');

        $booking->save();
        
        return response()->json($oldBookingHasRoom);
    }
}