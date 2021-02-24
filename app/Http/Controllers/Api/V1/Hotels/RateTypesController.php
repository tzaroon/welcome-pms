<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\User;
use App\Http\Controllers\Controller;
use App\Models\DailyPrice;
use App\Models\Hotel;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\RateType;
use App\Models\RateTypeDetail;
use App\Models\RoomType;
use App\Models\RoomTypeDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use DB;

class RateTypesController extends Controller
{
    /**
     * Show a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\RoomType $roomType
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function edit(RoomType $roomType) : JsonResponse
    {
        $roomType->roomTypeDetails;
        return response()->json($roomType);
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
            'room_type_id' => 'required',
            'number_of_people' => 'required',
            'price' => 'required',
            'rate_type_details.0.name' => 'required|string'
        ], [], [
            'room_type_id' => 'Room type',
            'number_of_people' => 'Number of persons',
            'rate_type_details.0.name' => 'First rate type name'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function() use ($user, $postData) {

            $rateType = new RateType();
            $rateType->company_id = $user->company_id;
            $rateType->room_type_id = $postData['room_type_id'];
            $rateType->rate_type_id = $postData['rate_type_id'];
            $rateType->number_of_people = $postData['number_of_people'];
            $rateType->advance = $postData['advance'];
            $rateType->show_in_booking_engine = $postData['show_in_booking_engine'];
            $rateType->price = array_key_exists('price', $postData) ? $postData['price'] : 0;
            $rateType->amount_to_add = array_key_exists('amount_to_add', $postData) ? $postData['amount_to_add'] : 0;
            $rateType->percent_to_add = array_key_exists('percent_to_add', $postData) ? $postData['percent_to_add'] : 0;
            $rateType->save();

            $start = Carbon::parse($postData['apply_rate_from']);
            $end =  Carbon::parse($postData['apply_rate_to']);

            $days = $end->diffInDays($start);

            $date = $start;
            for($i=0; $i <= $days; $i++) {

                $product = new Product();
                $product->company_id = $user->company_id;
                $product->type = Product::TYPE_ROOM;
                $product->save();

                $dailyPrice = new DailyPrice();
                $dailyPrice->company_id = $user->company_id;
                $dailyPrice->rate_type_id = $rateType->id;
                $dailyPrice->product_id = $product->id;
                $dailyPrice->date = $date->format('Y-m-d');
                $dailyPrice->checkin_closed = $postData['checkin_closed'];
                $dailyPrice->exit_closed = $postData['exit_closed'];
                $dailyPrice->minimum_stay = $postData['minimum_stay'];
                $dailyPrice->maximum_stay = $postData['maximum_stay'];

                $dailyPrice->save();

                $product->createPrice($postData['price'], $postData['taxes']);
                $date = $date->addDay();
            }

            $details = $postData['rate_type_details'];

            foreach($details as $detail) {
                $rateTypeDetail = new RateTypeDetail();
                $rateTypeDetail->company_id = $user->company_id;
                $rateTypeDetail->rate_type_id = $rateType->id;
                $rateTypeDetail->language_id = $detail['language_id'];
                $rateTypeDetail->name = $detail['name'];
                $rateTypeDetail->unique_feature = $detail['unique_feature'];

                $rateTypeDetail->save();
            }
        });
        return response()->json(['success'=> true, 'message' => 'Rate type saved successfully.']);
    }
}
