<?php

namespace App\Imports;

use App\Models\Booker;
use App\Models\Booking;
use App\Models\BookingHasRoom;
use App\Models\DailyPrice;
use App\Models\Guest;
use App\Models\Product;
use App\Models\RateType;
use App\Models\RateTypeDetail;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomTypeDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;

class BookingsImport implements ToCollection
{
    const HOTEL_ID = 3;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $itemCount = 0;
        if($collection) {
            foreach($collection as $item) {

                $itemCount++;
                if($itemCount <= 1 )
                    continue;
                if(!$item[3])
                    continue;

                DB::transaction(function() use($item) {
                    $bookerName = $item[3];
                    $reservationFrom = $item[6];
                    $reservationTo = $item[7];
                    $roomTypeName = $item[9];
                    $roomNumber = $item[10];
                    $guestCount = $item[12];
                    $price = $item[14];
                    $paid = $item[17];
                    $source = $item[22];

                    
                    $name = explode(' ', $bookerName);
                    $user = new User();
                    $user->company_id = 1;
                    $user->last_name = array_pop($name);
                    $user->first_name = implode(' ', $name);
                    $user->email = rand(0,555).'@gmail.com';
                    $user->save();

                    $booker = new Booker();
                    $booker->user_id = $user->id;
                    $booker->company = rand(0, 8888);
                    $booker->save();

                    $firstGuestName = null;
                    if($guestCount) {
                        for($i=0; $i<=$guestCount; $i++) {
                            $guestuser = new User();
                            $guestuser->company_id = 1;
                            $guestuser->first_name = rand(0,8888);
                            $guestuser->last_name = rand(0,8888);
                            $guestuser->email = rand(0,5555) . '@gmail.com';
                            $guestuser->save();

                            $guest = new Guest();
                            $guest->user_id = $guestuser->id;
                            $guest->guest_type = 'corporate';
                            $guest->push();

                            if($i == 0) {
                                $firstGuestName = $guestuser->first_name . ' ' . $guestuser->last_name;
                            }
                        }
                    }

                    $reservationFrom = explode('/', $reservationFrom);
                    $reservationFrom = implode('-', $reservationFrom);
                    
                    $reservationTo = explode('/', $reservationTo);
                    $reservationTo = implode('-', $reservationTo);

                    $booking = new Booking();
                    $booking->company_id = 1;
                    $booking->booker_id = $user->booker->id;
                    $booking->reservation_from = date('Y-m-d', strtotime(trim($reservationFrom)));
                    $booking->reservation_to = date('Y-m-d', strtotime(trim($reservationTo)));
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'not-paid';
                    $booking->total_price = $price;
                   // $booking->source = $source;
                    $booking->save();

                    $roomTypeDetail = RoomTypeDetail::firstOrNew([
                        'company_id' => 1,
                        'language_id' => 'en',
                        'name' => $roomTypeName
                    ]);

                    if($roomTypeDetail->id) {
                        $roomType = RoomType::where('id', $roomTypeDetail->room_type_id)->where('hotel_id', self::HOTEL_ID)->first();
                    } 
                    if(!isset($roomType) || !$roomType)
                    {
                        $roomType = new RoomType();
                    }

                    $roomType->company_id = 1;
                    $roomType->hotel_id = self::HOTEL_ID;
                    $roomType->category_id = 1;
                    $roomType->save();

                    $roomTypeDetail->company_id = 1;
                    $roomTypeDetail->room_type_id = $roomType->id;
                    $roomTypeDetail->language_id = 'en';
                    $roomTypeDetail->name = $roomTypeName;
                    $roomTypeDetail->save();

                    $room = Room::firstOrNew([
                        'company_id' => 1,
                        'room_type_id' => $roomType->id,
                        'room_number' => $roomNumber ? : 458,
                    ]);
                    if(!$room->id) {
                        $room->name = rand(0,15555);
                    }
                    $room->company_id = 1;
                    $room->save();

                    $rateType = RateType::firstOrNew([
                        'room_type_id' => $roomType->id,
                        'company_id' => 1,
                        'price' => $price
                    ]);

                    $rateType->amount_to_add = 0;
                    $rateType->percent_to_add = 0;
                    $rateType->number_of_people = 6;
                    $rateType->advance = 0;
                    $rateType->save();

                    $start = Carbon::parse($booking->reservation_from);
                    $end =  Carbon::parse($booking->reservation_to);

                    $days = $end->diffInDays($start);

                    $date = $start;
                      
                    for($i=0; $i <= $days; $i++) {

                        $product = new Product();
                        $product->company_id = 1;
                        $product->type = Product::TYPE_ROOM;
                        $product->save();

                        $dailyPrice = new DailyPrice();
                        $dailyPrice->company_id = 1;
                        $dailyPrice->rate_type_id = $rateType->id;
                        $dailyPrice->product_id = $product->id;
                        $dailyPrice->date = $date->format('Y-m-d');
                        $dailyPrice->checkin_closed = 0;
                        $dailyPrice->exit_closed = 0;
                        $dailyPrice->minimum_stay = 0;
                        $dailyPrice->maximum_stay = 0;

                        $dailyPrice->save();

                        $product->createPrice($rateType->rate_type_price);
                        $date = $date->addDay();
                    }
                    $rateTypeDetail = RateTypeDetail::firstOrNew([
                        'rate_type_id' => $rateType->id,
                        'company_id' => 1,
                        'name' => $price
                    ]);

                    $rateTypeDetail->language_id = 'en';
                    $rateTypeDetail->save();

                    $bookingRoom = new BookingHasRoom();
                    $bookingRoom->booking_id = $booking->id;
                    $bookingRoom->room_id = $room->id;
                    $bookingRoom->rate_type_id = $roomType->id;
                    $bookingRoom->first_guest_name = $firstGuestName;
                    $bookingRoom->save();
                });
                echo 'Done!';
            }
        }
    }
}
