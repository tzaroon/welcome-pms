<?php


namespace App\MessageBird\Whatsapp;


interface Service
{
	public function sendMessage($to , $body);
}