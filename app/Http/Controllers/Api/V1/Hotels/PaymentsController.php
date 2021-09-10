<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentLink;
use App\Models\ContactDetail;
use App\Models\Conversation;
use Validator;
use Illuminate\Support\Facades\Storage;
use DB;


use App\PaymentClass\paycomet_bankstore;
use App\Services\Twilio\WhatsAppService;
use App\Services\Twilio\SmsService;
use App\Mail\SendPaymentInfo; 


class PaymentsController extends Controller
{
    protected $whatsApp;
    protected $sms;

    public function __construct(WhatsAppService $whatsApp,SmsService $sms)
    {
        $this->whatsApp = $whatsApp;
        $this->sms = $sms;
    }
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

        $user = auth()->user();

        $payment = Payment::create([            
            'booking_id' =>  array_key_exists('booking_id', $postData) ? $postData['booking_id'] : null,
            'user_id' =>  $user->id,
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
        
        $payment->payment_date = array_key_exists('payment_date', $postData) ? $postData['payment_date'] : date('Y-m-d');
        $payment->amount = $postData['amount'];
        $payment->payment_method = (array_key_exists('payment_method', $postData) && $postData['payment_method']) ? $postData['payment_method'] : Payment::TYPE_CASH;
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


    public function generateLink(Request $request, Booking $booking) {

        $roomNames = [];
        foreach($booking->rooms as $room){
            $roomNames[] = $room->name;
        }

        $roomNames = json_encode($roomNames); // array to string conversion

        $totalAdults = $booking->adult_count;
        $totalChildrens = $booking->children_count;
        $totalRooms = $booking->roomCount;

        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $number = number_format($postData['amount'], 2, '.', '');
        $amount = $number * 100;

        $merchantCode	= "h893x7h4";
        $password		= "y56mk9r2hxwjn7zhtdwu";
        $terminal		= "31999";
        $jetid			= NULL; // Optional

        $paycomet = new Paycomet_Bankstore($merchantCode, $terminal, $password, $jetid);
        
        $description = "totalAdults: ".$totalAdults.", totalChildrens: ".$totalChildrens.
                       ", totalRooms: ".$totalRooms.", roomNames ".$roomNames;
        
        // get payment link
        $response = $paycomet->ExecutePurchaseUrl($booking->id, $amount, "EUR", "EN", $description, true);
        return response()->json($response);

        if ($response->RESULT == "OK") {
            return response()->json(['paymentLink' => $response->URL_REDIRECT]);
        } else {
            return response()->json(['errors' => ['paymentLink' => ['Payment link is not generated!']]]);
        }
    }


    public function sendPaymentLink(Request $request, Booking $booking){

        $admin = auth()->user();
        
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);
        // return $booking->booker->user;       

        $validator = Validator::make($postData, [
            'amount' => 'required',
            'payment_url' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }
        
        //----------------------------------------------------------------
        $type = $postData['whatsapp'];
        $type = array_keys($postData, $type);
        $whatsapp = $type[0];

        $type = $postData['email'];
        $type = array_keys($postData, $type);
        $email = $type[0];

        $type = $postData['sms'];
        $type = array_keys($postData, $type);
        $type[0] == 'whatsapp' ? $sms = $type[1] : $sms = $type[0];

        $whatsappValue = ContactDetail::where("user_id",$booking->booker->user->id)->where("type","whatsapp")->first(["id","contact"]);
        $emailValue = ContactDetail::where("user_id",$booking->booker->user->id)->where("type","email")->first(["id","contact"]);
        $smsValue = ContactDetail::where("user_id",$booking->booker->user->id)->where("type","sms")->first(["id","contact"]);

        DB::transaction(function () use ($booking, $postData, $admin, $whatsapp, $email, $sms, $whatsappValue, $emailValue, $smsValue, &$paymentLink) {

            if (gettype($postData['whatsapp']) != 'NULL'){
                
                if($whatsappValue && $postData['whatsapp'] == $whatsappValue->contact){
                    $contactDetail = ContactDetail::find($whatsappValue->id);
                    // return $contactDetail;
                } else {
                    $contactDetail = new ContactDetail;
                    $contactDetail->user_id = $booking->booker->user->id;
                    $contactDetail->contact =  array_key_exists('whatsapp', $postData) ? $postData['whatsapp'] : null;
                    $contactDetail->type = $whatsapp;
                    $contactDetail->save();
                }                

                $conversation = new Conversation;
                $conversation->contact_detail_id = $contactDetail->id;
                $conversation->from_user_id = $admin->id;
                $conversation->to_user_id = $booking->booker->user->id;
                $conversation->message = array_key_exists('payment_url', $postData) ? $postData['payment_url'] : null;
                $conversation->type = $whatsapp;
                $conversation->save();

                $this->whatsApp->sendMessage('whatsapp:'.$contactDetail->contact, "Hi ".$booking->booker->user->first_name." ".$booking->booker->user->last_name."! Your Payment Link: ".$conversation->message);
            }

            if (gettype($postData['email']) != 'NULL'){

                if($emailValue && $postData['email'] == $emailValue->contact){
                    $contactDetail = ContactDetail::find($emailValue->id);
                } else {
                    $contactDetail = new ContactDetail;
                    $contactDetail->user_id = $booking->booker->user->id;
                    $contactDetail->contact =  array_key_exists('email', $postData) ? $postData['email'] : null;
                    $contactDetail->type = $email;
                    $contactDetail->save();
                }

                $conversation = new Conversation;
                $conversation->contact_detail_id = $contactDetail->id;
                $conversation->from_user_id = $admin->id;
                $conversation->to_user_id = $booking->booker->user->id;
                $conversation->message = array_key_exists('payment_url', $postData) ? $postData['payment_url'] : null;
                $conversation->type = $email;
                $conversation->save();

                $data = ['first_name' => $booking->booker->user->first_name,
                         'last_name' => $booking->booker->user->last_name,
                         'message' => $conversation->message,
                        ];

                \Mail::to($booking->booker->user->email)->send(new SendPaymentInfo($data));
            }

            if (gettype($postData['sms']) != 'NULL'){

                if($smsValue && $postData['sms'] == $smsValue->contact){
                    $contactDetail = ContactDetail::find($smsValue->id);
                } else {
                    $contactDetail = new ContactDetail;
                    $contactDetail->user_id = $booking->booker->user->id;
                    $contactDetail->contact =  array_key_exists('sms', $postData) ? $postData['sms'] : null;
                    $contactDetail->type = $sms;
                    $contactDetail->save();
                }

                $conversation = new Conversation;
                $conversation->contact_detail_id = $contactDetail->id;
                $conversation->from_user_id = $admin->id;
                $conversation->to_user_id = $booking->booker->user->id;
                $conversation->message = array_key_exists('payment_url', $postData) ? $postData['payment_url'] : null;
                $conversation->type = $sms;
                $conversation->save();

                $this->sms->sendSmsMessage($contactDetail->contact, "Hi ".$booking->booker->user->first_name." ".$booking->booker->user->last_name."! Your Payment Link: ".$conversation->message);
            
            }

            $paymentLink = PaymentLink::create([            
                'amount' =>  array_key_exists('amount', $postData) ? $postData['amount'] : null,
                'booking_id' =>  $booking->id,
                'payment_url' =>  array_key_exists('payment_url', $postData) ? $postData['payment_url'] : null,
            ]); 
        });

        return response()->json(['data' => $paymentLink]);
    }


}
