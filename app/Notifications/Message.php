<?php

namespace App\Notifications;

class Message {

    protected $WHATSAPP;
    protected $SMS;
    protected $EMAIL;

    public function __construct($whatsAppService = NULL, $smsService = NULL, $emailService = NULL) {
        $this->WHATSAPP = $whatsAppService;
        $this->SMS = $smsService;
        $this->EMAIL = $emailService;
    }
}

