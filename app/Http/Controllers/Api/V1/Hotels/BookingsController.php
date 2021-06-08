<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingHasRoom;
use App\Models\BookingRoomGuest;
use App\Models\BookingsHasProductPrice;
use App\Models\DailyPrice;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\ProductPrice;
use App\Models\RateType;
use App\Models\Room;
use App\Models\RoomType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use DB;
use App\Models\Booker;
use App\Models\Language;
use ttlock\TTLock;
use App\Models\Lock;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexsss(Request $request)
    {
        $user = auth()->user();
        $hotels = Hotel::where('company_id', $user->company_id)->get();

        $postData = $request->getContent();

        $postData = $postData ? json_decode($postData, true) : [];

        $validator = Validator::make($postData, [
            'start_date' => 'required'
        ], [], [
            'start_date' => 'Start Date'
        ]);

        if (!$validator->passes()) {
            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $processedData = array();
        $count = 0;

        $startDate = Carbon::parse($postData['start_date']);
        if(array_key_exists('weeks', $postData)) {
            $endDate = Carbon::parse($postData['start_date'])->addWeeks($postData['weeks']);
        } else {
            $endDate = Carbon::parse($postData['start_date'])->addMonths($postData['months']);
        }
        
        $days = $endDate->diffInDays($startDate);

        if($hotels) {
            foreach($hotels as $hotel) {
                $processedData[] = [
                    'hotel_id' => $hotel->id,
                    'id' => $hotel->id,
                    'row_type' => 'hotelname',
                    'hotelname' => $hotel->property
                ];

                $calendarStartDate = Carbon::parse($postData['start_date']);
                
                $hotelBookings = $hotel->booking($hotel->id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

                $keyedRoomBookings = [];
                if($hotelBookings) {
                    foreach($hotelBookings as $hotelBooking) {
                        $keyedRoomBookings[$hotelBooking->reservation_from][] = $hotelBooking;
                        
                        $reservationStartDate = Carbon::parse($hotelBooking->reservation_from);
                       
                        $daysCount = $reservationStartDate->diffInDays(Carbon::parse($hotelBooking->reservation_to))-1;

                        if($daysCount > 1) {
                            for($i=0; $i < $daysCount; $i++) {
                                $reservationStartDate->addDay();
                                $keyedRoomBookings[$reservationStartDate->format('Y-m-d')][] = $hotelBooking;
                            }
                        }
                    }
                }

                $hotelSandBox = [];
                for($i=0; $i < $days; $i++)
                {
                    $processedData[$count]['total_availability'][$i] = [
                        'date' => $calendarStartDate->format('Y-m-d'),
                        'day' => date('w', strtotime($calendarStartDate->format('Y-m-d'))),
                        'available' => 6
                    ];

                    $bookingss = array_key_exists($calendarStartDate->format('Y-m-d'), $keyedRoomBookings) ? $keyedRoomBookings[$calendarStartDate->format('Y-m-d')] : [];

                    $hotelSandBox[$i] = [
                        'date' => $calendarStartDate->format('Y-m-d'),
                        'bookings_count' => sizeof($bookingss)
                    ];
                    $calendarStartDate->addDay();
                }
                
                $hotelRoomTypes = [];
                $roomTypeCount = 0;
                $count++;
                if($hotel->roomTypes) {
                    foreach($hotel->roomTypes as $roomType) {
                        $countJ = 0;			
 
                        $totalRooms = Room::where('room_type_id', $roomType->id)
                            ->where('company_id', $user->company_id)
                            ->get()->count();

                        $calendarStartDate = Carbon::parse($postData['start_date']);

                        $availabilityData = [];

                        $objRoom = new Room;
                        for($i=0; $i < $days; $i++)
                        {
                            $bookedCount = 0;
                            $rateDate = $calendarStartDate->format('Y-m-d');
                            $result = $objRoom->avaliability($roomType->id , $rateDate);  

                            if(isset($result) && 0 < sizeof($result)) {

                                $bookedCount = $result[0]->count;
                            }
                        
                            $avaliableRooms = $totalRooms - $bookedCount;

                            $dailyPrice = new DailyPrice();
                            $availabilityData[$countJ] = [
                                'date' => $rateDate,
                                'day' => date('w', strtotime($rateDate)),
                                'available' => $avaliableRooms
                            ];
                            $countJ++;
                            $calendarStartDate = $calendarStartDate->addDay();
                        }

                        $processedData[$count] = [
                            'hotel_id' => $hotel->id,
                            'roomtype_id' => $roomType->id,
                            'row_type' => 'roomtype',
                            'roomtype_name' => $roomType->roomTypeDetail->name,
                            'availability' =>  $availabilityData
                        ];
                        $count++;

                        $hotelRooms = [];
                        $hotelRoomCount = 0;
                        if($roomType->rooms) {
                            foreach($roomType->rooms as $room) {

                                $processedData[$count] = [
                                    'hotel_id' => $hotel->id,
                                    'roomtype_id' => $roomType->id,
                                    'room_id' => $room->id,
                                    'row_type' => 'rooms',
                                    'type' => 'room',
                                    'room_name' => $room->name,
                                    'room_number' => $room->room_number
                                ];
                                $bookings = [];
                                
                                $calendarStartDate = Carbon::parse($postData['start_date']);
                                $roomBookings = $room->booking($room->id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
                                //$roomBookings= [];
                                $keyedRoomBookings = [];
                                $hasPreviousBooking = false;
                                $reservationFromFirstDay = null;
                                $reservationToFirstDay = null;
                                if($roomBookings) {
                                    foreach($roomBookings as $roomBooking) {
                                        if(!$reservationFromFirstDay) {
                                            $reservationFromFirstDay = $roomBooking->reservation_from;
                                            $reservationToFirstDay = $roomBooking->reservation_to;
                                        }
                                        $keyedRoomBookings[$roomBooking->reservation_from] = $roomBooking;
                                    }
                                }

                                if($reservationFromFirstDay < $calendarStartDate->format('Y-m-d')) {
                                    $hasPreviousBooking = true;
                                }
                                for($i=0; $i < $days; $i++) {
                                    
                                    $booking = array_key_exists($calendarStartDate->format('Y-m-d'), $keyedRoomBookings) ? $keyedRoomBookings[$calendarStartDate->format('Y-m-d')] : null;                                    
                                    if($booking) {
                                        $objBooking = new Booking;
                                        $objBooking->fill((array)$booking);
                                        $objBooking->id = $booking ? $booking->id : null;

                                        $objBooking->booker ? $objBooking->booker->user : null;

                                        $bookingHasRoom = new BookingHasRoom;
                                        $bookingHasRoom->fill((array)$booking);
                                        $bookingHasRoom->id = $booking ? $booking->booking_room_id : null;

                                        $associatedRooms = [];

                                        $paymentStatus = [
                                            'not-paid', 'partially-paid', 'payed'
                                        ];
                                        shuffle($paymentStatus);
                                        $arrBooking = [
                                            'id' => $objBooking->id,
                                            'booking_room_id' => $bookingHasRoom ? $bookingHasRoom->id : null,
                                            'reservation_from' => $objBooking->reservation_from,
                                            'reservation_to' => $objBooking->reservation_to,
                                            'time_start' => $objBooking->time_start,
                                            'status' => $objBooking->status,
                                            'roomCount' => $objBooking->roomCount,
                                            'guest' => $bookingHasRoom ? $bookingHasRoom->first_guest_name : null,
                                            'adult_count' => $objBooking->adult_count,
                                            'children_count' => $objBooking->children_count,
                                            'rateType' => $bookingHasRoom && $bookingHasRoom->rateType ? $bookingHasRoom->rateType->detail->name : null,
                                            'numberOfDays' => $objBooking->numberOfDays,
                                            'booker' => $objBooking->booker ? $objBooking->booker->user->first_name . ' ' . $objBooking->booker->user->last_name : null,
                                            'rooms' => $associatedRooms,
                                            'total_price' => $objBooking->price,
                                            'payment_atatus' => $paymentStatus[0],
                                            'addons' => $objBooking->accessories
                                        ];
                                        

                                    }
                                    $previousBooking = false;
                                    if($hasPreviousBooking && $reservationToFirstDay >= $calendarStartDate->format('Y-m-d')) {
                                        $previousBooking = true;
                                    }
                                    $bookings[] = [
                                        'date' => $calendarStartDate->format('Y-m-d'),
                                        'day' => date('w', strtotime($calendarStartDate->format('Y-m-d'))),
                                        'booking' => $booking ? $arrBooking : null,
                                        'previous_booking' => $previousBooking
                                    ];
                                    $calendarStartDate->addDay();
                                }
                                $processedData[$count]['bookings'] = $bookings;
                                $hotelRoomCount++;
                                $count++;
                            }

                            $calendarStartDate = Carbon::parse($postData['start_date']);
                            
                            
                            $keyedRoomBookings = [];
                            if($roomBookings) {
                                foreach($roomBookings as $roomBooking) {
                                    $keyedRoomBookings[$roomBooking->reservation_from][] = $roomBooking;
                                }
                            }

                            $bookings =[];
                            $maxBookingCount = 0;

                            for($i=0; $i < $days; $i++) {
                                    
                                $bookingss = array_key_exists($calendarStartDate->format('Y-m-d'), $keyedRoomBookings) ? $keyedRoomBookings[$calendarStartDate->format('Y-m-d')] : null;
                                $arrBooking = [];
                                if($bookingss) {
                                    foreach($bookingss as $booking)
                                    {    
                                        if($booking) {
                                            $objBooking = new Booking;
                                            $objBooking->fill((array)$booking);
                                            $objBooking->id = $booking ? $booking->id : null;
    
                                            $objBooking->booker ? $objBooking->booker->user : null;
    
                                            $bookingHasRoom = new BookingHasRoom;
                                            $bookingHasRoom->fill((array)$booking);
                                            $bookingHasRoom->id = $booking ? $booking->booking_room_id : null;
    
                                            $associatedRooms = [];
    
                                            $paymentStatus = [
                                                'not-paid', 'partially-paid', 'payed'
                                            ];
                                            shuffle($paymentStatus);
    
                                            $arrBooking[] = [
                                                'id' => $objBooking->id,
                                                'booking_room_id' => $bookingHasRoom ? $bookingHasRoom->id : null,
                                                'reservation_from' => $objBooking->reservation_from,
                                                'reservation_to' => $objBooking->reservation_to,
                                                'time_start' => $objBooking->time_start,
                                                'status' => $objBooking->status,
                                                'roomCount' => $objBooking->roomCount,
                                                'guest' => $bookingHasRoom ? $bookingHasRoom->first_guest_name : null,
                                                'adult_count' => 2,
                                                'children_count' => 3,
                                                'rateType' => $bookingHasRoom && $bookingHasRoom->rateType ? $bookingHasRoom->rateType->detail->name : null,
                                                'numberOfDays' => $objBooking->numberOfDays,
                                                'booker' => $objBooking->booker ? $objBooking->booker->user->first_name . ' ' . $objBooking->booker->user->last_name : null,
                                                'rooms' => $associatedRooms,
                                                'total_price' => $objBooking->price,
                                                'payment_atatus' => $paymentStatus[0],
                                                'addons' => $objBooking->accessories
                                            ];
                                        }
                                    }
                                }
                                if(sizeof($arrBooking) > $maxBookingCount) {
                                    $maxBookingCount = sizeof($arrBooking);
                                }
                                $bookings[] = [
                                    'date' => $calendarStartDate->format('Y-m-d'),
                                    'booking' => $booking ? $arrBooking : null
                                ];
                                $calendarStartDate->addDay();
                            }
                        }

                        $roomTypeCount++;
                    }
                }
                $processedData[$count] = [
                    'hotel_id' => $hotel->id,
                    'row_type' => 'rooms',
                    'type' => 'sand_box',
                    'room_name' => 'Sand Box'
                ];
                $processedData[$count]['bookings'] = $hotelSandBox;
                $count++;
            }
        }
        
        return response()->json($processedData);
    }

    public function index(Request $request, $id)
    {
        $date = $request->input('date') ? : date('Y-m-d');
        $roomType = 0;

        $user = auth()->user();

        $carbonDate = new Carbon($date);

        $rooms = Room::where('company_id', $user->company_id)->with(
            [
                'roomType',
                'bookings' => function($q) use ($date, $carbonDate) {
                    $q->where('reservation_to', '>=', $date)
                        ->where('reservation_to', '<=', $carbonDate->addMonths(1)->format('Y-m-d'));
                }
            ]
        )->whereHas('roomType', function($q) use ($id, $roomType){
            if($id) {
                $q->where('hotel_id', $id);
            }
            if($roomType) {
                $q->where('id', $roomType);
            }
        })->get();

        $processedData = [];
        $count = 0;
        if($rooms) {
            foreach($rooms as $room) {
                $processedData[$count]['room_id'] = $room->id;
                $processedData[$count]['room_number'] = $room->room_number;
                $processedData[$count]['room_name'] = $room->name;
                $processedData[$count]['bookings'] = [];
                $processedData[$count]['rate_type_bookings'] = [];
                if($room->roomType && $room->roomType->rateTypes) {
                   
                    $bookingGuest = null;
                    foreach($room->roomType->rateTypes as $rateType)
                    {    
                        if($rateType->bookings) {
                            foreach($rateType->bookings as $booking) {
                                $bookingHasRoom = BookingHasRoom::where('booking_id', $booking->id)
                                    ->where('rate_type_id', $rateType->id)
                                    ->with('rateType')
                                    ->first();
    
                                $associatedRooms = [];
                                
                                $paymentStatus = [
                                    'not-paid', 'partially-paid', 'payed'
                                ];
                                shuffle($paymentStatus);
                                
                                $processedData[$count]['rate_type_bookings'][] = [
                                    'id' => $booking->id,
                                    'booking_room_id' => $bookingHasRoom ? $bookingHasRoom->id : null,
                                    'reservation_from' => $booking->reservation_from,
                                    'reservation_to' => $booking->reservation_to,
                                    'time_start' => $booking->time_start,
                                    'status' => $booking->status,
                                    'roomCount' => $booking->roomCount,
                                    'guest' => $bookingHasRoom ? $bookingHasRoom->first_guest_name : null,
                                    'rateType' => $bookingHasRoom && $bookingHasRoom->rateType ? $bookingHasRoom->rateType->detail->name : null,
                                    'numberOfDays' => $booking->numberOfDays,
                                    'booker' => $booking->booker ? $booking->booker->user->first_name . ' ' . $booking->booker->user->last_name : null,
                                    'rooms' => $associatedRooms,
                                    'total_price' => $booking->price,
                                    'payment_atatus' => $paymentStatus[0],
                                    'addons' => $booking->accessories
                                ];
                            }
                        }
                    }
                }
                if($room->bookings) {
                    $bookingGuest = null;
                    foreach($room->bookings as $booking) {
                        
                        $bookingHasRoom = BookingHasRoom::where('booking_id', $booking->id)->where('room_id', $room->id)
                            ->with('rateType')
                            ->first();

                        $associatedRooms = [];
                        if($booking->rooms) {
                            foreach($booking->rooms as $otherRoom) {
                                
                                if($room->id == $otherRoom->id)
                                    continue;

                                $associatedRooms[] = $otherRoom->room_number . ' ' . $otherRoom->name;
                            }
                        }

                        $paymentStatus = [
                            'not-paid', 'partially-paid', 'payed'
                        ];
                        shuffle($paymentStatus);
                        
                        $processedData[$count]['bookings'][] = [
                            'id' => $booking->id,
                            'booking_room_id' => $bookingHasRoom ? $bookingHasRoom->id : null,
                            'reservation_from' => $booking->reservation_from,
                            'reservation_to' => $booking->reservation_to,
                            'time_start' => $booking->time_start,
                            'status' => $booking->status,
                            'roomCount' => $booking->roomCount,
                            'guest' => $bookingHasRoom ? $bookingHasRoom->first_guest_name : null,
                            'rateType' => $bookingHasRoom && $bookingHasRoom->rateType ? $bookingHasRoom->rateType->detail->name : null,
                            'numberOfDays' => $booking->numberOfDays,
                            'booker' => $booking->booker ? $booking->booker->user->first_name . ' ' . $booking->booker->user->last_name : null,
                            'rooms' => $associatedRooms,
                            'total_price' => $booking->price,
                            'payment_atatus' => $paymentStatus[0],
                            'addons' => $booking->accessories
                        ];
                    }
                }
                $count++;
            }
        }
        return response()->json($processedData);
        //return response()->json($rooms);
    }

    /**
     * Store a new resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function storeold(Request $request, $hotel) : JsonResponse
    {
        $user = auth()->user();
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'reservation_from' => 'required',
            'reservation_to' => 'required'
        ], [], [
            'reservation_from' => 'Reservation from',
            'reservation_to' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $booking = DB::transaction(function() use ($user, $postData) {
            $booking = Booking::create([
                'company_id' => $user->company_id,
                'booker_id' => array_key_exists('booker_id', $postData) ? $postData['booker_id'] : null,
                'reservation_from' => array_key_exists('reservation_from', $postData) ? $postData['reservation_from'] : null,
                'reservation_to' => array_key_exists('reservation_to', $postData) ? $postData['reservation_to'] : null,
                'time_start' => array_key_exists('time_start', $postData) ? $postData['time_start'] : null,
                'status' => array_key_exists('status', $postData) ? $postData['status'] : Booking::STATUS_CONFIRMED,
                'source' => array_key_exists('source', $postData) ? $postData['source'] : null,
                'comment' => array_key_exists('comment', $postData) ? $postData['comment'] : null,
                'tourist_tax' => array_key_exists('tourist_tax', $postData) ? $postData['tourist_tax'] : null,
                'discount' => array_key_exists('discount', $postData) ? $postData['discount'] : null
            ]);

            $rooms = array_key_exists('rooms', $postData) ? $postData['rooms'] : [];

            $priceIds = [];
            if($rooms)  {
                foreach($rooms as $room) {
                    if(!array_key_exists('room_id', $room) || !$room['room_id'])
                        continue;

                    $bookingHasRoom = BookingHasRoom::firstOrNew(['booking_id' => $booking->id, 'room_id' => $room['room_id']]);

                    $bookingHasRoom->rate_type_id = array_key_exists('rate_type_id', $room) ? $room['rate_type_id'] : null;
                    $bookingHasRoom->save();
                   
                    $start = Carbon::parse($postData['reservation_from']);
                    $end =  Carbon::parse($postData['reservation_to']);

                    $days = $end->diffInDays($start);
                    
                    $date = $start;

                    for($i=0; $i < $days; $i++) {

                        $rateDate = $date->format('Y-m-d');
                        $dailyPrice = new DailyPrice();
                        $dailyPrice = $dailyPrice->where('date', $rateDate)
                            ->where('company_id', $user->company_id)
                            ->where('rate_type_id', $bookingHasRoom->rate_type_id)
                            ->first();

                        $priceIds[$room['room_id'].$i]['product_price_id'] = $dailyPrice->product->price->id;
                        $priceIds[$room['room_id'].$i]['booking_has_room_id'] =  $bookingHasRoom->id;

                        $date = $date->addDay();
                    }
                    
                    $guests = array_key_exists('guests', $room) ? $room['guests'] : [];
                    if($guests) {
                        foreach($guests as $guest) {
                            
                            if(!$bookingHasRoom->first_guest_name) {

                                $bookingHasRoom->first_guest_name = (array_key_exists('first_name', $guest) ? $guest['first_name'] : '') . ' ' . (array_key_exists('last_name', $guest) ? $guest['last_name'] : '');
                                $bookingHasRoom->save();
                            }
                            
                            $guestUser = User::create([
                                'company_id' => $user->company_id,
                                'first_name' => array_key_exists('first_name', $guest) ? $guest['first_name'] : null,
                                'last_name' => array_key_exists('last_name', $guest) ? $guest['last_name'] : null,
                                'email' => array_key_exists('email', $guest) ? $guest['email'] : null,
                                'phone_number' => array_key_exists('phone_number', $guest) ? $guest['phone_number'] : null,
                                'street' => array_key_exists('street', $guest) ? $guest['street'] : null,
                                'postal_code' => array_key_exists('postal_code', $guest) ? $guest['postal_code'] : null,
                                'city' => array_key_exists('city', $guest) ? $guest['city'] : null,
                                'country_id' => array_key_exists('country_id', $guest) ? $guest['country_id'] : null,
                                'gender' => array_key_exists('gender', $guest) ? $guest['gender'] : null,
                                'birth_date' => array_key_exists('birth_date', $guest) ? $guest['birth_date'] : null
                            ]);

                            $guest = Guest::create([
                                'user_id' => $guestUser->id,
                                'guest_type' => array_key_exists('guest_type', $guest) ? $guest['guest_type'] : null,
                                'identification_number' => array_key_exists('identification_number', $guest) ? $guest['identification_number'] : null,
                                'identification' => array_key_exists('identification', $guest) ? $guest['identification'] : null,
                                'id_issue_date' => array_key_exists('id_issue_date', $guest) ? $guest['id_issue_date'] : null,
                                'id_expiry_date' => array_key_exists('id_expiry_date', $guest) ? $guest['id_expiry_date'] : null,
                            ]);

                            $bookingRoomGuest = new BookingRoomGuest();
                            $bookingRoomGuest->room_id = $bookingHasRoom->room_id;
                            $bookingRoomGuest->booking_id = $booking->id;
                            $bookingRoomGuest->guest_id = $guest->id;
                            $bookingRoomGuest->save();
                        }
                    }
                }
            }

            $accessories = array_key_exists('accessories', $postData) ? $postData['accessories'] : [];
            
            if($accessories) {
                foreach($accessories as $accessory) {

                    if(!$accessory || 0 == sizeof($accessory) || !array_key_exists('product_price_id', $accessory))
                        continue;
                        
                    $priceIds[$accessory['product_price_id']]['product_price_id'] = array_key_exists('product_price_id', $accessory) ? $accessory['product_price_id'] : null;
                    $priceIds[$accessory['product_price_id']]['extras_count'] = array_key_exists('count', $accessory) ? $accessory['count'] : null;
                    $priceIds[$accessory['product_price_id']]['extras_pricing'] = array_key_exists('pricing', $accessory) ? $accessory['pricing'] : null;
                    $priceIds[$accessory['product_price_id']]['extras_date'] = array_key_exists('date', $accessory) ? $accessory['date'] : null;
                }
            }
            
            
            $booking->productPrice()->sync($priceIds);

            return $booking;
        });

        return response()->json(['booking' => $booking]);
    }

    public function store(Request $request, $hotel) : JsonResponse
    {
        
        $user = auth()->user();   
        $postData = $request->getContent();
        $postData = json_decode($postData, true);     

        $validator = Validator::make($postData, [
            'arrivel_date' => 'required',
            'departure_date' => 'required'
        ], [], [
            'arrivel_date' => 'Reservation from',
            'departure_date' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }      
        
        $booking = DB::transaction(function() use ($user, $postData) {
            $booking = Booking::create([
                'company_id' => $user->company_id,               
                'reservation_from' => array_key_exists('arrivel_date', $postData) ? $postData['arrivel_date'] : null,
                'reservation_to' => array_key_exists('departure_date', $postData) ? $postData['departure_date'] : null,
                'time_start' => array_key_exists('arrival_time', $postData) ? $postData['arrival_time'] : null,
                'adult_count' => array_key_exists('adults_count', $postData) ? $postData['adults_count'] : null,
                'children_count' => array_key_exists('children_count', $postData) ? $postData['children_count'] : null,
                'status' => array_key_exists('status', $postData) ? $postData['status'] : Booking::STATUS_CONFIRMED,
                'source' => array_key_exists('source', $postData) ? $postData['source'] : null,          
                'total_price' => array_key_exists('total_amount', $postData) ? $postData['total_amount'] : null,                
                'discount' => array_key_exists('total_discount', $postData) ? $postData['total_discount'] : null,
                'segment' => array_key_exists('segment', $postData) ? $postData['segment'] : null,
                'is_buisness_booking' => array_key_exists('buisness_booking', $postData) ? $postData['buisness_booking'] : null,
                'is_expiration_booking' => array_key_exists('expiration_booking', $postData) ? $postData['expiration_booking'] : null,
                'send_email' => array_key_exists('issend_email', $postData) ? $postData['issend_email'] : null,
                'comment' => array_key_exists('advanced', $postData) ? $postData['advanced'] : null
            ]);
           
        
         if(array_key_exists('language', $postData)){
            $language = Language::where('id' , $postData['language'])->first();
        }
            $bUser = User::create([
                'company_id' => $user->company_id,
                'first_name' => array_key_exists('user_name', $postData) ? $postData['user_name'] : null,
                'last_name' => array_key_exists('user_sarname', $postData) ? $postData['user_sarname'] : null,                
                'phone_number' => array_key_exists('phone_number', $postData) ? $postData['phone_number'] : null,
                'street' => array_key_exists('user_address', $postData) ? $postData['user_address'] : null,                
                'city' => array_key_exists('user_city', $postData) ? $postData['user_city'] : null,
                'postal_code' => array_key_exists('zip_code', $postData) ? $postData['zip_code'] : null,
                'country_id' => array_key_exists('user_country', $postData) ? $postData['user_country'] : null,
                'state_id' => array_key_exists('user_province_state', $postData) ? $postData['user_province_state'] : null,    
                'email' => array_key_exists('email', $postData) ? $postData['email'] : null,
                'language_id' => $language ? $language->id : null                              
            ]);

            $booker = Booker::create([
                'company_id' => $user->company_id,
                'user_id' =>  $bUser->id,
                'doc' =>  array_key_exists('document_type', $postData) ? $postData['document_type'] : null,
                'identification_number' =>  array_key_exists('user_ID', $postData) ? $postData['user_ID'] : null,
                'visible_notes' =>  array_key_exists('visible_notes', $postData) ? $postData['visible_notes'] : null,
                'private_notes' =>  array_key_exists('private_notes', $postData) ? $postData['private_notes'] : null,
                'customer_notes' =>  array_key_exists('customer_notes', $postData) ? $postData['customer_notes'] : null,
            ]);             

            $booking->booker_id = $booker->id;
            $booking->save();
            $roomId = array_key_exists('default_room_id', $postData) ? $postData['default_room_id'] : null;
            $rateTypes = array_key_exists('selected_roomtypedata', $postData) ? $postData['selected_roomtypedata'] : []; 

            $start = Carbon::parse($postData['arrivel_date']);
            $end =  Carbon::parse($postData['departure_date']);
            $days = $end->diffInDays($start);
            $date = $start;
            $productprices = [];
            $k =0;
                   
            
            if($roomId){
                $room = Room::where('id', $roomId)->first();
                $rateType =  $room->roomType->rateTypes;

                if($rateType)
                {
                    $bookingHasRoom = BookingHasRoom::create(['booking_id' => $booking->id ,'first_guest_name' => $bUser->first_name,'room_id' => $postData['default_room_id'] ,'rate_type_id' => $rateType[0]->id]);                   
                    $bookingHasRoom->save();                    
                    
                    for($i = 0; $i < $days ; $i++)
                    {
                        $rateDate = $date->format('Y-m-d');
                        $dailyPrice = new DailyPrice();
                        $dailyPrice = $dailyPrice->where('date', $rateDate)
                            ->where('company_id', $user->company_id)
                            ->where('rate_type_id', $bookingHasRoom->rate_type_id)
                            ->first();                                                                                 

                        $productprices[$k]['booking_id'] = $booking->id;
                        $productprices[$k]['product_price_id'] = $dailyPrice->product->price->id;
                        $productprices[$k]['booking_has_room_id'] =  $bookingHasRoom->id;

                        $date = $date->addDay();
                        $k++;
                    }
                   // $booking->productPrice()->sync($productprices); 
                }
            }
                
            
            $date = $start;

            if($rateTypes)
            {
                foreach($rateTypes as $rateType)
                {

                    $units = $rateType['number_of_rooms'];

                    for($j = 0 ; $j < $units; $j++)
                    {

                        $bookingHasRoom = BookingHasRoom::create(['booking_id' => $booking->id , 'rate_type_id' => $rateType['id'] ,'first_guest_name' => $bUser->first_name]);                   
                        $bookingHasRoom->save();
                        //$productprices = [];
                        for($i = 0; $i < $days ; $i++)
                        {
                            $rateDate = $date->format('Y-m-d');
                            $dailyPrice = new DailyPrice();
                            $dailyPrice = $dailyPrice->where('date', $rateDate)
                                ->where('company_id', $user->company_id)
                                ->where('rate_type_id', $bookingHasRoom->rate_type_id)
                                ->first();                              

                            $productprices[$k]['booking_id'] = $booking->id;
                            $productprices[$k]['product_price_id'] = $dailyPrice->product->price->id;
                            $productprices[$k]['booking_has_room_id'] =  $bookingHasRoom->id;

                            $date = $date->addDay();
                            $k++;
                            
                        }
                       // $booking->productPrice()->sync($productprices);
                    }
                }
            } 

            $booking->productPrice()->sync($productprices);
            return $booking;

        });

        return response()->json(['booking' => $booking]);
    }

    public function edit(Request $request, Booking $booking) {

        $responseArray['id'] = $booking->id;
        $responseArray['booker_id'] = $booking->booker->id;
        $responseArray['reservation_from'] = $booking->reservation_from;
        $responseArray['reservation_to'] = $booking->reservation_to;
        $responseArray['status'] = $booking->status;
        $responseArray['time_start'] = $booking->time_start;
        $responseArray['source'] = $booking->source;
        $responseArray['comment'] = $booking->comment;
        $responseArray['tourist_tax'] = $booking->tourist_tax;
        $responseArray['discount'] = $booking->discount;
        $responseArray['received'] = $booking->created_at;
        $responseArray['nights'] = $booking->numberOfDays;
        
        $booking->booker->user;
        
        $responseArray['booker_name'] = $booking->booker->user->first_name . ' ' . $booking->booker->user->last_name;
        $responseArray['booker_email'] = $booking->booker->user->email;
        $responseArray['phone_number'] = $booking->booker->user->phone_number;

        $responseArray['booker'] = $booking->booker;

        $accessories = [];
        if($booking->accessoriesObjects) {
            $i = 0;
            foreach($booking->accessoriesObjects as $accessory) {

                $accessories[$i]['product_price_id'] = $accessory->id;
                $accessories[$i]['price'] = $accessory->price;
                $accessories[$i]['vat'] = $accessory->vat->percentage;
                $accessories[$i]['count'] = $accessory->pivot->extras_count;
                $accessories[$i]['date'] = $accessory->pivot->extras_date;
                $accessories[$i]['pricing'] = $accessory->pivot->extras_pricing;
                $accessories[$i]['accessory'] = $accessory->product->extra;
                $i++;
            }
        }
        $responseArray['accessories'] = $accessories;
        $responseArray['payments'] = $booking->payments;

        $rooms = [];

        $allPrices = [];
        $adultCount = 0;
        $childrenCount = 0;

        if($booking->bookingRooms) {
            $i = 0;
            foreach($booking->bookingRooms as $room) {

                $guests = $room->guests();

                $keyedGuests = [];
                $j = 0;
                if($guests) {
                    foreach($guests as $guest) {
                        $keyedGuests[$j]['id'] = $guest->guest_id;
                        $keyedGuests[$j]['guest_type'] = $guest->guest_type;
                        $keyedGuests[$j]['first_name'] = $guest->first_name;
                        $keyedGuests[$j]['last_name'] = $guest->last_name;
                        $keyedGuests[$j]['email'] = $guest->email;
                        $keyedGuests[$j]['phone_number'] = $guest->phone_number;
                        $keyedGuests[$j]['street'] = $guest->street;
                        $keyedGuests[$j]['postal_code'] = $guest->postal_code;
                        $keyedGuests[$j]['city'] = $guest->city;
                        $keyedGuests[$j]['country_id'] = $guest->country_id;
                        $keyedGuests[$j]['gender'] = $guest->gender;
                        $keyedGuests[$j]['birth_date'] = $guest->birth_date;
                        $keyedGuests[$j]['identification_number'] = $guest->identification_number;
                        $keyedGuests[$j]['identification'] = $guest->identification;
                        $keyedGuests[$j]['id_issue_date'] = $guest->id_issue_date;
                        $keyedGuests[$j]['id_expiry_date'] = $guest->id_expiry_date;
                        $j++;

                        if(Guest::GUEST_TYPE_ADULT == $guest->guest_type) {
                            $adultCount++;
                        } else {
                            $childrenCount++;
                        }
                    }
                }
                $rooms[$i]['room_id'] = $room->room_id;
                $rooms[$i]['booking_room_id'] = $room->id;
                $rooms[$i]['rate_type_id'] = $room->rate_type_id;
                $prices = $room->productPriceByBookingId($booking->id);
                $allPrices[] = $prices;
                $rooms[$i]['prices'] = $prices;
                //$rooms[$i]['rate_types'] = $room->room->roomType->rateTypes;
                $rooms[$i]['guests'] = $keyedGuests;
                $rooms[$i]['room_name'] = $room->room ? $room->room->name : null;
                $rooms[$i]['room_number'] = $room->room ? $room->room->room_number : null;
                $i++;

                if(!array_key_exists('primary_room', $responseArray)) {
                    $responseArray['primary_room'] = $room->room->name . ' ' . $room->room->room_number;
                }
            }
        }

        $responseArray['total_adults'] = $adultCount;
        $responseArray['total_children'] = $childrenCount;
         
        $priceBreakDown = [];

        if($allPrices) {
            foreach($allPrices as $roomPrices) {

                if($roomPrices) {
                    foreach($roomPrices as $roomPrice) {
                        if(array_key_exists($roomPrice['date'], $priceBreakDown)) {
                            $priceBreakDown[$roomPrice['date']] += $roomPrice['price'];
                        } else {
                            $priceBreakDown[$roomPrice['date']] = $roomPrice['price'];
                        }
                    }
                }
                
            }
        }

        $finalBreakDown = [];
        $totalPrice = 0;
        if($priceBreakDown) {
            foreach($priceBreakDown as $date=>$breakDown) {
                $finalBreakDown[] = [
                    'date' => $date,
                    'price' => number_format($breakDown, 2, ',', '.')
                ];
                $totalPrice += $breakDown;
            }
        }

        $responseArray['rooms'] = $rooms;
        $responseArray['price'] = $booking->price['price'];
        $responseArray['total_price'] = $booking->price['total'];
        $responseArray['total_tax'] = $booking->price['tax'] + $booking->price['vat'];
        //$responseArray['price_breakdown_old'] = $booking->price['price_breakdown'];
        $responseArray['price_breakdown'] = ['daily_prices' => $finalBreakDown, 'total_price' => number_format($totalPrice, 2, ',', '.')];
        
        $responseArray['accommodation_price'] = $booking->getAccomudationPrice();
        $responseArray['accessories_price'] = $booking->getAccessoriesPrice();
        $responseArray['city_tax'] = $booking->getCityTax()+$booking->getChildrenCityTax();
        $responseArray['vat'] = $booking->getVat();
        $responseArray['total_booking_price'] = number_format(($responseArray['accommodation_price']+$responseArray['accessories_price']+$responseArray['city_tax']+$responseArray['vat'])-$booking->discount, 2, ',', '.');
        $responseArray['total_paid'] = $booking->totalPaid;
        $responseArray['amount_to_pay'] = number_format(($responseArray['accommodation_price']+$responseArray['accessories_price']+$responseArray['city_tax']+$responseArray['vat']-$responseArray['total_paid'])-$booking->discount, 2, ',', '.');

        $responseArray['price'] = number_format($responseArray['price'], 2, ',', '.');
        $responseArray['total_price'] = number_format($responseArray['total_price'], 2, ',', '.');
        $responseArray['total_tax'] = number_format($responseArray['total_tax'], 2, ',', '.'); 
        $responseArray['accommodation_price'] = number_format($responseArray['accommodation_price'], 2, ',', '.'); 
        $responseArray['accessories_price'] = number_format($responseArray['accessories_price'], 2, ',', '.');
        $responseArray['city_tax'] = number_format($responseArray['city_tax'], 2, ',', '.'); 
        $responseArray['vat'] = number_format($responseArray['vat'], 2, ',', '.');
        $responseArray['total_paid'] =  number_format($responseArray['total_paid'], 2, ',', '.');


        return response()->json($responseArray);
    }

    /**
     * Updates a resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function updateold(Request $request, $hotel, Booking $booking) : JsonResponse
    {
        $user = auth()->user();
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'reservation_from' => 'required',
            'reservation_to' => 'required'
        ], [], [
            'reservation_from' => 'Reservation from',
            'reservation_to' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function() use ($booking, $user, $postData) {
            $booking->fill($postData);
            $booking->save();

            $rooms = array_key_exists('rooms', $postData) ? $postData['rooms'] : [];

            if($rooms)  {
                $priceIds = [];
                foreach($rooms as $room) {
                    
                    if(!array_key_exists('room_id', $room) || !$room['room_id'])
                        continue;

                    $bookingHasRoom = BookingHasRoom::firstOrNew(['booking_id' => $booking->id, 'room_id' => $room['room_id']]);
                    $bookingHasRoom->rate_type_id = array_key_exists('rate_type_id', $room) ? $room['rate_type_id'] : $bookingHasRoom->rate_type_id;
                    $bookingHasRoom->save();
                   
                    $start = Carbon::parse($postData['reservation_from']);
                    $end =  Carbon::parse($postData['reservation_to']);

                    $days = $end->diffInDays($start);
                    
                    $date = $start;

                    for($i=0; $i < $days; $i++) {

                        $rateDate = $date->format('Y-m-d');
                        $dailyPrice = new DailyPrice();
                        $dailyPrice = $dailyPrice->where('date', $rateDate)
                            ->where('company_id', $user->company_id)
                            ->where('rate_type_id', $bookingHasRoom->rate_type_id)
                            ->first();
                            
                        $productPrice = new ProductPrice();
                        if(array_key_exists('price', $room) && $room['price']) {
                            
                            $productPrice = $productPrice->updateOrCreate($dailyPrice->product->price->id, $room['price']);
                        }
                        
                        $priceIds[$room['room_id'].$i]['product_price_id'] = $productPrice->id ? : $dailyPrice->product->price->id;
                        $priceIds[$room['room_id'].$i]['booking_has_room_id'] =  $bookingHasRoom->id;

                        $date = $date->addDay();
                    }
                    
                    $guests = array_key_exists('guests', $room) ? $room['guests'] : [];
                    if($guests) {
                        foreach($guests as $guestData) {
                            
                            if(!$bookingHasRoom->first_guest_name) {

                                $bookingHasRoom->first_guest_name = (array_key_exists('first_name', $guestData) ? $guestData['first_name'] : '') . ' ' . (array_key_exists('last_name', $guestData) ? $guestData['last_name'] : '');
                                $bookingHasRoom->save();
                            }
                            
                            if(array_key_exists('id', $guestData)) {
                                $guest = Guest::find($guestData['id']);
                                $guest->fill($guestData);
                                $guest->user->fill($guestData);
                                $guest->push();
                            } else {
                                $guestUser = User::create([
                                    'company_id' => $user->company_id,
                                    'first_name' => array_key_exists('first_name', $guestData) ? $guestData['first_name'] : null,
                                    'last_name' => array_key_exists('last_name', $guestData) ? $guestData['last_name'] : null,
                                    'email' => array_key_exists('email', $guestData) ? $guestData['email'] : null,
                                    'phone_number' => array_key_exists('phone_number', $guestData) ? $guestData['phone_number'] : null,
                                    'street' => array_key_exists('street', $guestData) ? $guestData['street'] : null,
                                    'postal_code' => array_key_exists('postal_code', $guestData) ? $guestData['postal_code'] : null,
                                    'city' => array_key_exists('city', $guestData) ? $guestData['city'] : null,
                                    'country_id' => array_key_exists('country_id', $guestData) ? $guestData['country_id'] : null,
                                    'gender' => array_key_exists('gender', $guestData) ? $guestData['gender'] : null,
                                    'birth_date' => array_key_exists('birth_date', $guestData) ? $guestData['birth_date'] : null
                                ]);
    
                                $guest = Guest::create([
                                    'user_id' => $guestUser->id,
                                    'guest_type' => array_key_exists('guest_type', $guestData) ? $guestData['guest_type'] : null,
                                    'identification_number' => array_key_exists('identification_number', $guestData) ? $guestData['identification_number'] : null,
                                    'identification' => array_key_exists('identification', $guestData) ? $guestData['identification'] : null,
                                    'id_issue_date' => array_key_exists('id_issue_date', $guestData) ? $guestData['id_issue_date'] : null,
                                    'id_expiry_date' => array_key_exists('id_expiry_date', $guestData) ? $guestData['id_expiry_date'] : null,
                                ]);
                            }
                            
                            $bookingRoomGuest = BookingRoomGuest::firstOrNew(['room_id' => $bookingHasRoom->room_id, 'booking_id' => $booking->id, 'guest_id' => $guest->id]);
                            $bookingRoomGuest->save();
                        }
                    }
                }
            }

            $accessories = array_key_exists('accessories', $postData) ? $postData['accessories'] : [];
            
            if($accessories) {
                foreach($accessories as $accessory) {
                    
                    if(!$accessory || 0 == sizeof($accessory) || !array_key_exists('price', $accessory))
                        continue;
                        
                    $productPrice = new ProductPrice();

                    $vat = 0;
                    if(array_key_exists('vat', $accessory)) {
                        $vat = $accessory['vat'];
                    }
                    
                    $productPrice = $productPrice->updateOrCreateWithVat($accessory['product_price_id'], $accessory['price'], $vat);

                    $priceIds[$accessory['product_price_id']]['product_price_id'] = $productPrice->id;
                    $priceIds[$accessory['product_price_id']]['extras_count'] = array_key_exists('count', $accessory) ? $accessory['count'] : null;
                    $priceIds[$accessory['product_price_id']]['extras_pricing'] = array_key_exists('pricing', $accessory) ? $accessory['pricing'] : null;
                    $priceIds[$accessory['product_price_id']]['extras_date'] = array_key_exists('date', $accessory) ? $accessory['date'] : null;
                }
            }

            $booking->productPrice()->sync($priceIds);

            $payments = array_key_exists('payments', $postData) ? $postData['payments'] : [];
            
            if($payments) {
                foreach($payments as $paymentData) {

                    if(!$paymentData || 0 == sizeof($paymentData) || !array_key_exists('initials', $paymentData))
                        continue;

                    if(array_key_exists('id', $paymentData) && $paymentData['id']) {
                        $payment = Payment::find($paymentData['id']);
                    } else {
                        $payment = new Payment();
                    }
                    $payment->booking_id = $booking->id;
                    $payment->fill($paymentData);
                    $payment->save();
                }
            }
        });

        return response()->json(['booking' => $booking]);
    }

    public function changeRoom(Request $request, BookingHasRoom $bookingRoom) {

        $user = auth()->user();

        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        if(array_key_exists('room_id', $postData) && $postData['room_id']) {
            $room = Room::find($bookingRoom->room_id);
            $newRoom = Room::find($postData['room_id']);
            if($room && $room->room_type_id != $newRoom->room_type_id && (!array_key_exists('force', $postData) || !$postData['force'])) {

                $rateTypes = RateType::where('room_type_id', $newRoom->room_type_id)->with(['detail'])->get();
                
                return response()->json(array('rate_types' => $rateTypes, 'existing_price' => $bookingRoom->price));
            } else {

                if($bookingRoom->updateRoom($newRoom->id)) {
                    $bookingRoom->updatePrices();
                    $bookingRoom->refresh();
                    return response()->json([
                        'message' => 'Room changed successfully.',
                        'booking' => $bookingRoom->booking
                    ]);
                }
                else
                {
                    return response()->json(array('errors' => ['room'=>'Room cannot be changed.']), 422);
                }
            }
        }

        if(array_key_exists('room_type_id', $postData) && $postData['room_type_id']) {

            $rateType = RateType::where('room_type_id', $postData['room_type_id'])->first();

            if(!$rateType) {
                $rateType = RateType::create([
                    'room_type_id' => $postData['room_type_id'],
                    'company_id' => 1,
                    'price' => 0,
                    'number_of_people' => 0,
                    'advance' => 0
                ]);
            }

            $bookingRoom->rate_type_id = $rateType->id;
            $bookingRoom->room_id = null;
            $bookingRoom->save();

            return response()->json([
                'message' => 'Room added to sandbox.'
            ]);
        }

        return response()->json([
            'message' => 'Room changed successfully.',
            'booking' => $bookingRoom->booking
        ]);
    }
    
    public function changeRoomAndRate(Request $request, BookingHasRoom $bookingRoom) {

        $user = auth()->user();
        
        $postData = $request->getContent();
        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'room_id' => 'required',
            'rate_type_id' => 'required'
        ], [], [
            'room_id' => 'Room',
            'rate_type_id' => 'Rate type'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        if($bookingRoom->updateRoom($postData['room_id'])){
            $bookingRoom->rate_type_id = $postData['rate_type_id'];
            $bookingRoom->save();
            
            $bookingRoom->updatePrices();
        }
        else
        {
            return response()->json(array('errors' => ['room'=>'Room cannot be changed.']), 422);
        }
        
        $bookingRoom->refresh();
        return response()->json([
            'message' => 'Room changed successfully.',
            'booking' => $bookingRoom->booking
        ]);
    }

    public function changeCleaningStatus(Request $request, Booking $booking) {

        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'cleaning_status' => 'required'
        ], [], [
            'cleaning_status' => 'Cleaning Status'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $booking->cleaning_status = $postData['cleaning_status'];
        $booking->save();

        return response()->json(array('message' => 'Cleaning status changed successfully.'));
    }

    public function getSandBoxBookings(Request $request) {

        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'date' => 'required',
            'hotel_id' => 'required'
        ], [], [
            'date' => 'Booking date',
            'hotel_id' => 'Hotel'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $sandBoxBookings = Hotel::sandBoxbookings($postData['hotel_id'], $postData['date']);

        $processedBookings = [];

        $lastRoomType = null;
        if($sandBoxBookings) {
            foreach($sandBoxBookings as $sandBoxBooking) {
                $booking = Booking::find($sandBoxBooking->id);
                $roomType = RoomType::find($sandBoxBooking->room_type_id);

                if(array_key_exists('auto_assign', $postData) && 1 == $postData['auto_assign']) {
                    $room = $roomType->getAvailableRoom($booking->reservation_from);
                    $bookingRoom = BookingHasRoom::find($sandBoxBooking->booking_room_id);
                    if($room && $room->id) {
                        $bookingRoom->room_id = $room->id;
                        $bookingRoom->save();
                    }
                }
                else
                {
                    $rooms = $roomType->getAvailableRooms($booking->reservation_from);

                    $processedRooms = [];
                    if($rooms) {
                        foreach($rooms as $objRoom) {
                            $processedRooms[] = [
                                'id' => $objRoom->id,
                                'name' => $objRoom->name . '-' . $objRoom->room_number,
                            ];
                        }
                    }
    
                    $processedBookings[] = [
                        'booking_room_id' => $sandBoxBooking->booking_room_id,
                        'reservation_from' => $sandBoxBooking->reservation_from,
                        'reservation_to' => $sandBoxBooking->reservation_to,
                        'room_type_id' => $sandBoxBooking->room_type_id,
                        'room_type_name' => $lastRoomType != $sandBoxBooking->room_type_name ? $sandBoxBooking->room_type_name : '',
                        'booking_guest' => $sandBoxBooking->first_guest_name,
                        'adult_count' => $booking->getAdultGuestCount(),
                        'children_count' => $booking->getChildrenGuestsCount(),
                        'price' => $booking->price['total'],
                        'room_options' => $processedRooms
                    ]; 
                    $lastRoomType = $sandBoxBooking->room_type_name;
                }
            }
        }
        if(array_key_exists('auto_assign', $postData) && 1 == $postData['auto_assign']) 
        {
            return response()->json(array('message' => 'Rooms assigned successfully.'));
        }
        else
        {
            return response()->json($processedBookings);
        }
    }

    public function sandBoxBookingAssignRoom(Request $request) {

        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        DB::beginTransaction();

        if($postData) {
            foreach($postData as $data) {
                $bookingRoom = BookingHasRoom::find($data['booking_id']);

                $booking =  $bookingRoom->booking;

                if($data['room_id']) {

                    $bookingRoom->room_id = $data['room_id'];

                    $room = Room::find($bookingRoom->room_id);
                    
                    if($room->isAvailable($bookingRoom->room_id, $booking->reservation_from)) {
                        $bookingRoom->save();
                    }
                    else
                    {
                        DB::rollback();
                        return response()->json(['errors' => ['rooms' => $room->name . ' ' . $room->room_number . ' cannot be assigned as already occupied.']], 422);
                    }
                }
            }
        }

        DB::commit();
        
        return response()->json(array('message' => 'Room assigned successfully.'));
    }

    public function loadProductPrices(Request $request, Booking $booking) {

        $booking->productPrice;
        $booking->bookingRooms;
        //return response()->json($booking);
        $processedArray = [];

        $arrRooms = [];
        $totalGuests = 0;
        foreach($booking->bookingRooms as $bookingRoom) {

            $arrRooms[] = [
                'id' => $bookingRoom->room ? $bookingRoom->room->id : null,
                'name' => $bookingRoom->room ? $bookingRoom->room->roomType->roomTypeDetail->name : null,
                'guest_count' => $bookingRoom->guests()->count()
            ];
            $totalGuests += $bookingRoom->guests()->count();
        }
        $cityTax['title'] = ($totalGuests*$booking->numberOfDays) . ' x ' . 'Tourist tax';
        $cityTax['amount'] = $totalGuests*$booking->numberOfDays*$booking->price['tax'];

        $roomNightPrices = [];
        foreach($booking->price['price_breakdown']['daily_prices'] as $dailyPrice) {
            $roomNightPrices[] = $dailyPrice;
        }
        $productPrices = [];
        foreach($booking->productPrice as $productPrice) {
            $productPrices[] = $productPrice->id;
        }

        $processedArray['rooms'] = $arrRooms;
        $processedArray['room_nights'] = Carbon::parse($booking->reservation_from)->format('d/m/Y') . ' to ' . Carbon::parse($booking->reservation_to)->format('d/m/Y');
        $processedArray['room_night_prices'] = $roomNightPrices;
        $processedArray['discount'] = $booking->discount;
        $processedArray['total_price'] = $booking->price['price_breakdown']['total_price'];

        $processedArray['product_price_ids'] = $productPrices;
        $processedArray['city_tax'] = $cityTax;

        return response()->json($processedArray);
    }

    public function changeStatus(Request $request, Booking $booking , $status) {

        if($status){
        $booking->status = $status;
        $booking->save();
        }
        return response()->json(array('message' => 'status changed successfully.'));
    }

    public function generateLock(Request $request) {
        
        $postData = $request->getContent();
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'booking_room_id' => 'required',
            'reservation_from_dt' => 'required',
            'reservation_to_dt' => 'required'
        ], [], [
            'booking_room_id' => 'Door',
            'reservation_from_dt' => 'Reservation from',
            'reservation_to_dt' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }        

        if(array_key_exists('booking_room_id', $postData)){

            $bookingRoom = BookingHasRoom::where('id' , $postData['booking_room_id'] )->first();
            $ttlock = new \ttlock\TTLock('384e4f2af4204245b9b81188c2ff5412','cc7fd08994b9f233241308d6a7cb82c6');
            $token = $ttlock->oauth2->token('+34615967283','h1251664','');
            $ttlock->passcode->setAccessToken($token['access_token']);
            $bookerUser = $bookingRoom->booking->booker->user;
            $hotel = $bookingRoom->rateType->roomType->hotel;
            if($bookingRoom->room->lock_id){
                $ttLock = Lock::find($bookingRoom->room->lock_id);
                $code = rand(1000,9999);
                $ttlock->passcode->add($ttLock->lock_id, $code, strtotime($postData['reservation_from_dt']), strtotime($postData['reservation_to_dt']), 1, time().'000' );
                $bookingRoom->ttlock_pin = $code;
                $bookingRoom->save();

                if(array_key_exists('send_key_via_whatsApp', $postData)){

                    //
                }
                if(array_key_exists('send_key_via_sms', $postData)){

                    //
                }
                if(array_key_exists('send_key_via_email', $postData)){

                    //
                }
                
            } else {

                return response()->json(array('errors' => ['lock' => 'Room does not have lock associated']), 422); 
            }

            //$this->whatsApp->sendMessage('whatsapp:+917006867241', 'Hey ' . $bookerUser->first_name . ' ' . $bookerUser->last_name . '! Tomorrow you have a booking at my place! Remember, to enter the hotel and the room, please use this code '.$code.'. The address is '.$hotel->address.', here is the map ' . $hotel->map_url . ' and this is the picture of the entrance '.$hotel->image_url.'. If you have any problem, please ask me or write me here. Thanks a lot and have a good trip! Marta');

        }

        return response()->json(array('message' => 'Lock generated successfully.'));
    }

    public function cancel(Request $request) {
        
        $postData = $request->getContent();
        $postData = json_decode($postData, true);
       
        
        if(array_key_exists('booking_id', $postData)){

            $booking =  Booking::where ('id', $postData['booking_id'])->first();
            if($booking){

                $booking->status = 'cancelled';
                $booking->reason = array_key_exists('reason', $postData) ? $postData['reason'] : null;
                $booking->save();
            }

            $bookingRooms  = array_key_exists('booking_room', $postData) ? $postData['booking_room'] : null;           

            foreach($bookingRooms as $bookingRoom )
            {
               $bookingRoom = BookingHasRoom::find($bookingRoom);
               $bookingRoom->status = 'canceled';
               $bookingRoom->save();
            }

            return response()->json(array('message' => 'Booking canceled successfully.'));

        }
        
    }
    
    public function loadRooms(Request $request , Booking $booking){

        if (!$booking) {

            return response()->json(array('errors' => ['payment' => 'Booking not found']), 422);
        }

        $bookingHasRooms = $booking->bookingRooms;
        $processedData = [];

        foreach($bookingHasRooms as $bookingRoom){
            if($bookingRoom->room){
                $processedData [] = [

                    'booking_room_id' => $bookingRoom->id,
                    'name' => $bookingRoom->room->name
                ];
        }}

        return response()->json($processedData);
        
    }

    public function downloadReceipt(Request $request , Booking $booking){            
       
       $receipt = public_path().DIRECTORY_SEPARATOR."payment-voucher.pdf";
       return  $receipt;      
    }

    public function getOldPrice(Request $request, $bookingRoom){            
       
        if (!$bookingRoom) {

            return response()->json(array('errors' => ['room' => 'Room not found']), 422);
        }

        $bookingRoom  = BookingHasRoom::find($bookingRoom);        
        return response()->json($bookingRoom->price);    
        
     }




     public function update(Request $request, $hotel, Booking $booking)
     {
        $user = auth()->user();
        
        $postData = $request->getContent();

        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'reservation_from' => 'required',
            'reservation_to' => 'required'
        ], [], [
            'reservation_from' => 'Reservation from',
            'reservation_to' => 'Reservation to'
        ]);

        if (!$validator->passes()) {

            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        DB::transaction(function() use ($booking, $user, $postData){

            $booking->fill($postData);
            $booking->save();       
        });

        $guests = array_key_exists('guests', $postData) ? $postData['guests'] : [];
                    if($guests) {
                        foreach($guests as $guestData) {
                            
                            if(!$bookingHasRoom->first_guest_name) {

                                $bookingHasRoom->first_guest_name = (array_key_exists('first_name', $guestData) ? $guestData['first_name'] : '') . ' ' . (array_key_exists('last_name', $guestData) ? $guestData['last_name'] : '');
                                $bookingHasRoom->save();
                            }
                            
                            if(array_key_exists('id', $guestData)) {
                                $guest = Guest::find($guestData['id']);
                                $guest->fill($guestData);
                                $guest->user->fill($guestData);
                                $guest->push();
                            } else {
                                $guestUser = User::create([
                                    'company_id' => $user->company_id,
                                    'first_name' => array_key_exists('first_name', $guestData) ? $guestData['first_name'] : null,
                                    'last_name' => array_key_exists('last_name', $guestData) ? $guestData['last_name'] : null,
                                    'email' => array_key_exists('email', $guestData) ? $guestData['email'] : null,
                                    'phone_number' => array_key_exists('phone_number', $guestData) ? $guestData['phone_number'] : null,
                                    'street' => array_key_exists('street', $guestData) ? $guestData['street'] : null,
                                    'postal_code' => array_key_exists('postal_code', $guestData) ? $guestData['postal_code'] : null,
                                    'city' => array_key_exists('city', $guestData) ? $guestData['city'] : null,
                                    'country_id' => array_key_exists('country_id', $guestData) ? $guestData['country_id'] : null,
                                    'gender' => array_key_exists('gender', $guestData) ? $guestData['gender'] : null,
                                    'birth_date' => array_key_exists('birth_date', $guestData) ? $guestData['birth_date'] : null
                                ]);
    
                                $guest = Guest::create([
                                    'user_id' => $guestUser->id,
                                    'guest_type' => array_key_exists('guest_type', $guestData) ? $guestData['guest_type'] : null,
                                    'identification_number' => array_key_exists('identification_number', $guestData) ? $guestData['identification_number'] : null,
                                    'identification' => array_key_exists('identification', $guestData) ? $guestData['identification'] : null,
                                    'id_issue_date' => array_key_exists('id_issue_date', $guestData) ? $guestData['id_issue_date'] : null,
                                    'id_expiry_date' => array_key_exists('id_expiry_date', $guestData) ? $guestData['id_expiry_date'] : null,
                                ]);
                            }
                            
                            $bookingRoomGuest = BookingRoomGuest::firstOrNew(['room_id' => $bookingHasRoom->room_id, 'booking_id' => $booking->id, 'guest_id' => $guest->id]);
                            $bookingRoomGuest->save();
                        }
                    }

        

     }

     
}