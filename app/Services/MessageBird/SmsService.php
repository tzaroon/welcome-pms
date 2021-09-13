<?php


namespace App\Services\MessageBird;

use App\MessageBird\SMS\Service;

class SmsService implements Service
{
	/**
	 * @var Client
	 */
	private $client;

	public function __construct($client = null)
	{		
		if ($client === null) {
            $client = new \MessageBird\Client('egKa6rlwNfAyHis6Qv2NVfWec');
            // $client = new \MessageBird\Client(getenv("MESSAGE_BIRD_API_KEY"));
		}

		$this->client = $client;
    }

	public function sendSmsMessage($to , $body)
	{
        $message = new \MessageBird\Objects\Message;
        // $message->originator = getenv("MESSAGE_BIRD_NUMBER");
        $message->originator = '+34683785295';
        $message->recipients = [ $to ];
        $message->body = $body;
        try {
            $response = $this->client->messages->create($message);
            // print_r($response);
        } catch (\Exception $e) {
            echo sprintf("%s: %s", "sms exception: ".get_class($e), $e->getMessage());
        }        
	}
}

