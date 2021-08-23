<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;


class QrCodeController extends Controller
{
    public function webCheckIn(Request $request, $bookingCode)
    {
      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $roomNames = [];
      foreach($bookingDetails->rooms as $room){
          $roomNames[] = $room->name;
      }
      if(count($roomNames) > 0){
          $rooms = implode(',',$roomNames);
          $hotelName = $bookingDetails->rooms[0]->roomType->hotel->property;
      }
      // dd($bookingDetails->bookingRooms[0]->room->name);

      $bookingRooms = [];
      foreach($bookingDetails->bookingRooms as $bookingRoom){
          $bookingRooms[] = $bookingRoom->ttlock_pin;
      }

      // dd($bookingRooms);

      $hotelImage = $bookingDetails->rooms[0]->roomType->hotel->image_url;
      $hotelTerms = $bookingDetails->rooms[0]->roomType->hotel->terms;
      
      $data = [
        'booking'  => $bookingDetails,
        'bookingRooms'  => $bookingRooms,
        'roomName'  => $bookingDetails->bookingRooms[0]->room->name,
        'hotelName'   => $hotelName,
        'guests'  => $bookingDetails->adult_count + $bookingDetails->children_count,
        'hotelImage'   => $hotelImage,
        'hotelTerms'   => $hotelTerms,
        'bookingCode'   => $bookingCode,
      ];
      return view('qrcode')->with('data',$data);
    }
}