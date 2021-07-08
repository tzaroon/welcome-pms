<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentAssignment;
use App\User;
use Illuminate\Http\Request;
use Validator;

class CashManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $payments = Payment::where('user_id', $user->id)->get();
        
       

        $assignments = new PaymentAssignment();

        $assignments = $assignments->whereNested(function($query) use ($user){

            $query->where('assigned_by', $user->id)->orWhere('assigned_to', $user->id);
        });

        if(array_key_exists('type', $postData)) {
            $assignments = $assignments->where('type', $postData['type']);
        }

        
        $assignments = $assignments->with(['assignedBy', 'assignedTo'])->get();

        return response()->json(['payments' => $payments, 'assignments' => $assignments]);
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
            'type' => 'required',  
            'amount' => 'required',    
        ], [], [
            'type' => 'Type',
            'amount' => 'Amount',
            'assigned_by' => 'Payment Method'
        ]);

        $user = auth()->user();

        if('user' == $postData['type'] && array_key_exists('user_id', $postData)) {
            $assignedUser = User::find($postData['user_id']);
            if(!$user->transaction_password || !$assignedUser->transaction_password || $user->transaction_password != $postData['giving_password'] || $postData['receiving_password'] != $assignedUser->transaction_password) {
                return response()->json(['error' => 'Password do not match.'], 401);
            }
        }

        PaymentAssignment::create([
            'amount' => $postData['amount'],
            'assigned_by' => $user->id,
            'assigned_to' => array_key_exists('user_id', $postData) ? $postData['user_id'] : null,
            'type' => $postData['type']
        ]);

        return response()->json(['message' => 'Saved Successfully']);
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
