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
use Validator;

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
        $date = $startDate;


        $room = Room::find($postData['room_id']);

        $rateTypes = $room->roomType->rateTypes;

        $processedData = [];
        
        foreach($rateTypes as $rateType){
            $totalprice = 0;
            $arrDailyPrice = [];
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
}
