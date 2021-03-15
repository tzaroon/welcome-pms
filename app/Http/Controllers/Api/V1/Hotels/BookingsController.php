<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingHasRoom;
use App\Models\BookingRoomGuest;
use App\Models\Guest;
use App\Models\Room;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use DB;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $date = $request->input('date') ? : date('Y-m-d');

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
        )->whereHas('roomType', function($q) use ($id){
            $q->where('hotel_id', $id);
        })->get();

        $processedData = [];
        $count = 0;
        if($rooms) {
            foreach($rooms as $room) {
                $processedData[$count]['room_id'] = $room->id;
                $processedData[$count]['room_number'] = $room->room_number;
                $processedData[$count]['room_name'] = $room->name;
                if($room->bookings) {
                    $bookingGuest = null;
                    foreach($room->bookings as $booking) {
                        
                        $bookingHasRoom = BookingHasRoom::where('booking_id', $booking->id)->where('room_id', $room->id)
                            ->with('rateType')
                            ->first();

                        $processedData[$count]['bookings'][] = [
                            'id' => $booking->id,
                            'reservation_from' => $booking->reservation_from,
                            'reservation_to' => $booking->reservation_to,
                            'time_start' => $booking->time_start,
                            'status' => $booking->status,
                            'roomCount' => $booking->roomCount,
                            'guest' => $bookingHasRoom->first_guest_name,
                            'rateType' => $bookingHasRoom->rateType ? $bookingHasRoom->rateType->detail->name : null,
                            'numberOfDays' => $booking->numberOfDays,
                            'booker' => $booking->booker ? $booking->booker->user->first_name . ' ' . $booking->booker->user->last_name : null,
                            'rooms' => [
                                '40 6 bedroom : Guest'
                            ],
                            'total_price' => 140,
                            'addons' => [
                                'Bottella da Sharab',
                                'Minibar Cola'
                            ]
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
    public function store(Request $request, $hotel) : JsonResponse
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

        DB::transaction(function() use ($user, $postData) {
            $booking = Booking::create([
                'company_id' => $user->company_id,
                'booker_id' => $postData['booker_id'],
                'reservation_from' => $postData['reservation_from'],
                'reservation_to' => $postData['reservation_to'],
                'time_start' => $postData['time_start'],
                'status' => $postData['status'],
                'source' => $postData['source'],
                'comment' => $postData['comment']
            ]);

            $rooms = array_key_exists('rooms', $postData) ? $postData['rooms'] : [];

            if($rooms)  {
                foreach($rooms as $room) {
                    $bookingHasRoom = new BookingHasRoom();
                    $bookingHasRoom->booking_id = $booking->id;
                    $bookingHasRoom->room_id = $room['room_id'];
                    $bookingHasRoom->rate_type_id = $room['rate_type_id'];
                    $bookingHasRoom->save();
                   
                    $guests = array_key_exists('guests', $room) ? $room['guests'] : [];
                    if($guests) {
                        foreach($guests as $guest) {
                            
                            if(!$bookingHasRoom->first_guest_name) {

                                $bookingHasRoom->first_guest_name = $guest['first_name'] . ' ' . $guest['last_name'];
                                $bookingHasRoom->save();
                            }
                            
                            $guestUser = User::create([
                                'company_id' => $user->company_id,
                                'first_name' => $guest['first_name'],
                                'last_name' => $guest['last_name'],
                                'email' => $guest['email'],
                                'phone_number' => $guest['phone_number'],
                                'street' => $guest['street'],
                                'postal_code' => $guest['postal_code'],
                                'city' => $guest['city'],
                                'country_id' => $guest['country_id'],
                                'gender' => $guest['gender'],
                                'birth_date' => $guest['birth_date']
                            ]);

                            $guest = Guest::create([
                                'user_id' => $guestUser->id,
                                'guest_type' => $guest['guest_type'],
                                'identification_number' => $guest['identification_number'],
                                'identification' => $guest['identification'],
                                'id_issue_date' => $guest['id_issue_date'],
                                'id_expiry_date' => $guest['id_expiry_date'],
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
        });
        return response()->json(['message' => 'Reservation successfully done.']);
    }
}