<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Restriction;
use Validator;

class RestrictionsController extends Controller
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
    public function store(Request $request)
    {
        $postData = $request->getContent();             
        $postData = json_decode($postData, true);              

        $validator = Validator::make($postData, [
            'arrival' => 'required',
            'departure' => 'required',
            'actions' => 'required'                      
            
        ], [], [
            'arrival' => 'Arrival Date',
            'departure' => 'Departure Date',
            'actions' => 'Actions'            
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }      

        if(array_key_exists('specific_days', $postData)){
            $specificDays = json_encode($postData['specific_days']);
        }
        if(array_key_exists('specific_days', $postData)){
            $cancellationPolicies = json_encode($postData['cancellation_policies']);
        }           

        $restriction = Restriction::create([            
            'room_id' =>  array_key_exists('room_id', $postData) ? $postData['room_id'] : null,
            'arrival' => array_key_exists('arrival', $postData) ? $postData['arrival'] : null,
            'departure' => array_key_exists('departure', $postData) ? $postData['departure'] : null,
            'specific_days' => $specificDays,
            'actions' => array_key_exists('actions', $postData) ? $postData['actions'] : null,
            'days' => array_key_exists('days', $postData) ? $postData['days'] : null,
            'cancellation_policies' => $cancellationPolicies,
            'release' => array_key_exists('release', $postData) ? $postData['release'] : null                      
        ]);          

        return response()->json($restriction);
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
