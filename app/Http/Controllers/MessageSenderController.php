<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\Message;
use App\helper\Helper; 

class MessageSenderController extends Controller
{
      
    public function sendMessage(Request $request) {
        try {
            Notification::notify($request->title_ar, $request->title_en, $request->message_ar, $request->message_en, $request->tokens, $request->user_id, $request->user_type);
           
            return Helper::responseJson(1, __('message has been sent'));
        } catch (\Exception $ex) { 
            return Helper::responseJson(0, Message::$ERROR);
        }
    }
}
