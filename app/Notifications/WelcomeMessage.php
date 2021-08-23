<?php

namespace App\Notifications;

use App\User;
use App\Models\Booker;
use App\Models\Booking;
use App\Models\ContactDetail;
use App\Models\Conversation;
use App\Services\Twilio\WhatsAppService;
use App\Services\Twilio\SmsService;
use App\Mail\SendWelcomeEmail; 
use App\Notifications\Message;

class WelcomeMessage extends Message {

    public function send($booking){

        $loggedInUser = auth()->user();

        $user = Booker::leftjoin("users","bookers.user_id","=","users.id")
                        ->where("bookers.id",$booking->booker_id)
                        ->select('users.id','users.first_name','users.last_name','users.email','users.phone_number')
                        ->first();

        $userInfo = User::find($user->id);

        $bookingDetails = Booking::where('bookings.booker_id',$userInfo->booker->id)->first();
        $roomNames = [];
        foreach($bookingDetails->rooms as $room){
            $roomNames[] = $room->name;
        }

        if(count($roomNames) > 0){
            $hotelName = $bookingDetails->rooms[0]->roomType->hotel->property;
        }

        $message = "Hi ".$user->first_name." ".$user->last_name.", thanks to book at ".$hotelName.". Please, fill in the form with the details of the guests in order to complete the check-in online and receive your code to access the hotel - https://staging.revroo.io/web-check-in/".$booking->booking_unique_code;
            // $message = "Hi ".$user->first_name." ".$user->last_name.", thanks to book at ".$hotelName.". Please, fill in the form with the details of the guests in order to complete the check-in online and receive your code to access the hotel - http://127.0.0.1:8000/web-check-in/".$booking->booking_unique_code;

        //* WHATSAPP:-
        if($this->WHATSAPP != null){            
            $this->saveConversation($user, $mode = 'whatsapp', $loggedInUser,$message);           
            $this->WHATSAPP->sendMessage('whatsapp:'.$user->phone_number, $message);
        }

        //* SMS:-
        if($this->SMS != null){
            $this->saveConversation($user, $mode = 'sms', $loggedInUser, $message);            
            $this->SMS->sendSmsMessage($user->phone_number, $message);
        }

        if($this->EMAIL == 1){
            $this->saveConversation($user, $mode = 'email', $loggedInUser, $message);            
            $data = ['first_name' => $user->first_name,
                     'last_name' => $user->last_name,
                     'email' => $user->email,
                     'hotelName' => $hotelName,
                     'message' => $message,
                     'link' => 'http://127.0.0.1:8000/web-check-in/'.$booking->booking_unique_code,
                     'subject' => 'Booking Confirmation'];
            \Mail::to($user->email)->send(new SendWelcomeEmail($data));
        }
        
    }


    public function saveConversation($user, $mode, $loggedInUser, $message){
        
        $contactInfo = ContactDetail::where('type',$mode)
                                    ->where('user_id',$user->id)
                                    ->where('contact',$user->phone_number)
                                    ->first(['id','contact']);
            
        if($contactInfo && $user->phone_number == $contactInfo->contact){
            $contactDetail = ContactDetail::find($contactInfo->id);
        } else {
            $contactDetail = new ContactDetail;
            $contactDetail->user_id = $user->id;
            $contactDetail->contact = $mode == 'email' ? $user->email : $user->phone_number;
            $contactDetail->type = $mode;
            $contactDetail->save();
        }

        $conversation = new Conversation;
        $conversation->contact_detail_id = $contactDetail->id;
        $conversation->from_user_id = $loggedInUser->id;
        $conversation->to_user_id = $user->id;
        $conversation->message = $message;
        $conversation->type = $mode;
        $conversation->save();
    }
}