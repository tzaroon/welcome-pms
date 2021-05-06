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
use App\Models\Country;
use App\Models\Language;

class PushNotificationController extends Controller
{
    public function index(Request $request)
    {
        $token = WuBook::auth()->acquire_token(); 
        
        $bookings = WuBook::reservations($token, $_POST['lcode'])->fetch_new_bookings(1);
        
        $hotel = Hotel::where('l_code', $_POST['lcode'])->first();
       
        if($hotel && $bookings && array_key_exists('data', $bookings) && $bookings['data']) {
            foreach($bookings['data'] as $booking) {
                
                $booking = DB::transaction(function() use ($booking, $hotel) {
                    $user = User::firstOrNew(['company_id' => $hotel->company_id, 'email' => $booking['customer_mail']]);
                    $user->first_name = array_key_exists('customer_name', $booking) ? $booking['customer_name'] : 'N/A';
                    $user->last_name = array_key_exists('customer_surname', $booking) ? $booking['customer_surname'] : 'N/A';
                    $user->email = array_key_exists('customer_mail', $booking) ? $booking['customer_mail'] : 'N/A';
                    $user->city = array_key_exists('customer_city', $booking) ? $booking['customer_city'] : 'N/A';
                    $user->phone_number = array_key_exists('customer_phone', $booking) ? $booking['customer_phone'] : 'N/A';
                    $user->street = array_key_exists('customer_address', $booking) ? $booking['customer_address'] : 'N/A';
                    $user->postal_code = array_key_exists('customer_zip', $booking) ? $booking['customer_zip'] : 'N/A';
					if(array_key_exists('customer_country', $booking)){
                    $country = Country::where('code', $booking['customer_country'])->get()->first();
					if($country){
                    $user->country_id = $country->id; 
                    }
                    }
                    if(array_key_exists('customer_language', $booking)){
					$language = Language::where('id', $booking['customer_language'])->get()->first();
                    if($language){
					$user->language_id = $language->id; 
                    }
                    }
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
                    $status = 'N/A';
                    if(array_key_exists('status', $booking)) {
                        $status = $booking['status'];

						switch ($status) {
							case 1:
                                $status = Booking::STATUS_CONFIRMED;
                              break;
                            case 2:
                                $status = Booking::STATUS_WAITING_APPROVAL;
                              break;
                            case 3:
                                $status = Booking::STATUS_REFUSED;
                              break;
                            case 4:
                                $status = Booking::STATUS_ACCEPTED;
                                break;
                            case 5:
                                $status = Booking::STATUS_CANCELLED;
                                break;
                            case 6:
								$status = Booking::STATUS_CANCELLED_WITH_PANELATY;
                          } 
                    }
                    
                    $pmsBooking = new Booking([
                        'company_id' => $hotel->company_id,
                        'booker_id' => $booker->id,
                        'reservation_from' => $arrival,
                        'reservation_to' => $departure,
                        'status' => $status,
                        'wubook_response' => json_encode($booking)
                        //TODO: Need to add status to 'status' column from $booking['status'] https://tdocs.wubook.net/wired/fetch.html#status
                        //TODO: Add column wubook_response put json_encode($booking); in it.
                        //TODO: Add pending info that we can get from API related to this table
                    ]);
                    $pmsBooking->save();
                    
                    $priceIds = [];
                    $i = 0;
                    $totalPrice = 0;
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
					
					                    $date = date('Y-m-d', strtotime(str_replace('/', '-', $roomDay['day'])));
                                        $dailyPrice = DailyPrice::where('company_id', $hotel->company_id)->where('rate_type_id', $rateType->id)->where('date',$date)->first();
                                        $product = $dailyPrice->product;

                                        $productPrice = $product->getPriceByAmount($roomDay['price']);

                                        if(!$productPrice || !$productPrice->id) {
                                            $productPrice = $product->createPrice($roomDay['price']);
                                        }
                                        $totalPrice += $roomDay['price'];
                                        $priceIds[$i]['product_price_id'] = $productPrice->id;
                                        $priceIds[$i]['booking_has_room_id'] = $bookingRoom->id;
                                        $i++;
                                    }
                                }
                            }
                        }
                    }

                    $pmsBooking->discount = $totalPrice-$booking['orig_amount'];
                    $pmsBooking->save();
                    
                    if($priceIds) {
                        $pmsBooking->productPrice()->sync($priceIds);
                    }
                });
            }
        }

        return response()->json(['success' => true]);
    }
}