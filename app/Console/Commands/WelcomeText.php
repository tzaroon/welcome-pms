<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\User;
use App\Models\Booker;
use App\Models\Booking;
use Illuminate\Console\Command;
use App\Notifications\WelcomeMessage;
use App\Services\Twilio\WhatsAppService;
use App\Services\Twilio\SmsService;


class WelcomeText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'welcome:message';
    protected $whatsappService;
    protected $smsService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'On booking, send a welcome message to the customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WhatsappService $whatsappService, SmsService $smsService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $welcomeMessage = new WelcomeMessage($this->whatsappService,$this->smsService);
        $bookings = \DB::table('bookings')->get(["id","booker_id","created_at"]);
        echo Carbon::now();

        foreach ($bookings as $booking){
            $startTime = Carbon::parse($booking->created_at);
            $endTime = Carbon::parse(Carbon::now());

            //* calculate minute difference
            $startTime01 = strtotime($booking->created_at);
            $endTime01 = strtotime(Carbon::now());

            $totalSecondsDiff = abs($startTime01-$endTime01);
            $totalMinutesDiff = round($totalSecondsDiff/60);

            $user = Booker::leftjoin("users","bookers.user_id","=","users.id")
                                     ->where("bookers.id",$booking->booker_id)
                                     ->select('users.id','users.first_name','users.last_name','users.phone_number')
                                     ->first();

            $userInfo = User::find($user->id);
        
            $bookingDetails = Booking::where('bookings.booker_id',$userInfo->booker->id)->first();
            $roomNames = [];
            foreach($bookingDetails->rooms as $room){
                $roomNames[] = $room->name;
            }
            if(count($roomNames) > 0){
                $rooms = implode(',',$roomNames);
                $hotelName = $bookingDetails->rooms[0]->roomType->hotel->property;
            }            

            if($totalMinutesDiff <= 10){
                echo "\r\n---------------------------------\r\n";
                echo "Hotel: ".$hotelName;
                echo "\r\nName:".$user->first_name." ".$user->last_name;
                echo "\r\nPhone Number:".$user->phone_number;      
                echo("\r\ncreated at: ".$startTime."   \r\ncurrent time: ".$endTime);
                echo "\r\nTotal minutes:".$totalMinutesDiff;
                echo "\r\n---------------------------------";
                echo "\r\n\r\n";
                $welcomeMessage->send($user, $hotelName);
            }
        }       
    }
}
