<?php

namespace App\Http\Controllers\Api\Patient;

use App\Message;
use App\Icu;
use App\Patient;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;

class PatientIcuController extends Controller
{

    public function icuFilter(Request $request)
    {

        $validator = validator()->make(
            $request->all(),
            [
              //  'patient_id' => 'required',
           //     'api_token' => 'required'
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
                $resault = Icu::where("city", $request->city)
                    ->where("area", $request->area)->orderByRaw('rate  ASC')->take(50)->get();
            } else {
                $resault = $this->searchNearstIcus($request->lang, $request->latt);
            }
            $messsage = str_replace("n", count($resault), Message::$ICU_SEARCH);
            $messsage_en = str_replace("x", count($resault), Message::$ICU_SEARCH_EN);

            return Message::success($messsage, $resault,$messsage_en);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR , null,Message::$ERROR_EN);
        }
    }


    public function searchNearstIcus($lng, $lat, $newkm = null, $searchNumber = 0)
    {
        $km = (float) Settings::find(1)->value;
        if ($newkm != null)
            $km = $newkm;
        $nearestIcus = [];
        $icus = Icu::orderByRaw('rate  ASC')->take(50)->get();

        foreach ($icus as $icu) {
            // calculate distance between current lng lat and icu lng lat
            $distance = Helper::latLangDistance($lat, $lng, $icu->latt, $icu->lang);
            $icu->distance = $distance;

            // if ($distance <= $km)
            $nearestIcus[] = $icu;
        }
        if ($searchNumber < 1)
            return $nearestIcus;

        if (count($nearestIcus) <= 0)
            $this->searchNearstIcus($lng, $lat, 2 * $km, $searchNumber++);

        return $nearestIcus;
    }
}
