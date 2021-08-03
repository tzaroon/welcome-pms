<?php

namespace App\Http\Controllers\Api\V1\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\ContactDetail;
use App\Models\Conversation;
use App\User;

use Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\Twilio\WhatsAppService;

class ConversationController extends Controller
{

    protected $whatsApp;

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }


    public function getAllUsersConversation(Request $request){
        $admin = auth()->user();
        
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);
        // return $postData['mode'];

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
        // return $conversation;

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
        // return $loggedInUser;
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);
        // return $postData;

        if($postData){
            $ids = Conversation::where('type',$postData['mode'])->orderBy('id', 'DESC')->get(["id","from_user_id", "to_user_id"]);
        // return $ids;

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
            // return $chatUserIds;
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
                $chat[$i]['first_name'] = User::where('id',$chatUserId)->value('first_name');
                $chat[$i]['last_name'] = User::where('id',$chatUserId)->value('last_name');
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


        $ids = Conversation::orderBy('id', 'DESC')->get(["id","from_user_id", "to_user_id"]);
        // return $ids;

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
        // return $chatUserIds;
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
            $chat[$i]['first_name'] = User::where('id',$chatUserId)->value('first_name');
            $chat[$i]['last_name'] = User::where('id',$chatUserId)->value('last_name');
            $chat[$i]['unread_messages'] = Conversation::where('from_user_id',$chatUserId)
                                                        ->where('to_user_id',$loggedInUser->id)
                                                        ->where('is_viewed',0)
                                                        ->count();
        }


        return response()->json([
            'conversation' => $chat
        ]);



    }


    public function getAllConversation(Request $request){
        $loggedInUser = auth()->user();
        // return $admin;
        
        $postData = $request->getContent();  
        $postData = json_decode($postData, true);
        // return $postData;

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

        Conversation::where(function ($query) use ($postData, $loggedInUser) {
                        $userId = $postData['user_id'];
                    
                        $query->where('conversations.from_user_id', '=', $userId)
                            ->where('conversations.to_user_id', '=', $loggedInUser->id);
                    })
                    ->where('conversations.type',$postData['mode'])->update(['is_viewed' => 1]);

        $conversation = Conversation::leftjoin('contact_details', 'conversations.contact_detail_id','=','contact_details.id')
                                    ->leftjoin('users','conversations.from_user_id','=','users.id')
                                    ->leftjoin('users as u2','conversations.to_user_id','=','u2.id')
                                    ->where(function ($query) use ($postData) {
                                        $userId = $postData['user_id'];
                                        $query->where('conversations.from_user_id', '=', $userId)
                                            ->orWhere('conversations.to_user_id', '=', $userId);
                                    })
                                    ->where('conversations.type',$postData['mode'])
                                    ->get(["contact_details.contact as contactVia","users.first_name as sender","u2.first_name as receiver","conversations.from_user_id as senderId","conversations.to_user_id as receiverId","conversations.message","conversations.type"]);
        
        return response()->json([
            'conversation' => $conversation
        ]);
    }    
    
    
    public function sendReplyMessage(Request $request){
        $loggedInUser = auth()->user();

        $postData = $request->getContent();  
        $postData = json_decode($postData, true);
        // return $postData;
         

        $userPhoneNumber = User::where('id',$postData['user_id'])->value('phone_number');

        $contactInfo = ContactDetail::where('type',$postData['mode'])
                                ->where('user_id',$postData['user_id'])
                                ->where('contact',$userPhoneNumber)
                                ->first(['id','contact']);
                                // return $contactInfo;
        if($postData['mode'] == 'whatsapp'){
            if($contactInfo && $userPhoneNumber == $contactInfo->contact){
                $contactDetail = ContactDetail::find($contactInfo->id);
                // return $contactDetail;
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

            // $this->whatsApp->sendMessage($to, $body);
            $this->whatsApp->sendMessage('whatsapp:'.$userPhoneNumber, $postData['message']);

            return response()->json([
                'message' => 'Whatsapp message sent',
            ]);
        }
        
        
    }
}
