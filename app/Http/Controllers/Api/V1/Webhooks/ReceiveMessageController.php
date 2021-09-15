<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\User;
use App\Models\ContactDetail;
use App\Models\Conversation;

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
        Storage::put('sms'.$senderNumber.$messageTime.'.txt', "sender: ".$senderNumber."\nreceiver: ".$receiverNumber."\nmessageTime: ".$messageTime."\nbody: ".$body."\nincoming message: ".$incomingMessage."\npayload: ".$payload);

        $messageFrom = substr($senderNumber,5); //9450196
        $contactDetail =  ContactDetail::query()
                                    ->where('contact', 'LIKE', "%{$messageFrom}") 
                                    ->Where('type','sms') 
                                    ->first();

        $conversation = new Conversation;
        $conversation->contact_detail_id = $contactDetail->id;
        $conversation->from_user_id = $contactDetail->user_id;
        $conversation->to_user_id = 1;
        $conversation->message = $body;
        $conversation->type = 'sms';
        $conversation->save();
        
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
                                        ->first();
            // return $contactDetail;
            Storage::put('whatsapp'.$messageCreated.'.txt',"\nmessageMSISDN: ".$contactMSISDN."\nmessageCreated: ".$messageCreated."\nmessagePlatform: ".$messagePlatform."\nmessageTo: ".$messageTo."\nmessageFrom: ".$messageFrom."\nmessageStatus: ".$messageStatus."\nmessage: ".$message."\n\n\nresponse: ".$response);
            
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