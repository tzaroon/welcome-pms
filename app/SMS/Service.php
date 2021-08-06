<?php


namespace App\SMS;


interface Service
{
	public function sendSmsMessage($to , $body);
}