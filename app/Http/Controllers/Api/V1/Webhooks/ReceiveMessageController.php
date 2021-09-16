<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\User;
use App\Models\ContactDetail;
use App\Models\Conversation;
use App\Models\Payment;
use App\Models\Booking;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Str;

class ReceiveMessageController extends Controller
{

    public function receiveSmsMessage(Request $request){

        $body = $request->body;
        $incomingMessage = $request->incomingMessage;
        $payload = $request->payload;
        $senderNumber = $request->originator;
        $receiverNumber = $request->recipient;
        $messageTime = $request->currentTime;
        // Storage::put('sms'.$senderNumber.$messageTime.'.txt', "sender: ".$senderNumber."\nreceiver: ".$receiverNumber."\nmessageTime: ".$messageTime."\nbody: ".$body."\nincoming message: ".$incomingMessage."\npayload: ".$payload);

        $messageFrom = substr($senderNumber,5); //9450196
        $contactDetail =  ContactDetail::query()
                                    ->where('contact', 'LIKE', "%{$messageFrom}") 
                                    ->Where('type','sms')
                                    ->orderBy('id', 'DESC')
                                    ->first();

        if(!$contactDetail){
            $user = User::create([
                'company_id' => 1,
                'first_name' => $senderNumber,
                'phone_number' => $senderNumber
            ]);
            
            $contact = ContactDetail::create([
                'user_id' => $user->id,
                'contact' => $senderNumber,
                'type' => 'sms'
            ]);
            
            Conversation::create([
                'contact_detail_id' => $contact->id,
                'from_user_id' => $contact->user_id,
                'to_user_id' => 1,
                'message' => $body,
                'type' => 'sms',
            ]);
        }
        else {
            $conversation = new Conversation;
            $conversation->contact_detail_id = $contactDetail->id;
            $conversation->from_user_id = $contactDetail->user_id;
            $conversation->to_user_id = 1;
            $conversation->message = $body;
            $conversation->type = 'sms';
            $conversation->save();
        }
        
    }


    public function receiveWhatsappMessage(Request $request){

        $response = $request;
        if($request->message['status'] == 'received'){
            $contactId = $request->contact['id'];
            $contactMSISDN = $request->contact['msisdn'];
            $messageCreated = $request->message['createdDatetime'];
            $conversationStatus = $request->conversation['status'];
            $messagePlatform = $request->message['platform'];
            $messageTo = $request->message['to'];
            $messageFrom = $request->message['from'];
            $messageStatus = $request->message['status'];
            $message = $request->message['content']['text'];

            $contactDetail =  ContactDetail::query()
                                        ->where(function ($query) use($messageFrom, $contactMSISDN) {
                                            $query->where('contact',$messageFrom)
                                                ->orWhere('contact',$contactMSISDN);}) 
                                        ->Where('type','whatsapp')
                                        ->orderBy('id', 'DESC')
                                        ->first();
            // return $contactDetail;
            // Storage::put('whatsapp'.$messageCreated.'.txt',"\nmessageMSISDN: ".$contactMSISDN."\nmessageCreated: ".$messageCreated."\nmessagePlatform: ".$messagePlatform."\nmessageTo: ".$messageTo."\nmessageFrom: ".$messageFrom."\nmessageStatus: ".$messageStatus."\nmessage: ".$message."\n\n\nresponse: ".$response);
            
            if(!$contactDetail){
                $user = User::create([
                    'company_id' => 1,
                    'first_name' => $messageFrom,
                    'phone_number' => $messageFrom
                ]);
                
                $contact = ContactDetail::create([
                    'user_id' => $user->id,
                    'contact' => $messageFrom,
                    'type' => 'whatsapp'
                ]);
                
                Conversation::create([
                    'contact_detail_id' => $contact->id,
                    'from_user_id' => $contact->user_id,
                    'to_user_id' => 1,
                    'message' => $message,
                    'type' => 'whatsapp',
                ]);
            }
            else {
                $conversation = new Conversation;
                $conversation->contact_detail_id = $contactDetail->id;
                $conversation->from_user_id = $contactDetail->user_id;
                $conversation->to_user_id = 1;
                $conversation->message = $message;
                $conversation->type = 'whatsapp';
                $conversation->save();
            }
            
            
        }        
    }


    public function receivePaymentSuccessful(Request $request){
        Storage::put('successpayment.txt',$request);
        return "OK: ".$request;       
    }

    public function receivePaymentUnsuccessful(Request $request){
        Storage::put('errorpayment.txt',$request);
        return "KO: ".$request;    
    }


    public function receivePaymentResponse(Request $request){
        Storage::put('#'.$request->Order.$request->Response.'-'.$request->Signature.'.txt',"Request: ".$request.
                                           "\n\nOrder: ".$request->Order.
                                           "\nResponse: ".$request->Response.
                                           "\nSignature: ".$request->Signature.
                                           "\nAmountEur: ".$request->AmountEur.
                                           "\nAccountCode: ".$request->AccountCode.
                                           "\nTerminalCode: ".$request->TpvID.
                                           "\nConcept: ".$request->Concept.
                                           "\nNotificationHash: ".$request->NotificationHash);
                                           
        if($request->Response == "OK"){
            $payment = new Payment;
            $payment->booking_id = $request->Order;
            $payment->payment_method = 'online';
            $payment->payment_date = \Carbon\Carbon::now()->format('Y-m-d');
            $payment->amount = $request->AmountEur;
            $payment->operation_code = $request->Signature;
            $payment->save();

            $booking = Booking::where('id',$request->Order)->first();
            
        }
    }

}
