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

class RoomTypesController extends Controller
{
    public function index(Request $request) {

    }
    /**
     * List all resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function list(Request $request, $hotel) : JsonResponse
    {
        $user = auth()->user();
        $rooms = RoomType::where(['company_id' => $user->company_id, 'hotel_id' => $hotel])
            ->with(
                [
                    'roomTypeDetail',
                    'rateTypes',
                    'rateTypes.detail',
                    'rateTypes.rateType',
                    'rateTypes.rateType.detail',
                    'rateTypes.rateType.roomType.roomTypeDetail'
                ]
            )->get();

        return response()->json($rooms);
    }

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
            'category_id' => 'required',
            'max_people' => 'required',
            'room_type_details.0.name' => 'required|string'
        ], [], [
            'category_id' => 'Category',
            'max_people' => 'Max people',
            'room_type_details.0.name' => 'Name',
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $roomType = new RoomType();
        $roomType->category_id = $postData['category_id'];
        $roomType->company_id = $user->company_id;
        $roomType->hotel_id = $postData['hotel_id'];
        $roomType->max_people = $postData['max_people'];
        $roomType->save();

        $details = $postData['room_type_details'];

        foreach($details as $detail) {
            
            $roomTypeDetail = new RoomTypeDetail();
            $roomTypeDetail->company_id = $user->company_id;
            $roomTypeDetail->room_type_id = $roomType->id;
            $roomTypeDetail->language_id = $detail['language_id'];
            $roomTypeDetail->name = $detail['name'];
            $roomTypeDetail->description = $detail['description'];
            $roomTypeDetail->name_singular = $detail['name_singular'];
            $roomTypeDetail->name_plural = $detail['name_plural'];

            $roomTypeDetail->save();
        }

        return response()->json(['success'=> true]);
    }

    /**
     * Update a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\User $user
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, RoomType $roomType) : JsonResponse
    {
        $user = auth()->user();
        
        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'id' => 'required',
            'category_id' => 'required',
            'max_people' => 'required',
            'room_type_details.0.name' => 'required|string'
        ], [], [
            'category_id' => 'Category',
            'max_people' => 'Max people',
            'room_type_details.0.name' => 'Name'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $details = $postData['room_type_details'];
        unset($postData['room_type_details']);

        $roomType->fill($postData);
        $roomType->update();

        foreach($details as $detail) {
            $roomTypeDetail = new RoomTypeDetail();
            
            if(array_key_exists('id', $detail)) {
                $roomTypeDetail = $roomTypeDetail->firstOrNew(['id' => $detail['id']]);
            }

            $roomTypeDetail->company_id = $user->company_id;
            $roomTypeDetail->room_type_id = $roomType->id;
            $roomTypeDetail->language_id = $detail['language_id'];
            $roomTypeDetail->name = $detail['name'];
            $roomTypeDetail->description = $detail['description'];
            $roomTypeDetail->name_singular = $detail['name_singular'];
            $roomTypeDetail->name_plural = $detail['name_plural'];

            $roomTypeDetail->save();
        }
        return response()->json(['success'=> true]);
    }

    /**
     * Destroy a resource.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Model\Hotel $hotel
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, RoomType $roomType) : JsonResponse
    {
       
        $roomType->delete();

        return response()->json(array('message' => 'Room type deleted successfully'));
    }
}
