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
            $client = new \MessageBird\Client('egKa6rlwNfAyHis6Qv2NVfWec', null, [\MessageBird\Client::ENABLE_CONVERSATIONSAPI_WHATSAPP_SANDBOX]);
		}

		$this->client = $client;
    }

	public function sendSmsMessage($to , $body)
	{
        $message = new \MessageBird\Objects\Message;
        $message->originator = $to;
        $message->recipients = [ $to ];
        $message->body = $body;
        try {
            $response = $this->client->messages->create($message);
            print_r($response);
        } catch (\Exception $e) {
            echo sprintf("%s: %s", get_class($e), $e->getMessage());
        }        
	}
}

