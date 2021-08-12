<?php

namespace App\Notifications;

use App\Services\Twilio\WhatsAppService;
use App\Services\Twilio\SmsService;
use App\Notifications\Message;

class WelcomeMessage extends Message {

    public function send($user, $hotelName){ //send booking

        if($this->WHATSAPP != null){
            $this->WHATSAPP->sendMessage('whatsapp:'.$user->phone_number, "Hi ".$user->first_name." ".$user->last_name.", thanks to book at ".$hotelName.". Please, fill in the form with the details of the guests in order to complete the check-in online and receive your code to access the hotel [BOOKING LINK]");
        }

        if($this->SMS != null){
            $this->SMS->sendSmsMessage($user->phone_number, "Hi ".$user->first_name." ".$user->last_name.", thanks to book at ".$hotelName.". Please, fill in the form with the details of the guests in order to complete the check-in online and receive your code to access the hotel [BOOKING LINK]");
        }
        
    }
}