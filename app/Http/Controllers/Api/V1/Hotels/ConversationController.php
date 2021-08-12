<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\Country;
use App\Models\Language;
use App\Models\Booker;
use App\Models\Booking;
use App\Models\ContactDetail;
use App\Models\Conversation;

use DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\Twilio\WhatsAppService;
use App\Services\Twilio\SmsService;

class ConversationController extends Controller
{

    protected $whatsApp;
    protected $sms;
    protected $checkDate = "000";
    protected $checkDay = "XXX";

    public function __construct(WhatsAppService $whatsApp,SmsService $sms)
    {
        $this->whatsApp = $whatsApp;
        $this->sms = $sms;

    }


    public function getAllUsersConversation(Request $request){
        $admin = auth()->user();
        
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'mode' => 'required',
        ], [], [
            'mode' => 'mode of conversation',
        ]);

        if (!$validator->passes()) {
            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }

        $conversation = Conversation::where(function ($query) {
                                        $admin = auth()->user();
                                        $query->where('from_user_id', '=', $admin->id)
                                            ->orWhere('to_user_id', '=', $admin->id);
                                    })
                                    ->where('type',$postData['mode'])
                                    ->get();

        $ids = [];
        foreach($conversation as $temp){
            $ids[] = $temp['contact_detail_id'];
        }
        $ids = array_unique($ids);
        
        $chat = [];
        foreach($ids as $id){
            $chat[] = Conversation::leftjoin('contact_details', 'conversations.contact_detail_id','=','contact_details.id')
                                ->leftjoin('users','conversations.from_user_id','=','users.id')
                                ->leftjoin('users as u2','conversations.to_user_id','=','u2.id')
                                ->where('conversations.contact_detail_id',$id)
                                ->where('conversations.type',$postData['mode'])
                                ->get(["contact_details.contact as contactVia","users.first_name as sender","u2.first_name as receiver","conversations.from_user_id as senderId","conversations.to_user_id as receiverId","conversations.message","conversations.type"]);
        }
        
        return response()->json([
            'conversation' => $chat
        ]);
    }


    public function getAllUsersConversationList(Request $request){
        $loggedInUser = auth()->user();
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);

        if($postData["mode"] != "all"){
            $ids = Conversation::where('type',$postData['mode'])->orderBy('id', 'DESC')->get(["id","from_user_id", "to_user_id"]);

            $chatUserIds = [];
            foreach($ids as $temp){
                if($temp['from_user_id'] != $loggedInUser->id){
                    $chatUserIds[] = $temp['from_user_id'];
                }
                if($temp['to_user_id'] != $loggedInUser->id){
                    $chatUserIds[] = $temp['to_user_id'];
                }
            }
            $chatUserIds = array_unique($chatUserIds);
            $chatUserIds = array_values($chatUserIds);
            $chat = [];
            for($i = 0; $i < count($chatUserIds); $i++){
                $chatUserId = $chatUserIds[$i];
                $chat[$i] = Conversation::where(function ($query) use ($chatUserId){
                                            $query->where('conversations.from_user_id', '=', $chatUserId)
                                                ->orWhere('conversations.to_user_id', '=', $chatUserId);
                                        })
                                        ->select("message","type","created_at")
                                        ->where('type',$postData['mode'])
                                        ->orderBy('id','DESC')
                                        ->limit(1)
                                        ->first();

                $chat[$i]['chat_with_user_id'] = $chatUserId;
                $user = User::find($chatUserId);
                $chat[$i]['first_name'] = $user->first_name;
                $chat[$i]['last_name'] = $user->last_name;
                $chat[$i]['unread_messages'] = Conversation::where('from_user_id',$chatUserId)
                                                            ->where('to_user_id',$loggedInUser->id)
                                                            ->where('type',$postData['mode'])
                                                            ->where('is_viewed',0)
                                                            ->count();
            }


            return response()->json([
                'conversation' => $chat
            ]);
        }

        if($postData["mode"] == "all"){
            $ids = Conversation::orderBy('id', 'DESC')->get(["id","from_user_id", "to_user_id"]);

            $chatUserIds = [];
            foreach($ids as $temp){
                if($temp['from_user_id'] != $loggedInUser->id){
                    $chatUserIds[] = $temp['from_user_id'];
                }
                if($temp['to_user_id'] != $loggedInUser->id){
                    $chatUserIds[] = $temp['to_user_id'];
                }
            }
            $chatUserIds = array_unique($chatUserIds);
            $chatUserIds = array_values($chatUserIds);
            $chat = [];
            for($i = 0; $i < count($chatUserIds); $i++){
                    $chatUserId = $chatUserIds[$i];
                    $chat[$i] = Conversation::where(function ($query) use ($chatUserId){
                                                $query->where('conversations.from_user_id', '=', $chatUserId)
                                                    ->orWhere('conversations.to_user_id', '=', $chatUserId);
                                            })
                                            ->select("message","type","created_at")
                                            ->orderBy('id','DESC')
                                            ->limit(1)
                                            ->first();

                $chat[$i]['chat_with_user_id'] = $chatUserId;
                $user = User::find($chatUserId);
                $chat[$i]['first_name'] = $user->first_name;
                $chat[$i]['last_name'] = $user->last_name;
                $chat[$i]['unread_messages'] = Conversation::where('from_user_id',$chatUserId)
                                                            ->where('to_user_id',$loggedInUser->id)
                                                            ->where('is_viewed',0)
                                                            ->count();
            }


            return response()->json([
                'conversation' => $chat
            ]);
        }
    }


    public function getAllConversation(Request $request){
        $loggedInUser = auth()->user();
        
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);

        $validator = Validator::make($postData, [
            'mode' => 'required',
            'user_id' => 'required',
        ], [], [
            'mode' => 'mode of conversation',
            'user_id' => 'user id'
        ]);

        if (!$validator->passes()) {
            return response()->json(array('errors' => $validator->errors()->getMessages()), 422);
        }
        
        // $page = $postData['page'];
        // $limit = $postData['limit'];

        // $offset  = $page * $limit;
        // $offset  = ($page * $limit) - 10;

        Conversation::where(function ($query) use ($postData, $loggedInUser) {
                        $userId = $postData['user_id'];                    
                        $query->where('conversations.from_user_id', '=', $userId)
                            ->where('conversations.to_user_id', '=', $loggedInUser->id);
                    })->where('conversations.type',$postData['mode'])->update(['is_viewed' => 1]);

        $conversation = Conversation::leftjoin('contact_details', 'conversations.contact_detail_id','=','contact_details.id')
                                    ->leftjoin('users','conversations.from_user_id','=','users.id')
                                    ->leftjoin('users as u2','conversations.to_user_id','=','u2.id')
                                    ->where(function ($query) use ($postData) {
                                        $userId = $postData['user_id'];
                                        $query->where('conversations.from_user_id', '=', $userId)
                                            ->orWhere('conversations.to_user_id', '=', $userId);
                                    })
                                    ->where('conversations.type',$postData['mode'])
                                    // ->offset($offset)->limit($limit)
                                    ->get(["contact_details.contact as contactVia","users.first_name as sender","u2.first_name as receiver","conversations.from_user_id as senderId","conversations.to_user_id as receiverId","conversations.message","conversations.type","conversations.created_at"]);

        
        foreach($conversation as $conver){
            $conver->time = date("h:i A",strtotime($conver->created_at));
            $day = date("D",strtotime($conver->created_at));
            $date = date("d M Y",strtotime($conver->created_at));
            if($this->checkDate != $date && $this->checkDay != $day){
                $conver->date = $date;
                $this->checkDate = $conver->date;
                $conver->day = $day;
                $this->checkDay = $conver->day;
            }
        }

        $userInfo = User::leftjoin('languages', 'users.language_id','=','languages.id')
                        ->leftjoin('countries','users.country_id','=','countries.id')
                        ->select('users.first_name', 'users.last_name', 'users.email', 'users.phone_number', 'languages.value as language', 'countries.name as country')
                        ->where('users.id',$postData['user_id'])
                        ->first();

        $user = User::find($postData['user_id']);

        if(!$user || !$user->booker){
            return response()->json([
                'conversation' => $conversation,
                'user_info' => $userInfo,
                'booking_info' => null,
                'payment_info' => null
            ]);
        }
      
        $bookingDetails = Booking::where('bookings.booker_id',$user->booker->id)->first(); 
        // return $bookingDetails->rooms;
        
        if($bookingDetails){
            $roomNames = [];
            foreach($bookingDetails->rooms as $room){
                $roomNames[] = $room->name;
            }
            if(count($roomNames) > 0){
                $rooms = implode(',',$roomNames);
                $hotelName = $bookingDetails->rooms[0]->roomType->hotel->property;
            } else {
                $rooms = "";
                $hotelName = "";
            }
            
            $booking = new \stdClass();
            $booking->booking = $bookingDetails->id;        
            $booking->hotel = $hotelName;
            $booking->hotel_id = $bookingDetails->rooms[0]->roomType->hotel->id;
            $booking->room = $rooms;
            $booking->room_id = $room->id;
            $booking->adult_count = $bookingDetails->adult_count;
            $booking->children_count = $bookingDetails->children_count;
            $booking->nights = $bookingDetails->numberOfDays;
            $booking->arrival = $bookingDetails->reservation_from;        
            $booking->departure = $bookingDetails->reservation_to;
            
            $paymentDetails = $bookingDetails->price['calendar_price'];
            $payment = new \stdClass();        
            $payment->total = (float)$paymentDetails['total'];
            $payment->paid = (float)$paymentDetails['total']-(float)$paymentDetails['pending'];        
            $payment->pending = (float)$paymentDetails['pending'];
        }
        return response()->json([
            'conversation' => $conversation,
            'user_info' => $userInfo,
            'booking_info' => isset($booking) ? $booking : null,
            'payment_info' => isset($payment) ? $payment : null
        ]);
    }    
    
    
    public function sendMessage(Request $request){
        $loggedInUser = auth()->user();

        $postData = $request->getContent();  
        $postData = json_decode($postData, true);
         

        $userPhoneNumber = User::where('id',$postData['user_id'])->value('phone_number');

        $contactInfo = ContactDetail::where('type',$postData['mode'])
                                ->where('user_id',$postData['user_id'])
                                ->where('contact',$userPhoneNumber)
                                ->first(['id','contact']);
        if($postData['mode'] == 'whatsapp'){
            if($contactInfo && $userPhoneNumber == $contactInfo->contact){
                $contactDetail = ContactDetail::find($contactInfo->id);
            } else {
                $contactDetail = new ContactDetail;
                $contactDetail->user_id = $postData['user_id'];
                $contactDetail->contact = $userPhoneNumber;
                $contactDetail->type = $postData['mode'];
                $contactDetail->save();
            }

            $conversation = new Conversation;
            $conversation->contact_detail_id = $contactDetail->id;
            $conversation->from_user_id = $loggedInUser->id;
            $conversation->to_user_id = $postData['user_id'];
            $conversation->message = $postData['message'];
            $conversation->type = $postData['mode'];
            $conversation->save();

            // $this->whatsApp->sendMessage('whatsapp:'.$userPhoneNumber, $postData['message']);

            return response()->json([
                'message' => 'Whatsapp message sent',
            ]);
        }

        if($postData['mode'] == 'sms'){
            if($contactInfo && $userPhoneNumber == $contactInfo->contact){
                $contactDetail = ContactDetail::find($contactInfo->id);
            } else {
                $contactDetail = new ContactDetail;
                $contactDetail->user_id = $postData['user_id'];
                $contactDetail->contact = $userPhoneNumber;
                $contactDetail->type = $postData['mode'];
                $contactDetail->save();
            }

            $conversation = new Conversation;
            $conversation->contact_detail_id = $contactDetail->id;
            $conversation->from_user_id = $loggedInUser->id;
            $conversation->to_user_id = $postData['user_id'];
            $conversation->message = $postData['message'];
            $conversation->type = $postData['mode'];
            $conversation->save();

            // $this->sms->sendSmsMessage($userPhoneNumber, $postData['message']);

            return response()->json([
                'message' => 'SMS message sent',
            ]);
        }
        
        
    }


}
