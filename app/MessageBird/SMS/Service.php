<?php


namespace App\MessageBird\SMS;


interface Service
{
	public function sendSmsMessage($to , $body);
}