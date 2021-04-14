<?php


namespace App\WhatsApp;


interface Service
{
	public function sendMessage($to , $body);
}