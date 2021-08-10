<?php

namespace App\Notifications;

class Message {

    protected $whatsAppService;
    protected $smsService;
    protected $emailService;

    public function __construct($whatsAppService = NULL, $smsService = NULL, $emailService = NULL) {
        $this->whatsAppService = $whatsAppService;
    }
}

