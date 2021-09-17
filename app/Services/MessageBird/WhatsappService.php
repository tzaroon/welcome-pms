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
            // $client = new \MessageBird\Client(getenv("MESSAGE_BIRD_API_KEY"));
            // dd(getenv("MESSAGE_BIRD_API_KEY"));
            
		}

		$this->client = $client;
    }

	public function sendMessage($to , $body)
	{   
        $messageTo = str_replace("+","",$to);          
        
        $content = new \MessageBird\Objects\Conversation\Content();
        $content->text = $body;

        $message = new \MessageBird\Objects\Conversation\Message();
        $message->channelId = '215a5ed4ec1c4b2b99abb0386036d8e2';
        // $message->channelId = getenv("MESSAGE_BIRD_CHANNEL_ID");
        $message->content = $content;
        $message->to = $messageTo;
        $message->type = 'text';

        try {
            $conversation = $this->client->conversations->start($message);
            // print_r ($conversation);
        } catch (\Exception $e) {
            echo sprintf("%s: %s", "whatsapp exception: ".get_class($e), $e->getMessage());
        }
	}
}
