<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Validator;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Booking $booking) {        
        if(0 == $booking->payments->count()) {
            return response()->json(['message' => 'no data found'], 201);
        }
        return response()->json($booking->payments);
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
    public function store(Request $request, Booking $booking) {
       
        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'payment_date' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'operation_code' => 'required'
            
        ], [], [
            'payment_date' => 'Payment Date',
            'amount' => 'Amount',
            'payment_method' => 'Payment Method',
            'operation_code' => 'Operation Code'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }      

        $payment = Payment::create([            
            'booking_id' =>  array_key_exists('booking_id', $postData) ? $postData['booking_id'] : null,
            'payment_date' => array_key_exists('payment_date', $postData) ? $postData['payment_date'] : null,
            'amount' => array_key_exists('amount', $postData) ? $postData['amount'] : null,
            'payment_method' => array_key_exists('payment_method', $postData) ? $postData['payment_method'] : null,
            'initials' => array_key_exists('initials', $postData) ? $postData['initials'] : null,
            'payment_on_account' => array_key_exists('payment_on_account', $postData) ? $postData['payment_on_account'] : null,
            'operation_code' => array_key_exists('operation_code', $postData) ? $postData['operation_code'] : null,
            'notes' => array_key_exists('notes', $postData) ? $postData['notes'] : null,            
            'send_receipt' => array_key_exists('send_receipt', $postData) ? $postData['send_receipt'] : null
        ]);          

        return response()->json($payment);
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
    public function edit(Request $request,Booking $booking, $payment) {
        
        $payment =  Payment::where('id', $payment)->first();
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking, $payment) {
        
       
        $postData = $request->getContent();       
        
        $postData = json_decode($postData, true);

        $payment =  Payment::where('id', $payment)->first();

        if (!$payment) {

            return response()->json(array('errors' => ['payment' => 'Invalid payment id']), 422);
        }

        $validator = Validator::make($postData, [
            'payment_date' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'operation_code' => 'required'            
            
        ], [], [
            'payment_date' => 'Payment Date',
            'amount' => 'Amount',
            'payment_method' => 'Payment Method',
            'operation_code' => 'Operation Code'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }
        
        $payment->payment_date = $postData['payment_date'];
        $payment->amount = $postData['amount'];
        $payment->payment_method = $postData['payment_method'];
        $payment->operation_code = $postData['operation_code'];
        $payment->notes = array_key_exists('notes', $postData) ? $postData['notes'] : null;   
        $payment->payment_on_account = array_key_exists('payment_on_account', $postData) ? $postData['payment_on_account'] : null;
        $payment->send_receipt = array_key_exists('send_receipt', $postData) ? $postData['send_receipt'] : null;
        $payment->save();

        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Booking $booking, $payment) 
    {        
        $payment =  Payment::where('id', $payment)->first();

        if(!$payment){
            return response()->json(['message' => 'Payment not found']);  
        }

        $payment->delete();        
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
