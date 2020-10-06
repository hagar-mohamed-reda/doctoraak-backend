<?php

namespace App\Http\Controllers\Api\Radiology;

use App\RadiologyOrder;
use App\Radiology;
use App\Message;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\WorkingHours;
use App\Http\Controllers\Controller;

class MainController extends Controller
{

    /**
     *  return radiology orders of this week
     *
     */
    public function getOrder(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'radiology_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );
        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        // chekc if Radiology login
         if (Radiology::where("api_token", $request->api_token)->where("id", $request->radiology_id)->count() <= 0)
               return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      

        try {
            $date = date("Y-m-d");
            $dates = WorkingHours::getStartAndEndDateOfWeek($date);

            $orders = RadiologyOrder::whereBetween("created_at", $dates)->where("radiology_id", $request->radiology_id)->get();

            return Message::success("", Helper::jsonFilter($orders),"");
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }

   
}
