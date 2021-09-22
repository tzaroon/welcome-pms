<?php

namespace App\Http\Controllers\Api\V1\WebCheckIn;

use App\User;
use App\Models\Room;
use App\Models\State;
use App\Models\Guest;
use App\Models\Booker;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Language;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use App\Models\BookingRoomGuest;
use Illuminate\Support\Facades\Storage;
use App\PaymentClass\paycomet_bankstore;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;



use DB;
use File;
use Image;
use Validator;


class WebCheckInController extends Controller
{
    
  public function documentTypes(Request $request) : JsonResponse{

      return response()->json(Booker::$__document_types_array);
  }
  
  public function genders(Request $request) : JsonResponse{

      return response()->json(User::$__gender_array);
  }

  public function sources(Request $request) : JsonResponse{

      return response()->json(Booking::$__sources);
  }

  public function segments(Request $request) : JsonResponse{

      return response()->json(Booking::$__segments_array);
  }
  
  
    public function webCheckIn(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
      $dailyPrices = [];
      for($i=0; $i<count($dailyPricesList); $i++){
          $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
          $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
      }
      $hotelId = $bookingDetails->rooms[0]->roomType->hotel->id;
      $hotelImagesList = HotelImage::where('hotel_id',$hotelId)
                                   ->select('id as imageId','image')
                                   ->get();


      
      $data = [
        'user'  => $bookingDetails->booker->user->first_name." ".$bookingDetails->booker->user->last_name,
        'checkIn' => date("d M Y", strtotime($bookingDetails->reservation_from)),
        'checkOut' => date("d M Y", strtotime($bookingDetails->reservation_to)),
        'nights' => $bookingDetails->numberOfDays,
        'arrivalTime' => $bookingDetails->time_start,
        'totalGuests'  => $bookingDetails->adult_count + $bookingDetails->children_count,
        'hotelName' => $bookingDetails->rooms[0]->roomType->hotel->property,
        'hotelImages' => $hotelImagesList,
        'hotelMap' => $bookingDetails->rooms[0]->roomType->hotel->map_url,
        'priceDetails' => $bookingDetails->price['calendar_price'],
        'dailyPrices'   => $dailyPrices,
        'bookingCode'   => $bookingCode,
      ];

      return response()->json(['data' => $data]);
    }


    public function termsAndConditions(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $terms = $bookingDetails->rooms[0]->roomType->hotel->terms;
      $terms = json_decode($terms);
      $data = [
        'description' => isset($terms->description) ? $terms->description : null,
        'information' => isset($terms->information) ? $terms->information : null,
        'tnc' => isset($terms->tnc) ? $terms->tnc : null,
        'bookingCode'   => $bookingCode,
      ];
      return response()->json(['data' => $data]);
    }

    //===========================================================================

    public function guestList(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      $guests = $bookingDetails->guests->toArray();
      $guestUsersList = [];
      foreach($guests as $guest){
        $guestUsersList[] = User::leftjoin('guests','users.id', '=', 'guests.user_id')
                           ->select('users.first_name','users.last_name','guests.id as guestId')->where('users.id',$guest['user_id'])->first();
      }


      $totalGuests  = $bookingDetails->adult_count + $bookingDetails->children_count;
      $guestsAdded = count($guestUsersList);
      if($totalGuests > $guestsAdded){
        $diff = $totalGuests - $guestsAdded;
        for($i=0; $i<$diff; $i++){
          $guestUsersList[$i+$guestsAdded]['first_name'] = "null";
          $guestUsersList[$i+$guestsAdded]['last_name'] = "null";
          $guestUsersList[$i+$guestsAdded]['guestId'] = "null";
        }
      }

      
      $data = [
        'bookerId'  => $bookingDetails->booker->user->id,
        'booker'  => $bookingDetails->booker->user->first_name." ".$bookingDetails->booker->user->last_name,
        'bookingStatus'  => $bookingDetails->status,
        'paymentStatus'  => $bookingDetails->payment_status,
        'totalGuests'  => $bookingDetails->adult_count + $bookingDetails->children_count,
        'guestList' => $guestUsersList,
        'bookingCode'   => $bookingCode,
      ];

      return response()->json(['data' => $data]);
    }

    //===========================================================================


    public function getBookerDetails(Request $request, $bookingCode, $userId){

        $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
        $userState = State::where('id',$bookingDetails->booker->user->state_id)->first();
                
        $data = [
          'id' => $bookingDetails->booker->user->id,
          'firstName' => $bookingDetails->booker->user->first_name,
          'lastName' => $bookingDetails->booker->user->last_name,
          'dateOfBirth' => $bookingDetails->booker->user->birth_date,
          'gender' => $bookingDetails->booker->user->gender,
          'language' => Language::where('id',$bookingDetails->booker->user->language_id)->first(['id','value']),
          'phoneNumber' => $bookingDetails->booker->user->phone_number,
          'postalCode' => $bookingDetails->booker->user->postal_code,
          'email' => $bookingDetails->booker->user->email,
          'street' => $bookingDetails->booker->user->street,
          'buildingNumber' => $bookingDetails->booker->user->building_no,
          'floor' => $bookingDetails->booker->user->floor,
          'city' => $bookingDetails->booker->user->city,
          'country' => Country::where('id',$bookingDetails->booker->user->country_id)->first(['id','name']),
          'state' => State::where('id',$bookingDetails->booker->user->state_id)->first(),
          'bookingAdults' => $bookingDetails->adult_count,
          'bookingChildrens' => $bookingDetails->children_count,
          'bookingArrivalTime' => $bookingDetails->time_start,
          'bookingSource' => $bookingDetails->source,
          'bookingSegment' => $bookingDetails->segment,
          'bookerDocType' => $bookingDetails->booker->identification,
          'bookerDocNumber' => $bookingDetails->booker->identification_number,
          'dateOfIssue' => $bookingDetails->booker->identification_date_of_issue,
          'dateOfExpiry' => $bookingDetails->booker->identification_date_of_expiry,
          'bookerSignature' => $bookingDetails->booker->booker_signature,
          'bookerDocImage' => $bookingDetails->booker->id_image,
          'bookerSelfie' => $bookingDetails->booker->booker_selfie,
          'bookingCode'   => $bookingCode,
        ];
        
        return response()->json(['data' => $data]);
    }


    public function getGuestDetails(Request $request, $bookingCode, $guestId){

        $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
        $guestUser = User::leftjoin('guests','users.id', '=', 'guests.user_id')
                         ->select('users.*')->where('guests.id',$guestId)->first();
        $guest = Guest::where('id',$guestId)->first();
        $bookingRoomGuest = BookingRoomGuest::where('guest_id',$guestId)->first(['booking_id','room_id','guest_id']);
       
        $data = [
          'guestId'  => is_null($guestId) ? 'no guest' : $guestId,
          'firstName' => $guestUser->first_name,
          'lastName' => $guestUser->last_name,
          'dateOfBirth' => $guestUser->birth_date,
          'gender' => $guestUser->gender,
          'phoneNumber' => $guestUser->phone_number,
          'postalCode' => $guestUser->postal_code,
          'email' => $guestUser->email,
          'country' => Country::where('id',$guestUser->country_id)->first(['id','name']),
          'state' => State::where('id',$guestUser->state_id)->first(),
          'guestDocType' => $guest->identification,
          'guestDocNumber' => $guest->identification_number,
          'dateOfIssue' => $guest->id_issue_date,
          'dateOfExpiry' => $guest->id_expiry_date,
          'bookingRoomGuest' => $bookingRoomGuest,
          'rooms' => $bookingDetails->rooms[0]->name,
          'guestDocImage' => $guest->id_image,
          'guestSignature' => $guest->guest_signature,
          'guestSelfie' => $guest->guest_selfie,          
          'bookingCode'   => $bookingCode,

        ];

        return response()->json(['data' => $data]);

    }

    //===========================================================================

    //* it is for when we are going to submit the details:-

    public function editBookerDetails(Request $request, $bookingCode){

        $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
        
        $postData = $request->getContent();        
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
          'doc_image' => 'required',
          'first_name'	=> 'required',
          'last_name'	=> 'required',
          'doc_type'	=> 'required',
          'doc_id'	=> 'required',
          'date_of_issue'	=> 'required',
          'date_of_expiry'	=> 'required',
          'gender'	=> 'required',
          'date_of_birth'	=> 'required',
          'nationality'	=> 'required',
          // 'language'	=> 'required',
          // 'adult'	=> 'required',
          // 'children'	=> 'required',
          'arrival_time'	=> 'required',
          'phone_number'	=> 'required',
          // 'email'	=> 'required',
          'countryId'	=> 'required',
          'stateId' => 'required',
          'zipcode'	=> 'required',
          'city'	=> 'required',
          'street'	=> 'required',
          // 'source'	=> 'required',
          // 'segment'	=> 'required',
          'booker_selfie'	=> 'required',
          'booker_signature' => 'required'
        ], [], [
          'doc_image' => 'Document Image',
          'first_name'	=> 'First Name',
          'last_name'	=> 'Sur Name',
          'doc_type'	=> 'Document Type',
          'doc_id'	=> 'Document Number ID',
          'date_of_issue'	=> 'Date of Issue',
          'date_of_expiry'	=> 'Date of Expiry',
          'gender'	=> 'Gender',
          'date_of_birth'	=> 'Date of Birth',
          'nationality'	=> 'Nationality',
          // 'language'	=> 'Language',
          // 'adult'	=> 'Adult',
          // 'children'	=> 'Children',
          'arrival_time'	=> 'Arrival Time',
          'phone_number'	=> 'Phone Number',
          // 'email'	=> 'Email',
          'countryId'	=> 'Country',
          'stateId' => 'State',
          'zipcode'	=> 'Postal Code',
          'city'	=> 'City',
          'street'	=> 'Street Name',
          // 'source'	=> 'Source',
          // 'segment'	=> 'Segment',
          'booker_selfie'	=> 'Booker Selfie',
          'booker_signature' => 'Booker Signature'
        ]);
  
        if (!$validator->passes()) {
            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }
  
        // return $postData;
        DB::transaction(function () use ($bookingDetails, $postData) {
  
          $bookingDetails->booker->user->first_name = $postData['first_name'];
          $bookingDetails->booker->user->last_name = $postData['last_name'];
          $bookingDetails->booker->user->birth_date = $postData['date_of_birth'];
          $bookingDetails->booker->user->gender = $postData['gender'];
          // $bookingDetails->booker->user->language_id = Language::where('value',$postData['language'])->value('id');
          $bookingDetails->booker->user->phone_number = $postData['phone_number'];
          $bookingDetails->booker->user->postal_code = $postData['zipcode'];
          // $bookingDetails->booker->user->email = $postData['email'];
          $bookingDetails->booker->user->street = $postData['street'];
          $bookingDetails->booker->user->building_no = isset($postData['building_number'])? $postData['building_number'] : $bookingDetails->booker->user->building_no;
          $bookingDetails->booker->user->floor = isset($postData['floor'])? $postData['floor'] : $bookingDetails->booker->user->floor;
          $bookingDetails->booker->user->city = $postData['city'];
          $bookingDetails->booker->user->country_id = $postData['countryId'];
          $bookingDetails->booker->user->state_id = $postData['stateId'];
          $bookingDetails->booker->user->save();
  
  
          $bookingDetails->booker->booker_signature = $postData['booker_signature'];
          $bookingDetails->booker->id_image = $postData['doc_image'];
          $bookingDetails->booker->booker_selfie = $postData['booker_selfie'];
          $bookingDetails->booker->identification = $postData['doc_type'];
          $bookingDetails->booker->identification_number = $postData['doc_id'];
          $bookingDetails->booker->identification_date_of_issue = $postData['date_of_issue'];
          $bookingDetails->booker->identification_date_of_expiry = $postData['date_of_expiry'];
          $bookingDetails->booker->save();
  
          // $bookingDetails->adult_count = (int)$postData['adult'];
          // $bookingDetails->children_count = (int)$postData['children'];
          $bookingDetails->time_start = $postData['arrival_time'];
          // $bookingDetails->source = $postData['source'];
          // $bookingDetails->segment = $postData['segment'];
          $bookingDetails->save();
        });
  
  
        $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
        $dailyPrices = [];
        for($i=0; $i<count($dailyPricesList); $i++){
            $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
            $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
        }
  
        return response()->json(["message" => "Booker details has been updated successfully!"]);
    }


    public function editGuestDetails(Request $request, $bookingCode){
      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();

      $postData = $request->getContent();        
      $postData = json_decode($postData, true);
      
      $validator = Validator::make($postData, [
        'doc_image' => 'required',
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
        'doc_image' => 'Document Image',
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

      // return $postData;

      if(isset($postData['guestId'])){
        DB::transaction(function () use ($bookingDetails, $postData, &$guests) {

          $guestId = (int)$postData['guestId'];          

          $guests = Guest::find($guestId);
          $guests->id_image = $postData['doc_image'];
          $guests->guest_signature = $postData['guest_signature'];
          $guests->guest_selfie = $postData['guest_selfie'];
          $guests->identification = $postData['doc_type'];
          $guests->identification_number = $postData['doc_id'];
          $guests->id_issue_date = $postData['date_of_issue'];
          $guests->id_expiry_date = $postData['date_of_expiry'];
          $guests->save();

          $user = User::where('id',$guests->user_id)->first();
          $user->first_name = $postData['first_name'];
          $user->last_name = $postData['last_name'];
          $user->birth_date = $postData['date_of_birth'];
          $user->gender = $postData['gender'];
          $user->phone_number = $postData['phone_number'];
          $user->email = $postData['email'];
          $user->postal_code = $postData['zipcode'];
          $user->country_id = $postData['countryId'];
          $user->state_id = $postData['stateId'];
          $user->save();

          $bookingRoomGuest = BookingRoomGuest::firstOrNew(['room_id' => $postData['roomId'], 'booking_id' => $bookingDetails->id, 'guest_id' => $guestId]);
          $bookingRoomGuest->save();
        });

        return response()->json(["message" => "Guest details has been updated successfully!"]);
      }



      DB::transaction(function () use ($bookingDetails, $postData, &$guests) {

        $user = new User;
        $user->company_id = 1;
        $user->first_name = $postData['first_name'];
        $user->last_name = $postData['last_name'];
        $user->birth_date = $postData['date_of_birth'];
        $user->gender = $postData['gender'];
        $user->phone_number = $postData['phone_number'];
        $user->email = $postData['email'];
        $user->postal_code = $postData['zipcode'];
        $user->country_id = $postData['countryId'];
        $user->state_id = $postData['stateId'];
        $user->save();

        $guests = new Guest;
        $guests->user_id = $user->id;
        $guests->id_image = $postData['doc_image'];
        $guests->guest_signature = $postData['guest_signature'];
        $guests->guest_selfie = $postData['guest_selfie'];
        $guests->identification = $postData['doc_type'];
        $guests->identification_number = $postData['doc_id'];
        $guests->id_issue_date = $postData['date_of_issue'];
        $guests->id_expiry_date = $postData['date_of_expiry'];
        $guests->save();

        $bookingRoomGuests = new BookingRoomGuest;
        $bookingRoomGuests->booking_id = $bookingDetails->id;
        $bookingRoomGuests->room_id = $postData['roomId'];
        $bookingRoomGuests->guest_id = $guests->id;
        $bookingRoomGuests->save();

      });

      return response()->json(["message" => "New Guest details has been added successfully!"]);
      
    }

    //===========================================================================

    public function paymentDetails(Request $request, $bookingCode){

        $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
        $dailyPricesList = $bookingDetails->price['price_breakdown']['daily_prices'];
        $dailyPrices = [];
        for($i=0; $i<count($dailyPricesList); $i++){
            $dailyPrices[$i]['date'] = date("d M Y", strtotime($dailyPricesList[$i]['date']));
            $dailyPrices[$i]['value'] = $dailyPricesList[$i]['value'];
        }
  
        $data = [
          'priceDetails' => $bookingDetails->price['calendar_price'],
          'dailyPrices' => $dailyPrices,
          'bookingCode' => $bookingCode,
        ];
        return response()->json(['data' => $data]);
    }
  
  
    public function makePayment(Request $request, $bookingCode){
        // return $request;
        $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
        // dd($bookingDetails);
  
        $merchantCode	= "h893x7h4";
        $password		= "y56mk9r2hxwjn7zhtdwu";
        $terminal		= "31999";
        $jetid			= NULL; // Optional
  
        $paycomet = new Paycomet_Bankstore($merchantCode, $terminal, $password, $jetid);
        $amount = $bookingDetails->price['calendar_price']['total'];
        $description = "totalAdults: ".$bookingDetails->totalAdults.", totalChildrens: ".$bookingDetails->totalChildrens;
  
        // get payment link
        $response = $paycomet->ExecutePurchaseUrl($bookingDetails->id, $amount, "EUR", "EN", $description, true);
        // return response()->json($response);
  
        if ($response->RESULT == "OK") {
            return response()->json(['paymentLink' => $response->URL_REDIRECT]);
        } else {
            return response()->json(['errors' => ['paymentLink' => ['Payment link is not generated!']]]);
        }
  
    }

    //===========================================================================

    public function countryList(Request $request){
      
      $countries = Country::get();
      return response()->json($countries);
    }


    public function stateList(Request $request, $countryId){
      
      $states = State::where('country_id', $countryId)->get();
      return response()->json($states);
    }


    public function roomList(Request $request, $bookingCode){

      $bookingDetails = Booking::where('booking_unique_code',$bookingCode)->first();
      if (!$bookingDetails) {
          return response()->json(array('errors' => ['payment' => 'Booking not found']), 422);
      }

      $bookingRooms = $bookingDetails->bookingRooms;
      $roomDetails = [];

      foreach ($bookingRooms as $bookingRoom) {
          if ($bookingRoom->room) {
              $roomDetails[] = [
                  'roomId' => $bookingRoom->room->id,
                  'roomName' => $bookingRoom->room->name
              ];
          }
      }

      return response()->json(['data' => $roomDetails]);
    }

}