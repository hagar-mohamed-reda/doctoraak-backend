<?php

namespace App\Http\Controllers\Api\Lab;

use App\LabOrder;
use App\Lab;
use App\Message;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\WorkingHours;
use App\Http\Controllers\Controller;

class MainController extends Controller
{

    /**
     *  return lab orders of this week
     *
     */
    public function getOrder(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'lab_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );
        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        // chekc if Lab login
        if (Lab::where("api_token", $request->api_token)->where("id", $request->lab_id)->count() <= 0)
           return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      

        try {
            $date = date("Y-m-d");
            $dates = WorkingHours::getStartAndEndDateOfWeek($date);
            //$dates = [$dates[0] . " 00:00:00", $dates[1]." 00:00:00"];

            $orders = LabOrder::whereBetween("created_at", $dates)->where("lab_id", $request->lab_id)->get();
 
            return Message::success("تفاصيل الطلب ", Helper::jsonFilter($orders),"order details");
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }
    /* public function rejectOrder(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'order_id' => 'required|numeric',
            'lab_id' => 'required|numeric',
            'api_token' => 'required'
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first());
        }

        // chekc if patient login
        if (Lab::where("api_token", $request->api_token)->where("id", $request->lab_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN);

        try {
            $resource = LabOrder::find($request->order_id);
            // update clinic order status
            $resource->update([
                'active' => 0
            ]);
            return Message::success(Message::$REJECT_ORDER, null);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR . $e->getMessage(), null);
        }
    }*/
}
