<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Notifications\WelcomeMessage;
use App\Services\Twilio\WhatsAppService;


class WelcomeText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'welcome:message';
    protected $wt_whatsapp;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WhatsappService $ws_whatsapp)
    {
        parent::__construct();
        $this->wt_whatsapp = $ws_whatsapp;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $welcomeMessage = new WelcomeMessage($this->wt_whatsapp);
        echo ("ss\r\n");
        $booking = Booking::get();
        echo ($booking."\r\n");
        $welcomeMessage->send();
    }
}
