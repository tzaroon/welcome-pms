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
	private $recurtionCount = 0;

	public function index(Request $request): JsonResponse

	{
		$user = auth()->user();
		$postData = $request->getContent();
		$postData = json_decode($postData, true);
		$dateFrom = $postData['date_from'];
		$dateTo = $postData['date_to'];
		$carbonFromDate = new Carbon($dateFrom);
		$carbonToDate = new Carbon($dateTo);
		$days = $carbonToDate->diffInDays($carbonFromDate);
		$inputRoomType = $request->input('room-type') ?: null;


		$hotels = Hotel::where('company_id', $user->company_id)->get();

		$hotelCount = 0;
		if ($hotels) {

			

			foreach ($hotels as $hotel) {
				$hotelBooked = [];
				$hotelTotalRooms = [];
				$roomsTypes = RoomType::where(['company_id' => $user->company_id, 'hotel_id' => $hotel->id])
					->with(
						[
							'roomTypeDetail',
							'rateTypes'
						]
					)->get();

				$count = 0;
				$resultData[$hotelCount][$count] = [
					'row_type' => 'hotel',
					'name' => $hotel->property

				];
				
				$hotalReserveKey = $count;
				$count++;
				//$count++;

				$room = new Room();

				$dailyPrices = DailyPrice::where('date', '>=', $dateFrom)
					->where('date', '<=', $dateTo)
					->with([
						'product.price',
						'rateType.roomType.hotel' => function ($q) use ($hotel) {
							$q->where('id', $hotel->id);
						}
					])->get();

				$keyedPrices = [];
				if ($dailyPrices) {
					foreach ($dailyPrices as $dailyPrice) {
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

				foreach ($roomsTypes as $roomType) {

					$rateTypes = $roomType->rateTypes;
					$resultData[$hotelCount][$count] = ['name' => $roomType->roomTypeDetail->name];

					$totalRooms = Room::where('room_type_id', $roomType->id)
						->where('company_id', $user->company_id)
						->get()->count();

					$countJ = 0;
					$carbonFromDate = new Carbon($dateFrom);

					for ($i = 0; $i <= $days; $i++) {

						$bookedCount = 0;
						$rateDate = $carbonFromDate->format('Y-m-d');
						$result = $room->avaliability($roomType->id, $rateDate);

						if (isset($result) && 0 < sizeof($result)) {

							$bookedCount = $result[0]->count;
						}

						$avaliableRooms = $totalRooms - $bookedCount;
						$dailyPrice = new DailyPrice();
						$resultData[$hotelCount][$count]['avaiability'][] = [
							'date' => $rateDate,
							'available' => $avaliableRooms
						];

						if (array_key_exists($rateDate, $totalRoomTypesOnDate)) {
							$totalRoomTypesOnDate[$rateDate] += $avaliableRooms;
						} else {
							$totalRoomTypesOnDate[$rateDate] = $avaliableRooms;
						}

						if (array_key_exists($rateDate, $hotelTotalRooms)) {

							$hotelTotalRooms[$rateDate] += $totalRooms;
							$hotelBooked[$rateDate] += $bookedCount;
						} else {
	
							$hotelTotalRooms[$rateDate] = $totalRooms;
							$hotelBooked[$rateDate] = $bookedCount;
						}

						$carbonFromDate = $carbonFromDate->addDay();
					}

					foreach ($rateTypes as $rateType) {
						$resultData[$hotelCount][$count]['rate_types'][$countJ]['id'] = $rateType->id;
						$resultData[$hotelCount][$count]['rate_types'][$countJ]['name'] = $rateType->detail->name;
						$resultData[$hotelCount][$count]['rate_types'][$countJ]['rate_type_id'] = $rateType->rate_type_id;
						$resultData[$hotelCount][$count]['rate_types'][$countJ]['number_of_people'] = $rateType->number_of_people;
						$carbonFromDate = new Carbon($dateFrom);

						for ($i = 0; $i <= $days; $i++) {
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
							$resultData[$hotelCount][$count]['rate_types'][$countJ]['rate'][] = [
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

				if($hotelTotalRooms) {
					$hotalRoomTotalCount = 0;
					foreach($hotelTotalRooms as $date=>$roomCount) {

						$resultData[$hotelCount][$hotalReserveKey]['room_counts'][$hotalRoomTotalCount] = [
							'date' => $date,
							'total_rooms' => $roomCount,
							'booked_rooms' => $hotelBooked[$date],
							'percent' => round($hotelBooked[$date]/$roomCount*100, 2)
						];

						$hotalRoomTotalCount++;
					}
				}

				$hotelCount++;
			}

			$bookedCount = 0;
			$count++;
		}

		$totalAvailabilityCount = [];
		if ($totalRoomTypesOnDate) {
			foreach ($totalRoomTypesOnDate as $date => $dateAvailability) {

				$totalAvailabilityCount[] = [
					'date' => $date,
					'available' => $dateAvailability
				];
			}
		}

		return response()->json(['calendar_data' => $resultData, 'total_available' => $totalAvailabilityCount]);
	}

	public function update(Request $request, $id): JsonResponse
	{
		$postData = $request->getContent();

		$postData = json_decode($postData, true);

		$dailyPrice = DailyPrice::find($id);

		$hotel = $dailyPrice->rateType->roomType->hotel;

		if (array_key_exists('price',  $postData)) {

			$this->applyPriceRecursively($dailyPrice, $postData['price'], $dailyPrice->date);
		}

		if (array_key_exists('checkin_closed',  $postData)) {
			$dailyPrice->checkin_closed = $postData['checkin_closed'];
		}
		if (array_key_exists('exit_closed',  $postData)) {
			$dailyPrice->exit_closed = $postData['exit_closed'];
		}
		if (array_key_exists('minimum_stay',  $postData)) {
			$dailyPrice->minimum_stay = $postData['minimum_stay'];
		}
		if (array_key_exists('maximum_stay',  $postData)) {
			$dailyPrice->maximum_stay = $postData['maximum_stay'];
		}
		$dailyPrice->save();

		$token = WuBook::auth()->acquire_token();
		$values = [
			$dailyPrice->rateType->ref_id . ' ' => [
				[
					'min_stay' => $dailyPrice->minimum_stay,
					'max_stay' => $dailyPrice->maximum_stay,
					'closed_arrival' => $dailyPrice->checkin_closed ? 1 : 0,
					'closed_departure' => $dailyPrice->exit_closed ? 1 : 0
				]
			]
		];
		$restriction = WuBook::restrictions($token, $hotel->l_code)->rplan_update_rplan_values(0, date('d/m/Y', strtotime($dailyPrice->date)), $values);

		return response()->json(array('message' => ' Record updated successfully.'));
	}

	public function bulkPriceUpdate(Request $request): JsonResponse
	{

		$postData = $request->getContent();

		$postData = json_decode($postData, true);

		$dailyPriceIds = array_key_exists('daily_price_ids',  $postData) ?  $postData['daily_price_ids'] : [];


		$dailyPrices = DailyPrice::whereIn('id', $dailyPriceIds)->with(['rateType.roomType.hotel'])->get();

		foreach ($dailyPrices as $dailyPrice) {

			$hotel = $dailyPrice->rateType->roomType->hotel;

			if (array_key_exists('price',  $postData)) {

				$this->applyPriceRecursively($dailyPrice, $postData['price'], $dailyPrice->date);
			}

			if (array_key_exists('checkin_closed',  $postData)) {
				$dailyPrice->checkin_closed = $postData['checkin_closed'];
			}
			if (array_key_exists('exit_closed',  $postData)) {
				$dailyPrice->exit_closed = $postData['exit_closed'];
			}
			if (array_key_exists('minimum_stay',  $postData)) {
				$dailyPrice->minimum_stay = $postData['minimum_stay'];
			}
			if (array_key_exists('maximum_stay',  $postData)) {
				$dailyPrice->maximum_stay = $postData['maximum_stay'];
			}
			$dailyPrice->save();

			$token = WuBook::auth()->acquire_token();
			$values = [
				$dailyPrice->rateType->ref_id . ' ' => [
					[
						'min_stay' => $dailyPrice->minimum_stay,
						'max_stay' => $dailyPrice->maximum_stay,
						'closed_arrival' => $dailyPrice->checkin_closed ? 1 : 0,
						'closed_departure' => $dailyPrice->exit_closed ? 1 : 0
					]
				]
			];
			$restriction = WuBook::restrictions($token, $hotel->l_code)->rplan_update_rplan_values(0, date('d/m/Y', strtotime($dailyPrice->date)), $values);
		}

		return response()->json(array('message' => ' Record updated successfully.'));
	}


	public function applyPriceRecursively($dailyPrice, $price, $rateDate)
	{

		$rateType = $dailyPrice->rateType;

		if (0 == $this->recurtionCount && $rateType->rateType) {
			$rateType->amount_to_add = 0;
			$rateType->percent_to_add = 0;
			$dailyPrice->force_price_update = 1;
			$dailyPrice->save();
		}

		if ($rateType->amount_to_add) {
			$price = $price + $rateType->amount_to_add;
		} elseif ($rateType->percent_to_add) {
			$amountToAdd = $rateType->percent_to_add / 100 * $price;
			$price = $price + $amountToAdd;
		}

		if (0 == $this->recurtionCount) {
			$product = $dailyPrice->product;
			$product->createPrice($price);
		} elseif (0 < $this->recurtionCount && !$dailyPrice->force_price_update) {
			$product = $dailyPrice->product;
			$product->createPrice($price);
		}

		$prices = [];

		if ($rateType->ref_id && (0 == $this->recurtionCount || (0 < $this->recurtionCount && !$dailyPrice->force_price_update))) {
			$token = WuBook::auth()->acquire_token();

			$dfromdmY = Carbon::parse($dailyPrice->date)->format('d/m/Y');

			$roomId = $dailyPrice->rateType->ref_id;
			$prices["$roomId "] = [(int)$price];

			$hotel = $dailyPrice->rateType->roomType->hotel;

			if (!$hotel->plan_id) {
				$plan = WuBook::prices($token)->add_pricing_plan('daily' . '_' . $hotel->name, 1);
				$hotel->plan_id = $plan['data'];
				$hotel->save();
			}

			$result = WuBook::prices($token, $hotel->l_code)->update_plan_prices($hotel->plan_id, $dfromdmY, $prices);
		}

		$this->recurtionCount++;

		if ($rateType->rateTypes) {
			foreach ($rateType->rateTypes as $childRateType) {

				$rateTypeChild = $childRateType;
				$rateTypeChild->rateDate = $rateDate;
				$dailyPriceChild = $rateTypeChild->dailyPrice;

				return $this->applyPriceRecursively($dailyPriceChild, $dailyPrice->product->price->price, $rateDate);
			}
		}
	}

	public function bookingCalender(Request $request, $id)
	{
		$user = auth()->user();
		$postData = $request->getContent();
		$postData = json_decode($postData, true);
		$dateFrom = $postData['date_from'];
		$dateTo = $postData['date_to'];
		$carbonFromDate = new Carbon($dateFrom);
		$carbonToDate = new Carbon($dateTo);
		$days = $carbonToDate->diffInDays($carbonFromDate);
		$inputRoomType = $request->input('room-type') ?: null;

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
				'rateType.roomType.hotel' => function ($q) use ($id) {
					$q->where('id', $id);
				}
			])->get();

		$keyedPrices = [];
		if ($dailyPrices) {
			foreach ($dailyPrices as $dailyPrice) {
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

		foreach ($roomsTypes as $roomType) {

			$rateTypes = $roomType->rateTypes;
			$resultData[$count] = ['name' => $roomType->roomTypeDetail->name];

			$totalRooms = Room::where('room_type_id', $roomType->id)
				->where('company_id', $user->company_id)
				->get()->count();

			$countJ = 0;
			$carbonFromDate = new Carbon($dateFrom);

			for ($i = 0; $i <= $days; $i++) {

				$bookedCount = 0;
				$rateDate = $carbonFromDate->format('Y-m-d');
				$result = $room->avaliability($roomType->id, $rateDate);

				if (isset($result) && 0 < sizeof($result)) {

					$bookedCount = $result[0]->count;
				}

				$avaliableRooms = $totalRooms - $bookedCount;
				$dailyPrice = new DailyPrice();
				$resultData[$count]['avaiability'][] = [
					'date' => $rateDate,
					'available' => $avaliableRooms
				];

				if (array_key_exists($rateDate, $totalRoomTypesOnDate)) {
					$totalRoomTypesOnDate[$rateDate] += $avaliableRooms;
				} else {
					$totalRoomTypesOnDate[$rateDate] = $avaliableRooms;
				}

				$carbonFromDate = $carbonFromDate->addDay();
			}

			foreach ($rateTypes as $rateType) {
				$resultData[$count]['rate_types'][$countJ]['id'] = $rateType->id;
				$resultData[$count]['rate_types'][$countJ]['name'] = $rateType->detail->name;
				$resultData[$count]['rate_types'][$countJ]['rate_type_id'] = $rateType->rate_type_id;
				$resultData[$count]['rate_types'][$countJ]['number_of_people'] = $rateType->number_of_people;
				$carbonFromDate = new Carbon($dateFrom);

				for ($i = 0; $i <= $days; $i++) {
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
		if ($totalRoomTypesOnDate) {
			foreach ($totalRoomTypesOnDate as $date => $dateAvailability) {

				$totalAvailabilityCount[] = [
					'date' => $date,
					'available' => $dateAvailability
				];
			}
		}

		return response()->json(['calendar_data' => $resultData, 'total_available' => $totalAvailabilityCount]);
	}
}
