<?php

namespace App\Notifications;

use App\Services\Twilio\WhatsAppService;
use App\Notifications\Message;

class WelcomeMessage extends Message {

    public function send(){
        if($this->WHATSAPP != null){
            dd($this->EMAIL);
        }
        
    }
}