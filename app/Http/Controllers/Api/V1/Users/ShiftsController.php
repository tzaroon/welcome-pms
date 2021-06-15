<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class ShiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $authUser = auth()->user();
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'from_date' => 'required',
            'to_date' => 'required',
            'role_id' => 'required',
            'shift' => 'required',
            'user_id' => 'required',
        ], [], [
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'role_id' => 'Role',
            'shift' => 'Shift',
            'user_id' => 'User'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }
       
        $startDate = Carbon::parse($postData['from_date']); 
        $endDate = Carbon::parse($postData['to_date']);
        
        $days = $endDate->diffInDays($startDate);

        $calendarStartDate = Carbon::parse($postData['from_date']);

        for($i = 0 ; $i < $days ; $i++ )
        {
            $shiftDate = $calendarStartDate->format('Y-m-d');

            $shift = Shift::create([
                'role_id' => array_key_exists('role_id', $postData) ? $postData['role_id'] : null,
                'shift' => array_key_exists('shift', $postData) ? $postData['shift'] : null,
                'date' => $shiftDate,
                'user_id' => array_key_exists('user_id', $postData) ? $postData['user_id'] : null
            ]);

            $calendarStartDate = $calendarStartDate->addDay();

        }
        
        return response()->json(['message' => 'Shift Saved Successfully']);
        
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
