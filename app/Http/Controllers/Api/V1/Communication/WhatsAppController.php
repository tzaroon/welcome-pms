<?php

namespace App\Http\Controllers\Api\V1\Communication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Twilio\WhatsAppService;

class WhatsAppController extends Controller
{
	/**
	* Verification service
	*
	* @var Service
	*/
	protected $whatsApp;

	public function __construct(WhatsAppService $whatsApp)
	{
		$this->whatsApp = $whatsApp;
	}

	public function sendMessage()
	{
		$this->whatsApp->sendMessage('whatsapp:+917889955696', 'Your appointment is coming up on July 21 at 3PM');
	}
}
