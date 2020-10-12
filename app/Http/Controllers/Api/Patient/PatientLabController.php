<?php

namespace App\Http\Controllers\Api\Patient;

use App\Patient;
use App\LabOrder;
use App\LabOrderDetails;
use App\Message;
use App\workingHours;
use App\LabWorkingHours;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\Lab;
use App\City;
use App\Area;
use DB;

class PatientLabController extends Controller
{


    public function labFilter(Request $request)
    {

        $validator = validator()->make(
            $request->all(),
            [
             //   'patient_id' => 'required',
         //       'api_token' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }

/*
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
             return Message::error(Message::$API_LOGIN ,null ,Message::$API_LOGIN_EN);
*/

        try {
            $patient = Patient::find($request->patient_id);
            $resault = [];
            ////search by using lab id
            if ($request->has("lab_id") && $request->lab_id != null) {
                $resault[] = Lab::find($request->lab_id);
            } else if ($request->has("city_id") && $request->has("area_id")) {
                $resault = Lab::where("city_id", $request->city_id_id)
                    ->where("area_id", $request->area_id_id)->get();
            } else if ($request->has("lat") && $request->has("lng")) {
                $resault = $this->searchNearstLabs($request->lng, $request->lat);
            }

            // fitler the resault with insurance id
            if ($request->insurance == 1 && $patient->insurance_id) {
                $resault = $this->insuranceLabFilter($resault, $patient->insurance_id);
            }
            // build return message
            $messsage = str_replace("n", count($resault), Message::$LAB_SEARCH);
            $messsage_en = str_replace("x", count($resault), Message::$LAB_SEARCH_EN);

              return Message::success($messsage, Helper::jsonFilter($resault),$messsage_en);
         } catch (\Exception $ex) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }

    public function insuranceLabFilter($labs, $insurance)
    {
        $filteredLabs = [];
        foreach ($labs as $lab) {
            if (optional($lab->lab_insurances)->where("insurance_id", $insurance)->count() > 0) {
                $filteredLabs[] = $lab;
            }
        }

        return $filteredLabs;
    }

    public function searchNearstLabs($lng, $lat, $newkm = null, $searchNumber = 0)
    {
        $km = Settings::find(1)->value;
        if ($newkm)
            $km = $newkm;

        $nearestLabs = [];
        $labs = Lab::all();

        foreach ($labs as $lab) {
            // calculate distance between current lng lat and lab lng lat
            $distance = Helper::latlngDistance($lat, $lng, $lab->lat, $lab->lng);
            $lab->distance = $distance;

            if ($distance <= $km)
                $nearestLabs[] = $lab;
        }

        if ($searchNumber > 2)
            return $nearestLabs;

        if (count($nearestLabs) <= 0)
            $this->searchNearstLabs($lng, $lat, 2 * $km, $searchNumber++);

        return $nearestLabs;
    }

    public function createLabOrder(Request $request)
    {

          $orderdetails = json_decode($request->orderDetails);

        $validator = validator()->make(
            $request->all(),
            [
                'date' => 'required|date',
                'patient_id' => 'required|numeric',
                'lab_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );


        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN, null ,Message::$API_LOGIN_EN);

        if ($request->has("orderDetails")) {
            $messsage = str_replace("n", Settings::find(5)->value, Message::$MAX_ORDER__DETAILS_NUMBER);
            $messsage_en = str_replace("x", Settings::find(5)->value, Message::$MAX_ORDER__DETAILS_NUMBER_EN);

            if (count($orderdetails) > Settings::find(5)->value) {
                return Message::error($messsage, null,$messsage_en);
            }
        }



        try {

            $lab = Lab::find($request->lab_id);
            $workingHours = LabWorkingHours::where("day", workingHours::getDay($request->date))
                ->where("lab_id", $lab->id)->where('active', '1')->first();

            if (!$workingHours)
                return Message::error(Message::$DAY_OFF, null,Message::$DAY_OFF_EN);

            if ($workingHours->active != 1) {
                return Message::error(Message::$DAY_OFF, null,Message::$DAY_OFF_EN);
            }


            $labOrder = new LabOrder;
            $labOrder->lab_id = $request->lab_id;
            $labOrder->patient_id = $request->patient_id;
            $labOrder->notes = $request->notes;
            $labOrder->created_at = date("Y-m-d H:i:s", strtotime($request->date));
           if ($request->hasFile('photo')) {
                $labOrder->photo = Helper::uploadImg($request->file("photo"), "/laborder/");
            }

            $labOrder->save();


            if ($request->has("orderDetails")) {
                foreach ($orderdetails as $detail) {
                    $d = new LabOrderDetails;
                    $d->analysis_id = $detail->analysis_id;
                    $d->lab_order = $labOrder->id;

                    $d->save();
                }
            }

            $orderNumber = LabOrder::whereBetween("created_at", [$request->date, $request->date])->where('lab_id', $request->lab_id)->count();

            // check on patient reservation number
            $messsage = str_replace("n", Settings::find(6)->value, Message::$MAX_ORDER_NUMBER);
            $messsage_en = str_replace("x", Settings::find(6)->value, Message::$MAX_ORDER_NUMBER_EN);

            if ($orderNumber > Settings::find(6)->value)
                return Message::error($messsage, null,$messsage_en);


            $city_ar = City::find($labOrder->lab->city_id)? City::find($labOrder->lab->city_id)->name_ar : null;
            $city_en = City::find($labOrder->lab->city_id)? City::find($labOrder->lab->city_id)->name : null;
            $area_ar = Area::find($labOrder->lab->area_id)? Area::find($labOrder->lab->area_id)->name_ar : null;
            $area_en = Area::find($labOrder->lab->area_id)? Area::find($labOrder->lab->area_id)->name : null;

            $message = str_replace("patient", $labOrder->patient->name, Message::$LAB_ORDER);
            $message = str_replace("number", $orderNumber, $message);
            $message = str_replace("name", $labOrder->lab->name, $message);
            $message = str_replace("phone", $labOrder->lab->phone, $message);
            $message = str_replace("city", $city_ar, $message);
            $message = str_replace("area", $area_ar, $message);

            $message_en = str_replace("patient", $labOrder->patient->name, Message::$LAB_ORDER_EN);
            $message_en = str_replace("numbers", $orderNumber, $message_en);
            $message_en = str_replace("name", $labOrder->lab->name, $message_en);
            $message_en = str_replace("phones", $labOrder->lab->phone, $message_en);
            $message_en = str_replace("citys", $city_en, $message_en);
            $message_en = str_replace("areas", $area_en, $message_en);

            /////notification to patients /////////////////////////
            $title_ar = "بيانات الحجز الخاص بك ".Patient::find($request->patient_id)->name;
            $title_en = "your reservation details ".Patient::find($request->patient_id)->name;
            Patient::notify($title_ar,  $title_en,$message,  $message_en, $icon='icon.png',$request->patient_id, $labOrder->id, "LAB");



            ///////////////notification for lab



            $message_ar_l ="لقد قام   ".Patient::find($request->patient_id)->name." بالحجز لديك و رقم هانفه " .Patient::find($request->patient_id)->phone;
            $message_en_l = "you have new book from ". Patient::find($request->patient_id)->name . "has phone number is" . Patient::find($request->patient_id)->phone ;
             $title_ar_l = "بيانات الحجز ";
            $title_en_l = "Reservation details";
           Lab::notify($title_ar_l,  $title_en_l,$message_ar_l,  $message_en_l, $icon='icon.png',$request->lab_id, $labOrder->id);



            return Message::success($message, null,  $message_en);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR , null ,Message::$ERROR_EN);
        }
    }
}
