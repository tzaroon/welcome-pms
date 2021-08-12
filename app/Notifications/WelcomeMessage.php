<?php

namespace App\Notifications;

use App\Services\Twilio\WhatsAppService;
use App\Services\Twilio\SmsService;
use App\Notifications\Message;

class WelcomeMessage extends Message {

    public function send($startTime, $user, $hotelName){

        $time = date("h:i A",strtotime($startTime));
        $day = date("D",strtotime($startTime));
        $date = date("d M Y",strtotime($startTime));

        if($this->WHATSAPP != null){
            echo("********************** [Action: send message to whatsapp number!] **********************\r\n\r\n");
            echo "startTime: ".$startTime."\r\nuser: ".$user->phone_number."\r\n\r\n";
            $this->WHATSAPP->sendMessage('whatsapp:'.$user->phone_number, "Hi ".$user->first_name." ".$user->last_name.", thanks to book at ".$hotelName.". Please, fill in the form with the details of the guests in order to complete the check-in online and receive your code to access the hotel [BOOKING LINK]");
            echo "\r\n Whatsapp message has been sent!\r\n";
        }

        if($this->SMS != null){
            echo("********************** [Action: send message to sms number!] **********************\r\n\r\n");
            echo "startTime: ".$startTime."\r\nuser: ".$user->phone_number."\r\n\r\n";
            $this->SMS->sendSmsMessage($user->phone_number, "Hi ".$user->first_name." ".$user->last_name.", thanks to book at ".$hotelName.". Please, fill in the form with the details of the guests in order to complete the check-in online and receive your code to access the hotel [BOOKING LINK]");
            echo "\r\n sms message has been sent!\r\n";
        }
        
    }
}