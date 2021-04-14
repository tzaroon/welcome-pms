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

		$roomsTypes = RoomType::where(['company_id' => $user->company_id])
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
				'product.price'
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

				$carbonFromDate = $carbonFromDate->addDay();
			}

			foreach($rateTypes as $rateType)
			{
				$resultData[$count]['rate_types'][$countJ]['name'] = $rateType->detail->name;
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

		return response()->json($resultData);
	}
}
