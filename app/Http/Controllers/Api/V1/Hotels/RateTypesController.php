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
use App\Models\Tax;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Rules\DuplicateRateTypeValidationRule;

class RateTypesController extends Controller
{
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
        $details = $postData['rate_type_details'];       
        $name = $details[0]['name'];
        $roomTypeId = $postData['room_type_id'];
        // return response()->json($apply_rate_from);

        $validator = Validator::make($postData, [
            'room_type_id' => 'required',
            'number_of_people' => 'required',
            'price' => 'required_without:rate_type_id',
            'apply_rate_from' => 'required|date',
            'apply_rate_to' => 'required|date',
            'rate_type_details.0.name' => [
                'required',
                'string',
                new DuplicateRateTypeValidationRule($roomTypeId)              
            ],
        ], [], [
            'room_type_id' => 'Room type',
            'rate_type_id' => 'Rate type',
            'number_of_people' => 'Number of persons',
            'rate_type_details.0.name' => 'Rate type name'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        // calculate total number of months between two dates
        $result = CarbonPeriod::create($postData['apply_rate_from'], '1 month', $postData['apply_rate_to']);
        $totalMonths = [];

        foreach ($result as $dt) {
            $totalMonths[] = $dt->format("Y-m");
        }

        // return response()->json($totalMonths);
        // return response()->json(count($totalMonths));
        if (count($totalMonths) > 18){
            return response()->json(['errors' => ['apply_rate_from' => ['Range between dates exceeds the limit of 18 months!']]]);
        }
        // ----------------------------------------------------------

        DB::transaction(function() use ($user, $postData) {

            $rateType = new RateType();
            $rateType->company_id = $user->company_id;
            $rateType->room_type_id = array_key_exists('room_type_id', $postData) ? $postData['room_type_id'] : null;
            $rateType->rate_type_id = array_key_exists('rate_type_id', $postData) ? $postData['rate_type_id'] : null;
            $rateType->number_of_people = array_key_exists('number_of_people', $postData) ? $postData['number_of_people'] : null;
            $rateType->advance = array_key_exists('advance', $postData) ? $postData['advance'] : null;
            $rateType->show_in_booking_engine = array_key_exists('show_in_booking_engine', $postData) ? $postData['show_in_booking_engine'] : 0;
            $rateType->amount_to_add = array_key_exists('amount_to_add', $postData) ? $postData['amount_to_add'] : 0;
            $rateType->percent_to_add = array_key_exists('percent_to_add', $postData) ? $postData['percent_to_add'] : 0;

            $rateType->price = (array_key_exists('price', $postData) && $postData['price'] > 0) ? $postData['price'] : $rateType->rate_type_price;

            $rateType->tax_1_amount = array_key_exists('tax_1_amount', $postData) ? $postData['tax_1_amount'] : 0;
            $rateType->tax_2_amount = array_key_exists('tax_2_amount', $postData) ? $postData['tax_2_amount'] : 0;
            $rateType->tax_1_percentage = array_key_exists('tax_1_percentage', $postData) ? $postData['tax_1_percentage'] : 0;
            $rateType->tax_2_percentage = array_key_exists('tax_2_percentage', $postData) ? $postData['tax_2_percentage'] : 0;

            $rateType->apply_rate_from = array_key_exists('apply_rate_from', $postData) ? $postData['apply_rate_from'] : null;
            $rateType->apply_rate_to = array_key_exists('apply_rate_to', $postData) ? $postData['apply_rate_to'] : null;
            $rateType->apply_rates_days = array_key_exists('apply_rates_days', $postData) ? json_encode($postData['apply_rates_days']) : null;
            $rateType->checkin_closed = array_key_exists('checkin_closed', $postData) ? $postData['checkin_closed'] : 0;
            $rateType->exit_closed = array_key_exists('exit_closed', $postData) ? $postData['exit_closed'] : 0;
            $rateType->minimum_stay = array_key_exists('minimum_stay', $postData) ? $postData['minimum_stay'] : null;
            $rateType->maximum_stay = array_key_exists('maximum_stay', $postData) ? $postData['maximum_stay'] : null;
            $rateType->max_booking_hour = array_key_exists('max_booking_hour', $postData) ? $postData['max_booking_hour'] : null;

            $rateType->save();

            $start = Carbon::parse($postData['apply_rate_from']);
            $end =  Carbon::parse($postData['apply_rate_to']);

            $days = $end->diffInDays($start);

            $date = $start;
            
            $applyRateDays = [];
            if(isset($postData['apply_rates_days'])) {

                foreach($postData['apply_rates_days'] as $rateDay) {
                    if(true == $rateDay['value']) {
                        $applyRateDays[] = $rateDay['day'];
                    }
                }
            }

            for($i=0; $i <= $days; $i++) {

                $dayofweek = date('w', strtotime($date));
                if(sizeof($applyRateDays) > 0 && !in_array($dayofweek, $applyRateDays)) {
                    $date = $date->addDay();
                    continue;
                }
                
                $product = new Product();
                $product->company_id = $user->company_id;
                $product->type = Product::TYPE_ROOM;
                $product->save();

                $dailyPrice = new DailyPrice();
                $dailyPrice->company_id = $user->company_id;
                $dailyPrice->rate_type_id = $rateType->id;
                $dailyPrice->product_id = $product->id;
                $dailyPrice->date = $date->format('Y-m-d');
                $dailyPrice->checkin_closed = array_key_exists('checkin_closed', $postData) ? $postData['checkin_closed'] : 0;
                $dailyPrice->exit_closed = array_key_exists('exit_closed', $postData) ? $postData['exit_closed'] : 0;
                $dailyPrice->minimum_stay = array_key_exists('minimum_stay', $postData) ? $postData['minimum_stay'] : 0;
                $dailyPrice->maximum_stay = array_key_exists('maximum_stay', $postData) ? $postData['maximum_stay'] : 0;

                $dailyPrice->save();

                $rateTypePrice = $rateType->rate_type_price;

                if($postData['rate_type_id']) {

                    $masterRateType = new RateType();
                    $masterRateType = $masterRateType->find($postData['rate_type_id']);
                   
                    $amountToAdd = $rateType->amount_to_add;
                    
                    if($rateType->percent_to_add) {
                        $amountToAdd = $masterRateType->rate_type_price*$rateType->percent_to_add/100;
                    }

                    $rateTypePrice = $masterRateType->rate_type_price + $amountToAdd;
                }

                $taxes = [];
                if($postData['tax_1_amount']) {
                    $taxes[Tax::CITY_TAX]['tax_id'] = Tax::CITY_TAX;
                    $taxes[Tax::CITY_TAX]['amount'] = $postData['tax_1_amount'];                   
                }
                else if($postData['tax_1_percentage']) {
                    $taxes[Tax::CITY_TAX]['tax_id'] = Tax::CITY_TAX;
                    $taxes[Tax::CITY_TAX]['percentage'] = $postData['tax_1_percentage'];                   
                }
                if($postData['tax_2_amount']) {
                    $taxes[Tax::CHILDREN_CITY_TAX]['tax_id'] = Tax::CHILDREN_CITY_TAX;
                    $taxes[Tax::CHILDREN_CITY_TAX]['amount'] = $postData['tax_2_amount'];                    
                }
                else if($postData['tax_2_percentage']) {
                    $taxes[Tax::CHILDREN_CITY_TAX]['tax_id'] = Tax::CHILDREN_CITY_TAX;
                    $taxes[Tax::CHILDREN_CITY_TAX]['percentage'] = $postData['tax_2_percentage'];                    
                }

                $product->createPrice($rateTypePrice, $taxes);
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
        return response()->json(['message' => 'Rate type saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RateType $rateType)
    {
        $rateType->details;
        return response()->json($rateType);
    }

    /**
     * Update a resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, RateType $rateType) : JsonResponse
    {
        $user = auth()->user();
        
        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);       

        $details = $postData['rate_type_details'];       
        $name = $details[0]['name'];
        $roomTypeId = $postData['room_type_id']; 

        $validator = Validator::make($postData, [
            'room_type_id' => 'required',
            'number_of_people' => 'required',
            'price' => 'required',
            'apply_rate_from' => 'required',
            'apply_rate_to' => 'required',
            'rate_type_details.0.name' => [
                'required',
                'string',
                new DuplicateRateTypeValidationRule($roomTypeId, $rateType->id)        
            ],
        ], [], [
            'room_type_id' => 'Room type',
            'number_of_people' => 'Number of persons',
            'rate_type_details.0.name' => 'First rate type name'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        // calculate total number of months between two dates
        $result = CarbonPeriod::create($postData['apply_rate_from'], '1 month', $postData['apply_rate_to']);
        $totalMonths = [];

        foreach ($result as $dt) {
            $totalMonths[] = $dt->format("Y-m");
        }

        // return response()->json($totalMonths);
        // return response()->json(count($totalMonths));
        if (count($totalMonths) > 18){
            return response()->json(['errors' => 'Range between dates exceeds the limit of 18 months!']);
        }
        // ----------------------------------------------------------

        DB::transaction(function() use ($user, $rateType, $postData) {

            $rateType->company_id = $user->company_id;
            $rateType->room_type_id = array_key_exists('room_type_id', $postData) ? $postData['room_type_id'] : null;
            $rateType->rate_type_id = array_key_exists('rate_type_id', $postData) ? $postData['rate_type_id'] : null;
            $rateType->number_of_people = array_key_exists('number_of_people', $postData) ? $postData['number_of_people'] : null;
            $rateType->advance = array_key_exists('advance', $postData) ? $postData['advance'] : null;
            $rateType->show_in_booking_engine = array_key_exists('show_in_booking_engine', $postData) ? $postData['show_in_booking_engine'] : 0;;
            $rateType->price = array_key_exists('price', $postData) ? $postData['price'] : 0;
            $rateType->tax_1_amount = array_key_exists('tax_1_amount', $postData) ? $postData['tax_1_amount'] : 0;
            $rateType->tax_2_amount = array_key_exists('tax_2_amount', $postData) ? $postData['tax_2_amount'] : 0;
            $rateType->tax_1_percentage = array_key_exists('tax_1_percentage', $postData) ? $postData['tax_1_percentage'] : 0;
            $rateType->tax_2_percentage = array_key_exists('tax_2_percentage', $postData) ? $postData['tax_2_percentage'] : 0;
            $rateType->amount_to_add = array_key_exists('amount_to_add', $postData) ? $postData['amount_to_add'] : 0;
            $rateType->percent_to_add = array_key_exists('percent_to_add', $postData) ? $postData['percent_to_add'] : 0;
            $rateType->apply_rate_from = array_key_exists('apply_rate_from', $postData) ? $postData['apply_rate_from'] : null;
            $rateType->apply_rate_to = array_key_exists('apply_rate_to', $postData) ? $postData['apply_rate_to'] : null;

            $rateType->apply_rates_days = array_key_exists('apply_rates_days', $postData) ? json_encode($postData['apply_rates_days']) : null;
            $rateType->checkin_closed = array_key_exists('checkin_closed', $postData) ? $postData['checkin_closed'] : 0;
            $rateType->exit_closed = array_key_exists('exit_closed', $postData) ? $postData['exit_closed'] : 0;
            $rateType->minimum_stay = array_key_exists('minimum_stay', $postData) ? $postData['minimum_stay'] : null;
            $rateType->maximum_stay = array_key_exists('maximum_stay', $postData) ? $postData['maximum_stay'] : null;
            $rateType->max_booking_hour = array_key_exists('max_booking_hour', $postData) ? $postData['max_booking_hour'] : null;
            $rateType->save();

            $start = Carbon::parse($postData['apply_rate_from']);
            $end =  Carbon::parse($postData['apply_rate_to']);

            $days = $end->diffInDays($start);

            $date = $start;
            for($i=0; $i <= $days; $i++) {

                $dailyPrice = DailyPrice::firstOrNew([
                    'company_id' => $user->company_id,
                    'date' => $date->format('Y-m-d'),
                    'rate_type_id' => $rateType->id
                ]);

                if(!$dailyPrice->id) {

                    $product = new Product();
                    $product->company_id = $user->company_id;
                    $product->type = Product::TYPE_ROOM;
                    $product->save();

                    $dailyPrice->product_id = $product->id;
                } else {
                    $product = Product::find($dailyPrice->product_id);
                }

                $rateTypePrice = $rateType->rate_type_price;

                if($postData['rate_type_id']) {

                    $masterRateType = new RateType();
                    $masterRateType = $masterRateType->find($postData['rate_type_id']);
                   
                    $amountToAdd = $rateType->amount_to_add;
                    
                    if($rateType->percent_to_add) {
                        $amountToAdd = $masterRateType->rate_type_price*$rateType->percent_to_add/100;
                    }
                    
                    $rateTypePrice = $masterRateType->rate_type_price + $amountToAdd;
                }

                $taxes = [];
                if($postData['tax_1_amount']) {
                    $taxes[Tax::CITY_TAX]['tax_id'] = Tax::CITY_TAX;
                    $taxes[Tax::CITY_TAX]['amount'] = $postData['tax_1_amount'];                   
                }
                else if($postData['tax_1_percentage']) {
                    $taxes[Tax::CITY_TAX]['tax_id'] = Tax::CITY_TAX;
                    $taxes[Tax::CITY_TAX]['percentage'] = $postData['tax_1_percentage'];                   
                }
                if($postData['tax_2_amount']) {
                    $taxes[Tax::CHILDREN_CITY_TAX]['tax_id'] = Tax::CHILDREN_CITY_TAX;
                    $taxes[Tax::CHILDREN_CITY_TAX]['amount'] = $postData['tax_2_amount'];                    
                }
                else if($postData['tax_2_percentage']) {
                    $taxes[Tax::CHILDREN_CITY_TAX]['tax_id'] = Tax::CHILDREN_CITY_TAX;
                    $taxes[Tax::CHILDREN_CITY_TAX]['percentage'] = $postData['tax_2_percentage'];                    
                }
                
                $product->createPrice($rateTypePrice, $taxes);

                $dailyPrice->company_id = $user->company_id;
                $dailyPrice->rate_type_id = $rateType->id;
                $dailyPrice->date = $date->format('Y-m-d');
                $dailyPrice->checkin_closed = array_key_exists('checkin_closed', $postData) ? $postData['checkin_closed'] : 0;
                $dailyPrice->exit_closed = array_key_exists('exit_closed', $postData) ? $postData['exit_closed'] : 0;
                $dailyPrice->minimum_stay = array_key_exists('minimum_stay', $postData) ? $postData['minimum_stay'] : null;
                $dailyPrice->maximum_stay = array_key_exists('maximum_stay', $postData) ? $postData['maximum_stay'] : null;

                $dailyPrice->save();

                $date = $date->addDay();
            }
        });

        $details = $postData['rate_type_details'];

        foreach($details as $detail) {

            $rateTypeDetail = new RateTypeDetail();

            if(array_key_exists('id', $detail)) {
                $rateTypeDetail = $rateTypeDetail->find($detail['id']);
            }

            $rateTypeDetail->company_id = $user->company_id;
            $rateTypeDetail->rate_type_id = $rateType->id;
            $rateTypeDetail->language_id = $detail['language_id'];
            $rateTypeDetail->name = $detail['name'];
            $rateTypeDetail->unique_feature = $detail['unique_feature'];

            $rateTypeDetail->save();
        }

        return response()->json(['message' => 'Rate type saved successfully.']);
    }

    public function rateTypeList($roomType) {

        $rateTypes = RateType::where('room_type_id', $roomType)->whereNull('rate_type_id')->with('detail')->get();
        return response()->json($rateTypes);
    }

    /**
     * Destroy a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Model\Hotel $hotel
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, RateType $rateType) : JsonResponse
    {
        $rateType = RateType::find($rateType->id);
        $rateType->delete();

        return response()->json(array(
            'message' => 'Rate type deleted successfully',
        ));
    }
}