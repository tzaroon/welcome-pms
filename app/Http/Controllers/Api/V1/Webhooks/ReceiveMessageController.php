<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        Storage::put('sms'.$senderNumber.$messageTime.'.txt', "sender: ".$senderNumber."\nreceiver: ".$receiverNumber."\nmessageTime ".$messageTime."\nbody: ".$body."\nincoming message: ".$incomingMessage."\npayload: ".$payload);

        
    }


    public function receiveWhatsappMessage(Request $request){

        $response = $request;
        Storage::put('whatsapp.txt', $response);
        dd($response);
    }




}