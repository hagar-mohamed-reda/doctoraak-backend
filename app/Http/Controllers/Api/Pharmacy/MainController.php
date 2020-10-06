<?php

namespace App\Http\Controllers\Api\Pharmacy;

use App\PharmacyOrder;
use App\Pharmacy;
use App\Patient;
use App\Message;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\WorkingHours;
use App\Http\Controllers\Controller;

class MainController extends Controller
{

    /**
     *  return Pharmacy orders of this week
     *
     */
    public function getOrder(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'pharmacy_id' => 'required|numeric',
                'api_token' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        // chekc if pharmacy login
        if (Pharmacy::where("api_token", $request->api_token)->where("id", $request->pharmacy_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);


        try {
            $date = date("Y-m-d");
            $dates = WorkingHours::getStartAndEndDateOfWeek($date);
            $orders = PharmacyOrder::join('pharmacy_requests', 'pharmacy_requests.pharmacy_order_id', '=', 'pharmacy_orders.id')
                ->where("pharmacy_requests.pharmacy_id", $request->pharmacy_id)
                ->where("pharmacy_requests.accept", '0')
                ->select('*', "pharmacy_orders.id as id")
                ->get();

            return Message::success("", Helper::jsonFilter($orders),"");
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR ,null,Message::$ERROR_EN);
        }
    }


    public function acceptOrder(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'order_id' => 'required|numeric',
            'pharmacy_id' => 'required|numeric',
            'api_token' => 'required'
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first());
        }

        // chekc if pharmacy login
        if (Pharmacy::where("api_token", $request->api_token)->where("id", $request->pharmacy_id)->count() <= 0)
               return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);

        try {
            $resource = PharmacyOrder::find($request->order_id);

            $pharmacyRequest = $resource->pharmacy_requests()->where("pharmacy_id", $request->pharmacy_id)->first();

            if ($resource) {
                // update pharmacy order
                $resource->update([
                    'pharmacy_id' => $request->pharmacy_id
                ]);
                // update pharmacy request status
                $pharmacyRequest->update([
                    'accept' => 1
                ]);
            }
             

            
             $title_ar="طلب شراء الادويه الخاص بك ";
             $title_en="your pharmacy order details";
             $message= "تم قبول طلبك من ".Pharmacy::find($request->pharmacy_id)->name;
             $message_en="Pharmacy ".Pharmacy::find($request->pharmacy_id)->name ." accepted your order";
             Patient::notify($title_ar,  $title_en,$message,  $message_en, $icon='icon.png',$resource->patient_id, $resource->id, "PHARMACY");
           



            return Message::success(Message::$DONE, [$resource, $pharmacyRequest],Message::$DONE_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR , null,Message::$ERROR_EN);
        }
    }
}
