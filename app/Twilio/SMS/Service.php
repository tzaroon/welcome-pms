<?php


namespace App\Twilio\SMS;


interface Service
{
	public function sendSmsMessage($to , $body);
}