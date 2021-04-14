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
			$sid = "AC156c58822542f100be3a6e9d8330c239"; 
			$token = "3b71e0aeb773695b1e9d3e923b39da85";
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