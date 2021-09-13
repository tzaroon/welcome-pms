<?php


namespace App\Services\MessageBird;

use App\MessageBird\Whatsapp\Service;

class WhatsappService implements Service
{
	/**
	 * @var Client
	 */
	private $client;

	public function __construct($client = null)
	{		
		if ($client === null) {
            // $client = new \MessageBird\Client('egKa6rlwNfAyHis6Qv2NVfWec', null, [\MessageBird\Client::ENABLE_CONVERSATIONSAPI_WHATSAPP_SANDBOX]);
            $client = new \MessageBird\Client('egKa6rlwNfAyHis6Qv2NVfWec');
		}

		$this->client = $client;
    }

	public function sendMessage($to , $body)
	{   
        // return $to;
        $content = new \MessageBird\Objects\Conversation\Content();
        $content->text = $body;

        $message = new \MessageBird\Objects\Conversation\Message();
        $message->channelId = '215a5ed4ec1c4b2b99abb0386036d8e2';
        $message->content = $content;
        $message->to = $to; // Channel-specific, e.g. MSISDN for SMS.
        $message->type = 'text';

        try {
            $conversation = $this->client->conversations->start($message);
            // print_r ($conversation);
        } catch (\Exception $e) {
            echo sprintf("%s: %s", "whatsapp exception: ".get_class($e), $e->getMessage());
        }
	}
}
