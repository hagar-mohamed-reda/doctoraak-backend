<?php

namespace App\Http\Controllers\Api\Patient;

use App\Patient;
use App\RadiologyOrder;
use App\RadiologyOrderDetails;
use App\Message;
use App\workingHours;
use App\RadiologyWorkingHours;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\Radiology;
use App\City;
use App\Area;
use DB;

class PatientRadiologyController extends Controller
{


    public function radiologyFilter(Request $request)
    {

        $validator = validator()->make(
            $request->all(),
            [
              //  'patient_id' => 'required',
             //   'api_token' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }

/*
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN, null ,Message::$API_LOGIN_EN);

*/

        try {
            $patient = Patient::find($request->patient_id);
            $resault = [];
            ////search by using radiology id
            if ($request->has("radiology_id") && $request->radiology_id != null) {
                $resault[] = Radiology::find($request->radiology_id);
            } else if ($request->has("city_id") && $request->has("area_id")) {
                $resault = Radiology::where("city_id", $request->city_id)
                    ->where("area_id", $request->area_id)->get();
            } else if ($request->has("lat") && $request->has("lng")) {
                $resault = $this->searchNearstRadiologys($request->lng, $request->lat);
            }

            // fitler the resault with insurance id

            if ($request->insurance == 1 && $patient->insurance_id) {
                $resault = $this->insuranceRadiologyFilter($resault, $patient->insurance_id);
            }
            //  return $patient->insurance_id;
            // build return message
            $messsage = str_replace("n", count($resault), Message::$RADIOLOGY_SEARCH);
            $messsage_en = str_replace("x", count($resault), Message::$RADIOLOGY_SEARCH_EN);

            return Message::success($messsage, Helper::jsonFilter($resault),$messsage_en);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR , null ,Message::$ERROR_EN);
        }
    }

    public function insuranceRadiologyFilter($radiologys, $insurance)
    {
        $filteredRadiologys = [];
        foreach ($radiologys as $radiology) {
            if (optional($radiology->radiology_insurances())->where("insurance_id", $insurance)->count() > 0) {
                $filteredRadiologys[] = $radiology;
            }
        }

        return $filteredRadiologys;
    }

    public function searchNearstRadiologys($lng, $lat, $newkm = null, $searchNumber = 0)
    {
        $km = Settings::find(1)->value;
        if ($newkm)
            $km = $newkm;

        $nearestRadiologys = [];
        $radiologys = Radiology::all();

        foreach ($radiologys as $radiology) {
            // calculate distance between current lng lat and radiology lng lat
            $distance = Helper::latLangDistance($lat, $lng, $radiology->lat, $radiology->lng);
            $radiology->distance = $distance;

            if ($distance <= $km)
                $nearestRadiologys[] = $radiology;
        }

        if ($searchNumber > 2)
            return $nearestRadiologys;

        if (count($nearestRadiologys) <= 0)
            $this->searchNearstradiologys($lng, $lat, 2 * $km, $searchNumber++);

        return $nearestRadiologys;
    }

    public function createRadiologyOrder(Request $request)
    {
         $orderdetails = json_decode($request->orderDetails);

        $validator = validator()->make(
            $request->all(),
            [
                'date' => 'required|date',
                'patient_id' => 'required|numeric',
                'radiology_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );


        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null ,Message::$API_LOGIN_EN);

        if ($request->has("orderDetails")) {
            $messsage = str_replace("n", Settings::find(5)->value, Message::$MAX_ORDER__DETAILS_NUMBER);
            $messsage_en = str_replace("x", Settings::find(5)->value, Message::$MAX_ORDER__DETAILS_NUMBER_EN);

            if (count($orderdetails) > Settings::find(5)->value) {
                return Message::error($messsage, null ,$messsage_en);
            }
        }



        try {

            $radiology = Radiology::find($request->radiology_id);
            $workingHours = RadiologyWorkingHours::where("day", workingHours::getDay($request->date))
                ->where("radiology_id", $radiology->id)->where('active', '1')->first();

            if (!$workingHours)
                return Message::error(Message::$DAY_OFF, null,Message::$DAY_OFF_EN);

            if ($workingHours->active != 1) {
                return Message::error(Message::$DAY_OFF, null ,Message::$DAY_OFF_EN);
            }


            $radiologyOrder = new RadiologyOrder;
            $radiologyOrder->radiology_id = $request->radiology_id;
            $radiologyOrder->patient_id = $request->patient_id;
            $radiologyOrder->notes = $request->notes;
            $radiologyOrder->created_at = date("Y-m-d H:i:s", strtotime($request->date));
           if ($request->hasFile('photo')) {
                $radiologyOrder->photo = Helper::uploadImg($request->file("photo"), "/radiologyorder/");
            }

            $radiologyOrder->save();


            if ($request->has("orderDetails")) {
                foreach ($orderdetails as $detail) {
                    $d = new RadiologyOrderDetails;
                    $d->rays_id = $detail->rays_id;
                    $d->radiology_order = $radiologyOrder->id;

                    $d->save();
                }
            }

            $orderNumber = RadiologyOrder::whereBetween("created_at", [$request->date, $request->date])->where('radiology_id', $request->radiology_id)->count();

            // check on patient reservation number
            $messsage = str_replace("n", Settings::find(6)->value, Message::$MAX_ORDER_NUMBER);
            $messsage_en = str_replace("x", Settings::find(6)->value, Message::$MAX_ORDER_NUMBER_EN);

            if ($orderNumber > Settings::find(6)->value)
                return Message::error($messsage, null ,$messsage_en);

            $city_ar = City::find($radiologyOrder->radiology->city_id)? City::find($radiologyOrder->radiology->city_id)->name_ar : null;
            $city_en = City::find($radiologyOrder->radiology->city_id)? City::find($radiologyOrder->radiology->city_id)->name : null;
            $area_ar = Area::find($radiologyOrder->radiology->area_id)? Area::find($radiologyOrder->radiology->area_id)->name_ar : null;
            $area_en = Area::find($radiologyOrder->radiology->area_id)? Area::find($radiologyOrder->radiology->area_id)->name : null;


            $message = str_replace("patient", $radiologyOrder->patient->name, Message::$RADIOLOGY_ORDER);
            $message = str_replace("number", $orderNumber, $message);
            $message = str_replace("name", $radiologyOrder->radiology->name, $message);
            $message = str_replace("phone", $radiologyOrder->radiology->phone, $message);
            $message = str_replace("city", $city_ar, $message);
            $message = str_replace("area", $area_ar, $message);


            $message_en = str_replace("patient", $radiologyOrder->patient->name, Message::$RADIOLOGY_ORDER_EN);
            $message_en = str_replace("numbers", $orderNumber, $message_en);
            $message_en = str_replace("name", $radiologyOrder->radiology->name, $message_en);
            $message_en = str_replace("phones", $radiologyOrder->radiology->phone, $message_en);
            $message_en = str_replace("citys", $city_en, $message_en);
            $message_en = str_replace("areas", $area_en, $message_en);

            /////notification to patients /////////////////////////
            $title_ar = "بيانات الحجز الخاص بك ".Patient::find($request->patient_id)->name;
            $title_en = "your reservation details ".Patient::find($request->patient_id)->name;
            Patient::notify($title_ar,  $title_en,$message,  $message_en, $icon='icon.png',$request->patient_id, $radiologyOrder->id, "RADIOLOGY");


            ///////////////notification for radiology
            $message_ar_r ="لقد قام   ".Patient::find($request->patient_id)->name." بالحجز لديك و رقم هانفه " .Patient::find($request->patient_id)->phone;
            $message_en_r = "you have new book from ". Patient::find($request->patient_id)->name . "has phone number is" . Patient::find($request->patient_id)->phone ;
            $title_ar_r = "بيانات الحجز ";
            $title_en_r = "Reservation details";
            Radiology::notify($title_ar_r,  $title_en_r,$message_ar_r,  $message_en_r, $icon='icon.png',$request->radiology_id, $radiologyOrder->id);

            return Message::success($message, null,  $message_en);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR , null ,Message::$ERROR_EN);
        }
    }
}
