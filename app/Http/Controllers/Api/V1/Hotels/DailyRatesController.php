<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\User;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\RoomTypeDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\DailyPrice;
use Wubook\Wired\Facades\WuBook;

class DailyRatesController extends Controller
{
	public function index(Request $request, $id) : JsonResponse
	{
		$user = auth()->user();	   
		$postData = $request->getContent();
		$postData = json_decode($postData, true);
		$dateFrom = $postData['date_from']; 
		$dateTo = $postData['date_to'];
		$carbonFromDate = new Carbon($dateFrom);
		$carbonToDate = new Carbon($dateTo);
		$days = $carbonToDate->diffInDays($carbonFromDate);
		$inputRoomType = $request->input('room-type') ? : null;		

		$roomsTypes = RoomType::where(['company_id' => $user->company_id, 'hotel_id' => $id])
			->with(
				[
					'roomTypeDetail',
					'rateTypes'
				]
		)->get();

		$resultData = [];
		$count = 0;
		$room = new Room();

		$dailyPrices = DailyPrice::where('date', '>=', $dateFrom)
			->where('date', '<=', $dateTo)
			->with([
				'product.price',
				'rateType.roomType.hotel' => function($q) use ($id){
					$q->where('id', $id);
				}
			])->get();

		$keyedPrices = [];
		if($dailyPrices) {
			foreach($dailyPrices as $dailyPrice) {
				$keyedPrices[$dailyPrice->rate_type_id][$dailyPrice->date] = [
					'id' => $dailyPrice->id,
					'price' => $dailyPrice->product->price->price,
					'checkin_closed' => $dailyPrice->checkin_closed,
					'exit_closed' => $dailyPrice->exit_closed,
					'minimum_stay' => $dailyPrice->minimum_stay,
					'maximum_stay' => $dailyPrice->maximum_stay
				];
			}
		}

		$totalRoomTypesOnDate = [];

		foreach($roomsTypes as $roomType)
		{
			
			$rateTypes = $roomType->rateTypes;
			$resultData[$count] = ['name' => $roomType->roomTypeDetail->name];

			$totalRooms = Room::where('room_type_id', $roomType->id)
				->where('company_id', $user->company_id)
				->get()->count();			
		
			$countJ = 0;			
			$carbonFromDate = new Carbon($dateFrom);

			for($i=0; $i <= $days; $i++)
			{			  

				$bookedCount = 0;
				$rateDate = $carbonFromDate->format('Y-m-d');  
				$result = $room->avaliability($roomType->id , $rateDate);  

				if(isset($result) && 0 < sizeof($result)) {

					$bookedCount = $result[0]->count;
				}
			
				$avaliableRooms = $totalRooms - $bookedCount;
				$dailyPrice = new DailyPrice();
				$resultData[$count]['avaiability'][] = [
					'date' => $rateDate,
					'available' => $avaliableRooms
				];

				if(array_key_exists($rateDate, $totalRoomTypesOnDate)) {
					$totalRoomTypesOnDate[$rateDate] += $avaliableRooms;
				} else {
					$totalRoomTypesOnDate[$rateDate] = $avaliableRooms;
				}
				
				$carbonFromDate = $carbonFromDate->addDay();
			}

			foreach($rateTypes as $rateType)
			{
				$resultData[$count]['rate_types'][$countJ]['id'] = $rateType->id;
				$resultData[$count]['rate_types'][$countJ]['name'] = $rateType->detail->name;
				$resultData[$count]['rate_types'][$countJ]['rate_type_id'] = $rateType->rate_type_id;
				$carbonFromDate = new Carbon($dateFrom); 

				for($i=0; $i <= $days; $i++)
				{
					$bookedCount = 0;
					$rateDate = $carbonFromDate->format('Y-m-d'); 
					$id = array_key_exists($rateType->id, $keyedPrices) && array_key_exists($rateDate, $keyedPrices[$rateType->id]) ? $keyedPrices[$rateType->id][$rateDate]['id'] : 0;
					$price = array_key_exists($rateType->id, $keyedPrices) && array_key_exists($rateDate, $keyedPrices[$rateType->id]) ? $keyedPrices[$rateType->id][$rateDate]['price'] : 0;
					$checkinClosed = array_key_exists($rateType->id, $keyedPrices) && array_key_exists($rateDate, $keyedPrices[$rateType->id]) ? $keyedPrices[$rateType->id][$rateDate]['checkin_closed'] : 0;
					$exitClosed = array_key_exists($rateType->id, $keyedPrices) && array_key_exists($rateDate, $keyedPrices[$rateType->id]) ? $keyedPrices[$rateType->id][$rateDate]['exit_closed'] : 0; 
					$minimumStay = array_key_exists($rateType->id, $keyedPrices) && array_key_exists($rateDate, $keyedPrices[$rateType->id]) ? $keyedPrices[$rateType->id][$rateDate]['minimum_stay'] : 0; 
					$maximumStay = array_key_exists($rateType->id, $keyedPrices) && array_key_exists($rateDate, $keyedPrices[$rateType->id]) ? $keyedPrices[$rateType->id][$rateDate]['maximum_stay'] : 0;  

					$avaliableRooms = $totalRooms - $bookedCount;
					$dailyPrice = new DailyPrice();
					$resultData[$count]['rate_types'][$countJ]['rate'][] = [
						'id' => $id,
						'rate_type_id' => $rateType->id,
						'parent_rate_type_id' => $rateType->rate_type_id,
						'date' => $rateDate,
						'price' => $price,
						'checkin_closed' => $checkinClosed,
						'exit_closed' => $exitClosed,
						'minimum_stay' => $minimumStay,
						'maximum_stay' => $maximumStay,
					];

					$carbonFromDate = $carbonFromDate->addDay();
				}

				$countJ++;
			}
		
			$bookedCount = 0;
			$count++;
		}

		$totalAvailabilityCount = [];
		if($totalRoomTypesOnDate) {
			foreach($totalRoomTypesOnDate as $date => $dateAvailability) {

				$totalAvailabilityCount[] = [
					'date' => $date,
					'available' => $dateAvailability
				];
			}
		}

		return response()->json(['calendar_data' => $resultData, 'total_available' => $totalAvailabilityCount]);
	}

	public function update(Request $request) : JsonResponse
    {      
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);	

		if(array_key_exists('id',  $postData))
		{
			$dailyPrice = DailyPrice::find($postData['id']);						

			if(array_key_exists('price',  $postData))
			{										
				$product = $dailyPrice->product;
				$product->createPrice($postData['price']);
				if($dailyPrice->rateType->ref_id)
				{
					//TODO: Create env variable "PLAN_ID" for it and get from that
					$planId = 182115;
					$token = WuBook::auth()->acquire_token();				
					$dfromdmY = Carbon::parse($dailyPrice->date)->format('d/m/Y');
					$prices[$dailyPrice->rateType->ref_id][] = $postData['price'];		
					$hotel = $dailyPrice->rateType->roomType->hotel;							  
					$result = WuBook::prices($token, $hotel->l_code)->update_plan_prices($planId, $dfromdmY, $prices);			
				}
			}

			if(array_key_exists('checkin_closed',  $postData))
			{
				$dailyPrice->checkin_closed = $postData['checkin_closed'];
			}
			if(array_key_exists('exit_closed',  $postData))
			{
				$dailyPrice->exit_closed = $postData['exit_closed'];
			}
			if(array_key_exists('minimum_stay',  $postData))
			{
				$dailyPrice->minimum_stay = $postData['minimum_stay'];
			}
			if(array_key_exists('maximum_stay',  $postData))
			{
				$dailyPrice->maximum_stay = $postData['maximum_stay'];
			}
			$dailyPrice->save();			
		}

		return response()->json(array('message' => ' Record updated successfully.'));
    }
}

