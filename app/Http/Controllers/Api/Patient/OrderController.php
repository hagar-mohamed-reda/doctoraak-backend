<?php

namespace App\Http\Controllers\Api\Patient;

use App\Message;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ClinicOrder;
use App\LabOrder;
use App\PharmacyOrder;
use App\RadiologyOrder;
use App\ClinicWorkingHours;
use App\WorkingHours;
use App\Settings;
use App\Patient;
use App\Clinic;
use App\Doctor;
use App\Favourite;
use App\PatientRate;
use DB;

class OrderController extends Controller

{ 
    
    public function getOrders(Request $request) { 
        $validator = validator()->make(
            $request->all(),
            [ 
                'patient_id' => 'required',
                'api_token' => 'required',
                'user_type' => "required"
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }


        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
    
        $resault = [];
    
        if ($request->user_type == "LAB")
            $resault = LabOrder::where("patient_id", $request->patient_id)->get();
            
        else if ($request->user_type == "RADIOLOGY")
            $resault = RadiologyOrder::where("patient_id", $request->patient_id)->get();
            
        else if ($request->user_type == "PHARMACY")
            $resault = PharmacyOrder::where("patient_id", $request->patient_id)->get();
    
        
        return Message::success(Message::$DONE, Helper::jsonFilter($resault),Message::$DONE_EN);
    }
    
}
