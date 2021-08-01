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

class ConversationController extends Controller
{
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
            'chat' => $chat
        ]);
    }


    public function getAllConversation(Request $request){
        $admin = auth()->user();
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


        $conversation = Conversation::leftjoin('contact_details', 'conversations.contact_detail_id','=','contact_details.id')
        ->leftjoin('users','conversations.from_user_id','=','users.id')
        ->leftjoin('users as u2','conversations.to_user_id','=','u2.id')

        // ->leftjoin('users','conversations.to_user_id','=','users.id')
        // ->where('conversations.contact_detail_id',$id)
        ->where(function ($query) use ($postData) {
            $userId = $postData['user_id'];
            $query->where('conversations.from_user_id', '=', $userId)
                ->orWhere('conversations.to_user_id', '=', $userId);
        })
        ->where('conversations.type',$postData['mode'])
        ->get(["contact_details.contact as contactVia","users.first_name as sender","u2.first_name as receiver","conversations.from_user_id as senderId","conversations.to_user_id as receiverId","conversations.message","conversations.type"]);
        
        
        // where(function ($query) use ($postData) {
        //     $userId = $postData['user_id'];
        //     $query->where('from_user_id', '=', $userId)
        //         ->orWhere('to_user_id', '=', $userId);
        // })
        // ->where('type',$postData['mode'])
        // ->get();
        return response()->json([
            'conversation' => $conversation
        ]);
    }
}
