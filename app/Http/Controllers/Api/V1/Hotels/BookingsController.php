<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingHasRoom;
use App\Models\BookingHasRoomHasGuest;
use App\Models\Guest;
use App\Models\Room;
use App\User;
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
    public function index($id)
    {
        $user = auth()->user();

        $rooms = Room::where('company_id', $user->company_id)->with(
            [
                'roomType'
            ]
        )->whereHas('roomType', function($q) use ($id){
            $q->where('hotel_id', $id);
        })->get();

        return response()->json($rooms);
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
                    $bookingHasRoom->save();
                   
                    $guests = array_key_exists('guests', $room) ? $room['guests'] : [];
                    if($guests) {
                        foreach($guests as $guest) {

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

                            $bookingHasRoomHasGuest = new BookingHasRoomHasGuest();
                            $bookingHasRoomHasGuest->bookings_has_rooms_id = $bookingHasRoom->id;
                            $bookingHasRoomHasGuest->guest_id = $guest->id;
                            $bookingHasRoomHasGuest->save();
                        }
                    }
                }
            }
        });
        return response()->json(['message' => 'Reservation successfully done.']);
    }
}