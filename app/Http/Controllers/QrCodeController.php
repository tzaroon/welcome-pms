<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;


class QrCodeController extends Controller
{
    public function webCheckIn(Request $request, $bookingCode)
    {
      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
      $dailyPrices = [];
      for($i=0; $i<count($dailyPricesList); $i++){
          $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
          $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
      }
      
      $roomNames = [];
      foreach($bookingDetails->rooms as $room){
          $roomNames[] = $room->name;
      }
      if(count($roomNames) > 0){
          $rooms = implode(',',$roomNames);
          $hotelName = $bookingDetails->rooms[0]->roomType->hotel->property;
      }
      // dd($bookingDetails->bookingRooms);

      $bookingRooms = [];
      foreach($bookingDetails->bookingRooms as $bookingRoom){
          $bookingRooms[] = $bookingRoom->ttlock_pin;
      }

      // dd($bookingRooms);

      $hotelImage = $bookingDetails->rooms[0]->roomType->hotel->image_url;
      $hotelTerms = $bookingDetails->rooms[0]->roomType->hotel->terms;
      
      $data = [
        'booker'  => $bookingDetails->booker->user,
        'booking'  => $bookingDetails,
        'checkIn' => date("d M Y", strtotime($bookingDetails->reservation_from)),
        'checkOut' => date("d M Y", strtotime($bookingDetails->reservation_to)),
        'bookingRooms'  => $bookingRooms,
        'roomName'  => $bookingDetails->bookingRooms[0]->room->name,
        'hotelName'   => $hotelName,
        'guests'  => $bookingDetails->adult_count + $bookingDetails->children_count,
        'hotelImage'   => $hotelImage,
        'hotelTerms'   => $hotelTerms,
        'dailyPrices'   => $dailyPrices,
        'bookingCode'   => $bookingCode,
      ];
      // dd($data);
      return view('qrcode')->with('data',$data);
    }


    public function termsAndConditions(Request $request, $bookingCode){
      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $data = [
        'bookingCode' => $bookingCode,
        'hotelTerms' => htmlentities($bookingDetails->rooms[0]->roomType->hotel->terms),

      ];
      return view('terms')->with('data',$data);
    }

}