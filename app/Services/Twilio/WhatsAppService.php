<?php


namespace App\Services\Twilio;


//use App\Verify\Result;
use App\Twilio\WhatsApp\Service;
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
			// $sid = getenv('TWILIO_ACCOUNT_SID');
			// $token = getenv("TWILIO_AUTH_TOKEN");
			$sid = 'AC88e715a84fea219a6dda9066f23dd42a';
			$token = "f30822215fa45c97863929a9b2966391";
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