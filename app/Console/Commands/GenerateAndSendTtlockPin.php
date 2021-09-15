<?php

namespace App\Console\Commands;

use App\Models\BookingHasRoom;
use App\Models\Lock;
use App\Services\Twilio\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use ttlock\TTLock;

class GenerateAndSendTtlockPin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ttlock:generate-pin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and sends ttlock Pin';

    protected $whatsApp;
    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now();
        $date->addDay();

        $bookingRooms = BookingHasRoom::whereNotNull('room_id')
            ->whereNull('ttlock_pin')
            ->with(['booking', 'room'])
            ->whereHas('booking', function($query) use ($date) {
                $query->where('reservation_from', '=', $date->format('Y-m-d'));
            })
            ->whereHas('room', function($query) {
                $query->whereNotNull('lock_id');
            })
            ->get();

        $ttlock = new \ttlock\TTLock('384e4f2af4204245b9b81188c2ff5412','cc7fd08994b9f233241308d6a7cb82c6');

        $token = $ttlock->oauth2->token('+34615967283','h1251664','');

        $ttlock->passcode->setAccessToken($token['access_token']);
        
        if($bookingRooms) {
            foreach($bookingRooms as $bookingRoom) {

                $bookerUser = $bookingRoom->booking->booker->user;
               
                $hotel = $bookingRoom->rateType->roomType->hotel;

                $ttLock = Lock::find($bookingRoom->room->lock_id);

                $code = rand(10000000,99999999);

                $ttlock->passcode->add($ttLock->lock_id, $code, strtotime($bookingRoom->booking->reservation_from), strtotime($bookingRoom->booking->reservation_to), 1, time().'000' );
                $bookingRoom->ttlock_pin = $code;
                $bookingRoom->save();

                $this->whatsApp->sendMessage('917006867241', 'Hey ' . $bookerUser->first_name . ' ' . $bookerUser->last_name . '! Tomorrow you have a booking at my place! Remember, to enter the hotel and the room, please use this code '.$code.'. The address is '.$hotel->address.', here is the map ' . $hotel->map_url . ' and this is the picture of the entrance '.$hotel->image_url.'. If you have any problem, please ask me or write me here. Thanks a lot and have a good trip! Marta');
            }
        }
        return 0;
    }
}
