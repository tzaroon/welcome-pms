<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Validator;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = auth()->user();

        $rooms = Room::where('company_id', $user->company_id)->with(
            [
                'roomType',
                'roomType.roomTypeDetail'
            ]
        )->whereHas('roomType', function($q) use ($id){
            $q->where('hotel_id', $id);
        })->get();

        $data = $this->paginate($rooms);

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'room_type_id' => 'required',
            'name' => 'required|string',
            'room_number' => 'required',
            'number_of_rooms' => 'required'
        ], [], [
            'room_type_id' => 'Room Type',
            'name' => 'Name',
            'number_of_rooms' => 'Number of rooms',
            'room_number' => 'Room number'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        for($i=0; $i < $postData['number_of_rooms']; $i++) {

            $room = new Room();
            $room->name = $postData['name'];
            $room->room_number = $postData['room_number']++;
            $room->company_id = $user->company_id;
            $room->room_type_id =  $postData['room_type_id'];
            $room->save();
        }
        
        return response()->json($room);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        $room->roomType->roomTypeDetail;
        return response()->json($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $user = auth()->user();

        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'room_type_id' => 'required',
            'name' => 'required|string',
            'room_number' => 'required',
        ], [], [
            'room_type_id' => 'Room Type',
            'name' => 'Name',
            'room_number' => 'Room number'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $room->fill($postData);
        $room->company_id = $user->company_id;
        $room->save();

        return response()->json($room);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['success' => true, 'message' => 'Room deleted successfully.']);
    }
}