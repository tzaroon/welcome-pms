<?php


namespace App\Twilio\WhatsApp;


interface Service
{
	public function sendMessage($to , $body);
}