<?php

 namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Validator;
use Illuminate\Support\Facades\Storage;


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
            'amount' => 'required'      
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
            'payment_date' => array_key_exists('payment_date', $postData) ? $postData['payment_date'] : date('Y-m-d'),
            'amount' => array_key_exists('amount', $postData) ? floatval($postData['amount']) : null,
            'payment_method' => array_key_exists('payment_method', $postData) && $postData['payment_method'] ? $postData['payment_method'] : Payment::TYPE_CASH,
            'initials' => array_key_exists('initials', $postData) ? $postData['initials'] : null,
            'payment_on_account' => array_key_exists('payment_on_account', $postData) ? $postData['payment_on_account'] : null,
            'operation_code' => array_key_exists('operation_code', $postData) ? $postData['operation_code'] : null,
            'notes' => array_key_exists('notes', $postData) ? $postData['notes'] : 'Paid on receiption',            
            'send_receipt' => array_key_exists('send_receipt', $postData) ? $postData['send_receipt'] : null
        ]);          

        if(array_key_exists('whatsapp_payment_link', $postData) && $postData['whatsapp_payment_link']) {
            
        }

        if(array_key_exists('email_payment_link', $postData) && $postData['email_payment_link']) {
            
        }

        if(array_key_exists('sms_payment_link', $postData) && $postData['sms_payment_link']) {
            
        }

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
   

    public function showReceipt(Request $request , Payment $payment  , $detailed) {
      

        if(!$payment){
            return response()->json(['message' => 'Payment not found']);  
        }        
       
        $pdf = app('Fpdf');
        $pdf->SetDrawColor(220,220,220);
        $pdf->SetFont('Arial','',10);
        $pdf->AddPage();
       
        $x = 40;
        $y = 20;
        $pdf->SetXY($x, $y); 
        $pdf->Cell(20,10,'CHIC');
        $y += 6;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,10,'STAYS');
        $x =  $x - 20;
        $y += 20;
        $yIncremenent = 6;
        $fontSize = 8;

        $pdf->SetXY($x, $y); 
        $pdf->Cell(20,$fontSize,'Chicstays S.L');

        $pdf->SetXY(150, $y);
        $pdf->Cell(20,10, $payment->booking->booker->user->first_name . ' ' . $payment->booking->booker->user->last_name );

        $y += $yIncremenent;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,$fontSize,'Ali Bei 15');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,10,'08010');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,$fontSize,'Barcelona (Espana)');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,$fontSize,'VAT : (B65121618)');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->SetFont('Arial','B',$fontSize); 
        $pdf->Cell(20,10,'Receipt #  : ' . $payment->id);
        $y += $yIncremenent ;
        
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,10, date('D F j, Y', strtotime($payment->payment_date)));

        $y += $yIncremenent ; 
        $y += $yIncremenent ; 
        $i = 20;
        $rooms = $payment->booking->rooms;
             
        if($detailed){
            $i = 0;
            $pdf->SetXY($x, $y); 
            $pdf->SetFont('Arial','', $fontSize); 
            $pdf->Cell(20,10,'ITEM');
            $pdf->SetXY(150, $y); 
            $pdf->Cell(20,10,'TOTAL');
            $y += $yIncremenent ;

            foreach($rooms as $room)
            {            
                $pdf->SetXY($x, $y);  
                $pdf->Cell(20,10, $room->name . $room->roomType->roomTypeDetail->name); 
                $y += $yIncremenent ;
            }       
            
            $pdf->SetFont('Arial','B', $fontSize);
            $pdf->SetXY($x, $y);
            $pdf->Cell(50,10, 'Room night ' . $payment->booking->reservation_from .' To ' . $payment->booking->reservation_to); 
            $pdf->SetFont('Arial','', $fontSize);  

            $pdf->SetXY(150, $y);   
        
            $pdf->Cell(20,10, $payment->booking['price']['price']); 
            $y += $yIncremenent ;
            $pdf->SetXY($x, $y); 
            $pdf->Cell(20,10, $payment->booking->adult_count. ' Adults '. '* ' . 'Tourist Tax Adultos '); 
            $pdf->SetXY(150, $y);
            $pdf->Cell(20,10, $payment->booking['price']['tax']);
        
        }
       
        $pdf->SetFont('Arial','', $fontSize);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetDrawColor(51,51,51);       
        $pdf->Rect(21, 125 - $i, 150, 30);
        $y += $yIncremenent ;
        $y += $yIncremenent ;
            
         
        $pdf->SetTextColor(51,51,51); 

        $pdf->SetFont('Arial','B', $fontSize); 
        $pdf->SetXY(130, $y);
        $pdf->Cell(20,10,'Paid on Account'); 
        $y += $yIncremenent ;
        $pdf->SetXY(130, $y);
        $pdf->SetFont('Arial','B', $fontSize);  
       
        $pdf->Cell(20,10, $payment->booking['price']['total']);
       
        $y += $yIncremenent ;
        $pdf->SetXY(130, $y);
        $pdf->Cell(20,10,'Taxes Inc'); 
        $y += $yIncremenent ;
        $pdf->SetXY(130, $y);
        $pdf->SetFont('Arial','', $fontSize);
        $pdf->Cell(20,10,'Payment Method : ' . $payment->payment_method);

        $y += $yIncremenent ;
        $y += $yIncremenent ;
        $pdf->SetXY(40, $y);
        $pdf->Cell(20,10,'Casa  Boutique Barcelona | info@chicstays.com | +34615967283'); 
        $y += $yIncremenent ;

        $pdf->SetXY(40, $y);
        $pdf->Cell(20,10,'Nota a pie de factura que sa  pone on Ajustes abajo del todo');

        
       $pdf->Output('I');
       exit;

        $fileName =  $payment->id . '-' . time() . '.pdf';
        //save file
        Storage::put('/public/' . $fileName, $pdf->Output('S'));
        //$pdf->Output($$fileName, 'D');

        return response()->json(['file' => 'storage/' . $fileName]);
    }


}
