<?php


namespace App\Services\Twilio;


//use App\Verify\Result;
use App\WhatsApp\Service;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class WhatsAppService implements Service
{
	/**
	 * @var Client
	 */
	private $client;


	/**
	 * @var string
	 */
	private $verification_sid;

	public function __construct($client = null, string $verification_sid = null)
	{
		if ($client === null) {
			$sid = "ACe42d04d14d4a6522f18a3c7ee736ad15"; 
			$token = "e2dfdd189e28d8bf08e6a956b9c1cf5c";
			$client = new Client($sid, $token);
		}
		$this->client = $client;
		$this->verification_sid = $verification_sid ?: config('app.twilio.verification_sid');
	}

	public function sendMessage($to , $body)
	{
		$message = $this->client->messages 
		->create($to, 
				 array( 
					 "from" => "whatsapp:+14155238886",	
					 "body" => $body
				 ) 
		);
	}
}