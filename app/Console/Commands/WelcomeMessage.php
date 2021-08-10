<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\WelcomeMessage;
use App\Services\Twilio\WhatsAppService;


class AddLocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:locks';
    protected $whatsapp;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports locks from ttlock API.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WhatsappService $whatsapp)
    {
        parent::__construct();
        $this->whatsapp = $whatsapp;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $welcomeMessage = new WelcomeMessage(NULL);
        $welcomeMessage->send();
        echo ("ss");
        exit();
        $ttlock = new \ttlock\TTLock('384e4f2af4204245b9b81188c2ff5412','cc7fd08994b9f233241308d6a7cb82c6');

		$token = $ttlock->oauth2->token('+34615967283','h1251664','');

		$ttlock->passcode->setAccessToken($token['access_token']);
		$ttlock->lock->setAccessToken($token['access_token']);

		$locks = $ttlock->lock->list(1,10,time().'000');

        if($locks && $locks['list']) {
            foreach($locks['list'] as $lockData) {

                $lock = new Lock();
                $lock->company_id = 1;
                $lock->lock_alias = $lockData['lockAlias'];
                $lock->lock_mac = $lockData['lockMac'];
                $lock->lock_id = $lockData['lockId'];
                $lock->lock_data = $lockData['lockData'];
                $lock->keyboard_pwd_version = $lockData['keyboardPwdVersion'];
                $lock->lock_name = $lockData['lockName'];
                $lock->save();
            }
        }
    }
}
