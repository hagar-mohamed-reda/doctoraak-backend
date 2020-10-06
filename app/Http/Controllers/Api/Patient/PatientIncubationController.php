<?php

namespace App\Http\Controllers\Api\Patient;

use App\Message;
use App\Incubation;
use App\helper\Helper;
use App\Patient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;

class PatientIncubationController extends Controller
{

    public function incubationFilter(Request $request)
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
             return Message::error(Message::$API_LOGIN ,null ,Message::$API_LOGIN_EN);
*/


        try {
            $resault = [];
            if ($request->has("city") && $request->has("area")) {
                $resault = Incubation::where("city", $request->city)
                    ->where("area", $request->area)->orderByRaw('rate  ASC')->take(50)->get();
            } else {
                $resault = $this->searchNearstIncubations($request->lang, $request->latt);
            }
            $messsage = str_replace("n", count($resault), Message::$INCUBATION_SEARCH);
            $messsage_en = str_replace("x", count($resault), Message::$INCUBATION_SEARCH_EN);

           
            return Message::success($messsage, $resault,$messsage_en);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR , null,Message::$ERROR_EN);
        }
    }



    public function searchNearstIncubations($lng, $lat, $newkm = null, $searchNumber = 0)
    {
        $km = (float) Settings::find(1)->value;
        if ($newkm != null)
            $km = $newkm;
        $nearestIncubations = [];
        $incubations = Incubation::orderByRaw('rate  ASC')->take(50)->get();

        foreach ($incubations as $incubation) {
            // calculate distance between current lng lat and incubation lng lat
            $distance = Helper::latLangDistance($lat, $lng, $incubation->latt, $incubation->lang);
            $incubation->distance = $distance;

            //if ($distance <= $km)
            $nearestIncubations[] = $incubation;
        }

        if ($searchNumber < 1)
            return $nearestIncubations;

        if (count($nearestIncubations) <= 0)
            $this->searchNearstIncubations($lng, $lat, 2 * $km, $searchNumber++);

        return $nearestIncubations;
    }
}
