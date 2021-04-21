<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Wubook\Wired\Facades\WuBook;
use App\Models\RoomType;
use App\Models\RateType;
use App\User;
use App\Models\DailyPrice;
use Carbon\Carbon;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class SyncWuBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:wubook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send data to wubook.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = WuBook::auth()->acquire_token();
               
        $companyId = 1;
/*         $plan = WuBook::prices($token)->add_pricing_plan('Daily', 1);        
        $planId = $plan['data'];
        dd($planId); */
       // $planId = 182115;
        
        $hotels = Hotel::where('company_id', $companyId)->whereNotNull('l_code')->get();

        $dfrom =  Carbon::now();    
        
        $fromDateYmd = $dfrom->format('Y-m-d');   

        $dfromdmY = $dfrom->format('d/m/Y');  
        
        $toDate = $dfrom->add('day', 999);                
        $toDate = $toDate->format('Y-m-d');

       

        foreach($hotels as $hotel)
        {
            
            $pushUrl = WuBook::reservations($token, $hotel->l_code)->push_url();
            
            if(!$pushUrl['data']) {
                //TODO: set API_URL in env file and use instead of http://light.tripgofersolutions.com
                WuBook::reservations($token, $hotel->l_code)->push_activation('http://light.tripgofersolutions.com/api/v1/wubook/push-notification', 1);
            }
            
            $rooms = WuBook::rooms($token, $hotel->l_code)->fetch_rooms();

            $prices = DB::transaction(function() use ($rooms, $companyId ,$hotel ,$fromDateYmd , $toDate) {
                
                $prices = [];
                foreach($rooms['data'] as $room)
                {
                    $roomType = RoomType::where('company_id', $companyId)->where('hotel_id', $hotel->id)->whereHas('roomTypeDetails', 
                        function($q) use ($room) {
                            $q->where('name', $room['name']);                        
                        }
                    )->first();           
                    
                    if($roomType) 
                    {
                        $roomType->ref_id = $room['id']; 
                        $roomType->save();              
                    }

                    if($room['subroom'] > 0)
                    {
                        $rateType = RateType::where('company_id',  $companyId)->whereHas( 'details', 
                            function($q) use ($room) {
                                $q->where('name', $room['name']);
                            }
                        )->first();

                        if($rateType)
                        {
                            $rateType->ref_id = $room['id'];
                            $rateType->save();
                            
                            $dailyPrices = DailyPrice::where('company_id', $companyId)
                                ->where('rate_type_id', $rateType->id)
                                ->where('date', '>=', $fromDateYmd)
                                ->where('date', '<=', $toDate)->get();

                            $i = 0;
                            foreach($dailyPrices as $dailyPrice)
                            {
                                $prices[$room['id']][$i] = $dailyPrice->product->price->price;
                                $i++;
                            }
                        }
                    }
                }
                return $prices;
            });
            
            if(!$hotel->plan_id)
            {
                $plan = WuBook::prices($token)->add_pricing_plan('daily' . '_'. $hotel->name, 1);        
                $planId = $plan['data'];                
                $hotel->plan_id = $planId;
                $hotel->save();    
            }

            $result = WuBook::prices($token, $hotel->l_code)->update_plan_prices($hotel->plan_id, $dfromdmY, $prices);
        } 
        
        
    }
}