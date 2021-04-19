<?php

namespace App\Http\Controllers\Api\V1\WuBook;

use App\Http\Controllers\Controller;
use App\Models\Booker;
use App\Models\Booking;
use App\Models\BookingHasRoom;
use App\Models\DailyPrice;
use App\Models\Hotel;
use App\Models\RateType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Wubook\Wired\Facades\WuBook;
use Validator;
use DB;

class PushNotificationController extends Controller
{
    public function index(Request $request)
    {
        $token = WuBook::auth()->acquire_token();

        $bookings = WuBook::reservations($token, $_POST['lcode'])->fetch_new_bookings(1,0);
        $hotel = Hotel::where('l_code', $_POST['lcode'])->first();

        if($hotel && $bookings && array_key_exists('data', $bookings) && $bookings['data']) {
            foreach($bookings['data'] as $booking) {
                
                $booking = DB::transaction(function() use ($booking, $hotel) {
                    $user = User::firstOrNew(['company_id' => $hotel->company_id, 'email' => $booking['customer_mail']]);
                    $user->first_name = array_key_exists('customer_name', $booking) ? $booking['customer_name'] : 'N/A';
                    $user->last_name = array_key_exists('customer_surname', $booking) ? $booking['customer_surname'] : 'N/A';
                    //TODO: Add other details from API
                    $user->save();

                    $booker = Booker::firstOrNew(['company_id' => $hotel->company_id, 'user_id' => $user->id]);
                    $booker->company_id = $hotel->company_id;
                    $booker->user_id = $user->id;
                    $booker->save();

                    $arrival = 'N/A';
                    if(array_key_exists('date_arrival', $booking)) {
                        $arrival = Carbon::createFromFormat('d/m/Y', $booking['date_arrival']);
                    }
                    
                    $departure = 'N/A';
                    if(array_key_exists('date_departure', $booking)) {
                        $departure = Carbon::createFromFormat('d/m/Y', $booking['date_departure']);
                    }
                    
                    $pmsBooking = new Booking([
                        'company_id' => $hotel->company_id,
                        'booker_id' => $booker->id,
                        'reservation_from' => $arrival,
                        'reservation_to' => $departure,
                        //TODO: Need to add status to 'status' column from $booking['status'] https://tdocs.wubook.net/wired/fetch.html#status
                        //TODO: Add column wubook_response put json_encode($booking); in it.
                        //TODO: Add panding info that we can get from API related to this table
                    ]);
                    $pmsBooking->save();

                    $priceIds = [];
                    $i = 0;
                    if(array_key_exists('booked_rooms', $booking)) {

                        foreach($booking['booked_rooms'] as $bookedRoom){
                            $rateType = RateType::where('ref_id', $bookedRoom['room_id'])->first();
                            if($rateType) {
                                $bookingRoom = new BookingHasRoom([
                                    'booking_id' => $pmsBooking->id,
                                    'rate_type_id' => $rateType->id,
                                    'first_guest_name' => $user->first_name . ' ' . $user->last_name
                                    //TODO: write migration to make room_id nullable
                                ]);

                                $bookingRoom->save();

                                if(array_key_exists('roomdays', $bookedRoom)) {
                                    
                                    foreach($bookedRoom['roomdays'] as $roomDay) {

                                        $dailyPrice = DailyPrice::where('company_id', $hotel->company_id)->where('rate_type_id', $rateType->id)->first();
                                        $product = $dailyPrice->product;
                                        $productPrice = $product->getPriceByAmount($roomDay['price']);

                                        $priceIds[$i]['product_price_id'] = $productPrice->id;
                                        $priceIds[$i]['booking_has_room_id'] = $bookingRoom->id;
                                        $i++;
                                    }
                                }
                            }
                        }
                    }

                    if($priceIds) {
                        $pmsBooking->productPrice()->sync($priceIds);
                    }
                });
            }
        }
//dd($bookings);
        return response()->json(['success' => true]);
    }
}