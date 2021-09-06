<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Booker;
use App\Models\Country;
use App\Models\Language;
use App\Models\State;
use App\Models\Room;
use App\User;
use App\Models\Guest;
use App\Models\BookingRoomGuest;
use Validator;
use File;
use DB;
use Image;


class BookingGuestController extends Controller
{

    public function getState(Request $request){
        $states = DB::table("states")
                    ->where("country_id",$request->country_id)
                    ->pluck("name","id");
        return response()->json($states);
    }

    //*------------------------------------------------------------------------

    public function guests(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      // dd($bookingDetails->guests);
      $guests = $bookingDetails->guests->toArray();
      // dd($bookingDetails->guests);
      $guestUser = [];
      foreach($guests as $guest){
        $guestUser[] = User::where('id',$guest['user_id'])->first();
      }
      
      $data = [
        'booker'  => $bookingDetails->booker->user,
        'bookingDetails'  => $bookingDetails,
        'status'  => $bookingDetails->status,
        'guestUser' => $guestUser,
        'guests' => $bookingDetails->guests->toArray(),
        'totalGuests'  => $bookingDetails->adult_count + $bookingDetails->children_count,
        'bookingCode'   => $bookingCode,
      ];
      // dd($bookingDetails->guests->toArray());
      // dd($data);
      return view('guests')->with('data',$data);
    }

    //*------------------------------------------------------------------------

    public function getBookerDetails(Request $request, $bookingCode, $userId){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      // dd($bookingDetails->booker);
      $userState = State::where('id',$bookingDetails->booker->user->state_id)->first();
      $countryList = DB::table("countries")->pluck("name","id");
      $stateList = State::get();

      
      $data = [
        'user'  => is_null($userId) ? 'no user' : $bookingDetails->booker->user,
        'userLanguage'  => is_null($userId) ? 'no user' : $bookingDetails->booker->user->language,
        'userCountry'  => is_null($userId) ? 'no user' : $bookingDetails->booker->user->country,
        'userState'  => is_null($userId) ? 'no user' : $userState,
        'bookingCode'   => $bookingCode,
        'booking' => $bookingDetails,
        'booker' => $bookingDetails->booker,
        'countryList' => $countryList,
        'stateList' => $stateList,
      ];
      return view('getbooker')->with('data',$data);
    }

    public function getGuestDetails(Request $request, $bookingCode, $guestId){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      // dd($bookingDetails->children_count);
      $guestUser = User::leftjoin('guests','users.id', '=', 'guests.user_id')
                       ->select('users.*')->where('guests.id',$guestId)->first();
      $guestCountry = Country::where('id',$guestUser->country_id)->first();
      $guestState = State::where('id',$guestUser->state_id)->first();
      $guestRoom = BookingRoomGuest::where('guest_id',$guestId)->first();
      $guestRoomName = Room::where('id',$guestRoom->room_id)->first();
      
      $countryList = DB::table("countries")->pluck("name","id");
      $stateList = State::get();
      
      $data = [
        'guestId'  => is_null($guestId) ? 'no guest' : $guestId,
        'guestUser'  => $guestUser,
        'guestCountry'  => $guestCountry,
        'guestState'  => $guestState,
        'guests' => Guest::where('id', $guestId)->first(),
        'bookingCode'   => $bookingCode,
        'countryList' => $countryList,
        'stateList' => $stateList,
        'guestRoom' => $guestRoom,
        'guestRoomName' => $guestRoomName,
        'rooms' => $bookingDetails->rooms
      ];
      return view('getguest')->with('data',$data);
    }

    public function addGuest(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      // dd($bookingDetails->children_count);
      $countryList = DB::table("countries")->pluck("name","id");
      $stateList = State::get();
      // dd($bookingDetails->rooms);
      $data = [
        'bookingCode'   => $bookingCode,
        'countryList' => $countryList,
        'stateList' => $stateList,
        'rooms' => $bookingDetails->rooms
      ];
      return view('addguest')->with('data',$data);
    }

    //*------------------------------------------------------------------------
    //* it is for when we are going to submit the details:-

    public function addBookerDetails(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();

      $validator = Validator::make($request->all(), [
        'image' => 'required',
        'first_name'	=> 'required',
        'last_name'	=> 'required',
        'doc_type'	=> 'required',
        'doc_id'	=> 'required',
        'date_of_issue'	=> 'required',
        'date_of_expiry'	=> 'required',
        'gender'	=> 'required',
        'date_of_birth'	=> 'required',
        'nationality'	=> 'required',
        'language'	=> 'required',
        'adult'	=> 'required',
        'children'	=> 'required',
        'arrival_time'	=> 'required',
        'phone_number'	=> 'required',
        'email'	=> 'required',
        'countryId'	=> 'required',
        'stateId' => 'required',
        'zipcode'	=> 'required',
        'city'	=> 'required',
        'street'	=> 'required',
        'source'	=> 'required',
        'segment'	=> 'required',
        'booker_selfie'	=> 'required',
        'booker_signature' => 'required'
      ], [], [
        'image' => 'Document Image',
        'first_name'	=> 'First Name',
        'last_name'	=> 'Sur Name',
        'doc_type'	=> 'Document Type',
        'doc_id'	=> 'Document Number ID',
        'date_of_issue'	=> 'Date of Issue',
        'date_of_expiry'	=> 'Date of Expiry',
        'gender'	=> 'Gender',
        'date_of_birth'	=> 'Date of Birth',
        'nationality'	=> 'Nationality',
        'language'	=> 'Language',
        'adult'	=> 'Adult',
        'children'	=> 'Children',
        'arrival_time'	=> 'Arrival Time',
        'phone_number'	=> 'Phone Number',
        'email'	=> 'Email',
        'countryId'	=> 'Country',
        'stateId' => 'State',
        'zipcode'	=> 'Postal Code',
        'city'	=> 'City',
        'street'	=> 'Street Name',
        'source'	=> 'Source',
        'segment'	=> 'Segment',
        'booker_selfie'	=> 'Booker Selfie',
        'booker_signature' => 'Booker Signature'
      ]);

      if (!$validator->passes()) {
          return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
      }

      // return $request;
      DB::transaction(function () use ($bookingDetails, $request) {

        $bookingDetails->booker->user->first_name = $request->first_name;
        $bookingDetails->booker->user->last_name = $request->last_name;
        $bookingDetails->booker->user->birth_date = $request->date_of_birth;
        $bookingDetails->booker->user->gender = $request->gender;
        $bookingDetails->booker->user->language_id = Language::where('value',$request->language)->value('id');
        $bookingDetails->booker->user->phone_number = $request->phone_number;
        $bookingDetails->booker->user->postal_code = $request->zipcode;
        $bookingDetails->booker->user->email = $request->email;
        $bookingDetails->booker->user->street = $request->street;
        $bookingDetails->booker->user->building_no = $request->building_number;
        $bookingDetails->booker->user->floor = $request->floor;
        $bookingDetails->booker->user->city = $request->city;
        $bookingDetails->booker->user->country_id = $request->countryId;
        $bookingDetails->booker->user->state_id = $request->stateId;
        $bookingDetails->booker->user->save();


        $bookingDetails->booker->booker_signature = $request->booker_signature;
        $bookingDetails->booker->id_image = $request->image;
        $bookingDetails->booker->booker_selfie = $request->booker_selfie;
        $bookingDetails->booker->identification = $request->doc_type;
        $bookingDetails->booker->identification_number = $request->doc_id;
        $bookingDetails->booker->identification_date_of_issue = $request->date_of_issue;
        $bookingDetails->booker->identification_date_of_expiry = $request->date_of_expiry;
        $bookingDetails->booker->save();

        $bookingDetails->adult_count = (int)$request->adult;
        $bookingDetails->children_count = (int)$request->children;
        $bookingDetails->time_start = $request->arrival_time;
        $bookingDetails->source = $request->source;
        $bookingDetails->segment = $request->segment;
        $bookingDetails->save();
      });


      $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
      $dailyPrices = [];
      for($i=0; $i<count($dailyPricesList); $i++){
          $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
          $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
      }

      $data = [
        'booker'  => $bookingDetails->booker->user,
        'booking'  => $bookingDetails,
        'bookingCode' => $bookingCode,
        'dailyPrices' => $dailyPrices,
      ];
      return view('payment')->with('data',$data);
    }

    public function addGuestDetails(Request $request, $bookingCode){
      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();

      $validator = Validator::make($request->all(), [
        'image' => 'required',
        'first_name'	=> 'required',
        'last_name'	=> 'required',
        'doc_type'	=> 'required',
        'doc_id'	=> 'required',
        'date_of_issue'	=> 'required',
        'date_of_expiry'	=> 'required',
        'gender'	=> 'required',
        'date_of_birth'	=> 'required',
        'phone_number'	=> 'required',
        'email'	=> 'required',
        'countryId'	=> 'required',
        'stateId' => 'required',
        'zipcode'	=> 'required',
        'roomId'	=> 'required',
        'guest_selfie'	=> 'required',
        'guest_signature' => 'required'
      ], [], [
        'image' => 'Document Image',
        'first_name'	=> 'First Name',
        'last_name'	=> 'Sur Name',
        'doc_type'	=> 'Document Type',
        'doc_id'	=> 'Document Number ID',
        'date_of_issue'	=> 'Date of Issue',
        'date_of_expiry'	=> 'Date of Expiry',
        'gender'	=> 'Gender',
        'date_of_birth'	=> 'Date of Birth',
        'phone_number'	=> 'Phone number',
        'email'	=> 'Email',
        'countryId'	=> 'Country',
        'stateId'	=> 'State',
        'zipcode'	=> 'Postal Code',
        'roomId'	=> 'Room',
        'guest_selfie'	=> 'Guest Selfie',
        'guest_signature' => 'Guest Signature'
      ]);

      if (!$validator->passes()) {
          return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
      }

      // return $request;

      if($request->guestId != null){
        DB::transaction(function () use ($bookingDetails, $request, &$guests) {

          $guestId = (int)$request->guestId;          

          $guests = Guest::find($guestId);
          $guests->id_image = $request->image;
          $guests->guest_signature = $request->guest_signature;
          $guests->guest_selfie = $request->guest_selfie;
          $guests->identification = $request->doc_type;
          $guests->identification_number = $request->doc_id;
          $guests->id_issue_date = $request->date_of_issue;
          $guests->id_expiry_date = $request->date_of_expiry;
          $guests->save();

          $user = User::where('id',$guests->user_id)->first();
          $user->first_name = $request->first_name;
          $user->last_name = $request->last_name;
          $user->birth_date = $request->date_of_birth;
          $user->gender = $request->gender;
          $user->phone_number = $request->phone_number;
          $user->email = $request->email;
          $user->postal_code = $request->zipcode;
          $user->country_id = $request->countryId;
          $user->state_id = $request->stateId;
          $user->save();

          $bookingRoomGuests = BookingRoomGuest::where('guest_id',$guestId)->first();
          $bookingRoomGuests->booking_id = $bookingDetails->id;
          $bookingRoomGuests->room_id = $request->roomId;
          $bookingRoomGuests->save();
        });


        $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
        $dailyPrices = [];
        for($i=0; $i<count($dailyPricesList); $i++){
          $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
          $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
        }

        $data = [
          'booker'  => $bookingDetails->booker->user,
          'booking'  => $bookingDetails,
          'bookingCode' => $bookingCode,
          'dailyPrices' => $dailyPrices,
        ];
        return view('payment')->with('data',$data);
      }
      DB::transaction(function () use ($bookingDetails, $request, &$guests) {

        $user = new User;
        $user->company_id = 1;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birth_date = $request->date_of_birth;
        $user->gender = $request->gender;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->postal_code = $request->zipcode;
        $user->country_id = $request->countryId;
        $user->state_id = $request->stateId;
        $user->save();

        $guests = new Guest;
        $guests->user_id = $user->id;
        $guests->id_image = $request->image;
        $guests->guest_signature = $request->guest_signature;
        $guests->guest_selfie = $request->guest_selfie;
        $guests->identification = $request->doc_type;
        $guests->identification_number = $request->doc_id;
        $guests->id_issue_date = $request->date_of_issue;
        $guests->id_expiry_date = $request->date_of_expiry;
        $guests->save();

        $bookingRoomGuests = new BookingRoomGuest;
        $bookingRoomGuests->booking_id = $bookingDetails->id;
        $bookingRoomGuests->room_id = $request->roomId;
        $bookingRoomGuests->guest_id = $guests->id;
        $bookingRoomGuests->save();

      });

      $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
      $dailyPrices = [];
      for($i=0; $i<count($dailyPricesList); $i++){
          $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
          $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
      }

      $data = [
        'booker'  => $bookingDetails->booker->user,
        'booking'  => $bookingDetails,
        'bookingCode' => $bookingCode,
        'dailyPrices' => $dailyPrices,
      ];
      return view('payment')->with('data',$data);
    }

    //*------------------------------------------------------------------------

    public function makePayment(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
      $dailyPrices = [];
      for($i=0; $i<count($dailyPricesList); $i++){
          $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
          $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
      }

      $data = [
        'booker'  => $bookingDetails->booker->user,
        'booking'  => $bookingDetails,
        'bookingCode' => $bookingCode,
        'dailyPrices' => $dailyPrices,
      ];
      return view('payment')->with('data',$data);
    }

}