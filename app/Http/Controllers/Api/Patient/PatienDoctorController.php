<?php

namespace App\Http\Controllers\Api\Patient;

use App\Message;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ClinicOrder;
use App\ClinicWorkingHours;
use App\WorkingHours;
use App\Settings;
use App\Patient;
use App\Clinic;
use App\Doctor;
use App\City;
use App\Area;
use App\Favourite;
use App\PatientRate;
use DB;

class PatienDoctorController extends Controller

{

    /**
     * Undocumented function
     * Start Registration function
     * @param Request $request
     * @return void
     */
    public function searchClinic(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'specialization_id' => 'required',
             //   'patient_id' => 'required',
              //  'api_token' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }

        return Message::error($request->lat . "-" . $request->lng,null,Message::$DATA_NOT_FIND_EN);
   /*

        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
   */
        try {
            $resault = [];
            $patient = Patient::find($request->patient_id);

            // search with city and area if exist
            if ($request->has("city_id") && $request->has("city_id")) {

            $resault = Clinic::select('*', DB::raw('clinics.id AS id'))
                    ->where("specialization_id", $request->specialization_id)
                    ->where("city_id", $request->city_id)
                    ->where("area_id", $request->area_id)
                    ->orderByRaw('reservation_rate - degree_rate ASC')
                   ->leftJoin('doctors', function($join) {
                   $join->on('clinics.doctor_id', '=', 'doctors.id');
                     })->get();

            }

            if ($request->has("lat") && $request->has("lng")) {
                // search with lng lat if exist
                $resault = $this->searchNearstClinics($request->lng, $request->lat, $request->specialization_id);
            }

            // fitler the resault with insurance id
            if ($request->insurance == 1 && $patient->insurance_id) {
                $resault = $this->insuranceClinicFilter($resault, $patient->insurance_id);
            }

            // fitler with medical center if exist
            if ($request->is_medical_center != null && $request->is_medical_center == 1) {
                $resault = $this->medicalCenterFilter($resault);
            }

            // check if the resault is empty
            if (count($resault) <= 0)
                return Message::error(Message::$DATA_NOT_FIND,null,Message::$DATA_NOT_FIND_EN);

            // build return message
            $messsage = str_replace("n", count($resault), Message::$CLINIC_SEARCH);
            $messsage_en = str_replace("x", count($resault), Message::$CLINIC_SEARCH_EN);

            return Message::success($messsage, Helper::jsonFilter($resault),$messsage_en);
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, $exc->getMessage() , null,Message::$ERROR_EN);
        }
    }


    /**
     * filter clinic list with insurance id
     *
     * @param [type] $clinics
     * @param [type] $insurance
     * @return void
     */
    public function insuranceClinicFilter($clinics, $insurance)
    {
        $filteredClinics = [];
        foreach ($clinics as $clinic) {
            if (optional(optional($clinic->doctor)->doctor_insurances)->where("insurance_id", $insurance)->count() > 0) {
                $filteredClinics[] = $clinic;
            }
        }

        return $filteredClinics;
    }


    /**
     * filter clinic list with for medical center
     *
     * @param [type] $clinics
     * @param [type] $insurance
     * @return void
     */
    public function medicalCenterFilter($clinics)
    {
        $filteredClinics = [];
        foreach ($clinics as $clinic) {
            if ($clinic->doctor) {
                if (optional($clinic->doctor)->is_medical_center == 1) {
                    $filteredClinics[] = $clinic;
                }
            }
        }

        return $filteredClinics;
    }


    /**
     * search on nearst clinic with specail distance
     *
     * @param [type] $lng
     * @param [type] $lat
     * @param [type] $specialization
     * @param [type] $newkm
     * @return void
     */
    public function searchNearstClinics($lng, $lat, $specialization, $newkm = null)
    {
        $km = (float) Settings::find(1)->value;
        if ($newkm)
            $km = $newkm;
        $nearestClinics = [];
        $clinics = Clinic::all();

        foreach ($clinics as $clinic) {
            // calculate distance between current lng lat and clinci lng lat
            $distance = Helper::latLangDistance($lat, $lng, $clinic->latt, $clinic->lang);

            //
            $clinic->distance = $distance;

            if (($distance <= $km) && ($clinic->doctor->specialization_id ==  $specialization))
                $nearestClinics[] = $clinic;
        }

        if (count($nearestClinics) <= 0)
            $this->searchNearstClinics($lng, $lat,  $specialization, 2 * $km);

        // sort the resault with nearst km
        $nearestClinics = array_sort($nearestClinics, 'distance', SORT_ASC);

        return $nearestClinics;
    }

    /**
     * create reservation for clinic
     *
     * @param Request $request
     * @return type
     */
    public function createClinicOrder(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'date' => 'required|date',
                'part'=>'required',
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
                'api_token' => 'required',
                'type' => 'required|numeric|min:1|max:3'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }


        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);


        // check on the request date if < today date
        if (strtotime($request->date) < strtotime(date("Y-m-d")))
            return Message::error(Message::$CANT_ORDER_LAST_DATE, null,Message::$CANT_ORDER_LAST_DATE_EN);

        // add reservation to queue based on parts
        return $this->addToReservationQueue(function ($part, $reservationNumber) use ($request) {
            $clinicOrder = new ClinicOrder;
            $clinicOrder->clinic_id = $request->clinic_id;
            $clinicOrder->patient_id = $request->patient_id;
            $clinicOrder->part_id = $part;
            $clinicOrder->reservation_number = $reservationNumber;
            $clinicOrder->type = $request->type;
            $clinicOrder->notes = $request->notes;
            $clinicOrder->date = $request->date;
            $clinicOrder->active = "1";

            $clinicOrder->save();

           }, $request);

    }

    /**
     * add reservation to clinic day queue based on part_id
     *
     * @param [function] $action
     * @param Request $request
     * @return void
     */
    public function addToReservationQueue($action, Request $request)
    {
        try {
            $clinic = Clinic::find($request->clinic_id);
            $workingHours = ClinicWorkingHours::where("day", WorkingHours::getDay($request->date))->where("clinic_id", $clinic->id)->first();

            $part1QueueMaxNumber = $workingHours->reservation_number_1;
            $part2QueueMaxNumber = $workingHours->reservation_number_2;


            $clinicOrdersPart1QueueNumber = ClinicOrder::where("date", $request->date)->where("clinic_id", $clinic->id)->where("part_id", '1')->where("active", '1')->count() + 1;
            $clinicOrdersPart2QueueNumber = ClinicOrder::where("date", $request->date)->where("clinic_id", $clinic->id)->where("part_id", '2')->where("active", '1')->count() + 1;

            $patientReservationNumber = ClinicOrder::where("date", $request->date)
                ->where("clinic_id", $clinic->id)
                ->where("patient_id", $request->patient_id)
                ->count();

            $data = null;

            // check on clinic working hours
            if ($workingHours->active != 1) {
                return Message::error(Message::$DAY_OFF, null,Message::$DAY_OFF_EN);
            }

            // check on patient reservation number
            $messsage = str_replace("n", Settings::find(3)->value, Message::$MAX_RESERVATION_NUMBER);
            $messsage_en = str_replace("x", Settings::find(3)->value, Message::$MAX_RESERVATION_NUMBER_EN);

            if ($patientReservationNumber >= Settings::find(3)->value)
                return Message::error($messsage, null, $messsage_en);

            if ($clinicOrdersPart1QueueNumber <= $part1QueueMaxNumber && $request->part == 1) {
                $action(1, $clinicOrdersPart1QueueNumber);

                $data = [
                    "reservation_number" => $clinicOrdersPart1QueueNumber,
                    "reservation_time" => $request->date . " " . ClinicWorkingHours::calculateReservationTime($clinic->waiting_time, $clinicOrdersPart1QueueNumber, $workingHours->part1_from),
                    "part_id" => 1,
                ];
            } else if ($clinicOrdersPart2QueueNumber <= $part2QueueMaxNumber  && $request->part == 2) {
                $action(2, $clinicOrdersPart2QueueNumber);

                $data = [
                    "reservation_number" => $clinicOrdersPart2QueueNumber,
                    "reservation_time" => $request->date . " " . ClinicWorkingHours::calculateReservationTime($clinic->waiting_time, $clinicOrdersPart2QueueNumber, $workingHours->part2_from),
                    "part_id" => 2,
                ];
            } else {
                return Message::error(Message::$DAY_BUSY, $data,Message::$DAY_BUSY_EN . $request->part);
            }


            $city_ar = optional($clinic->city)->name_ar;
            $city_en = optional($clinic->city)->name;
            $area_ar = optional($clinic->area)->name_ar;
            $area_en = optional($clinic->area)->name;

            // build return message
            $messsage = str_replace("patient", Patient::find($request->patient_id)->name, Message::$CLINIC_RESERVATION);
            $messsage = str_replace("doctor", $clinic->doctor->name, $messsage);
            $messsage = str_replace("time", $data["reservation_time"], $messsage);
            $messsage = str_replace("number", $data["reservation_number"], $messsage);
            $messsage = str_replace("part", $data["part_id"], $messsage);
            $messsage = str_replace("phone", $clinic->phone, $messsage);
            $messsage = str_replace("city", $city_ar, $messsage);
            $messsage = str_replace("area", $area_ar, $messsage);



            $messsage_en = str_replace("{patient}", Patient::find($request->patient_id)->name, Message::$CLINIC_RESERVATION_EN);
            $messsage_en = str_replace("{doctor}", $clinic->doctor->name, $messsage_en);
            $messsage_en = str_replace("{time}", $data["reservation_time"], $messsage_en);
            $messsage_en = str_replace("{number}", $data["reservation_number"], $messsage_en);
            $messsage_en = str_replace("{part}", $data["part_id"], $messsage_en);
            $messsage_en = str_replace("{phones}", $clinic->phone, $messsage_en);
            $messsage_en = str_replace("{citys}", $city_en, $messsage_en);
            $messsage_en = str_replace("{areas}", $area_en, $messsage_en);


            /////notification to patients /////////////////////////
            $title_ar = "بيانات الحجز الخاص بك ".Patient::find($request->patient_id)->name;
            $title_en = "your reservation details ".Patient::find($request->patient_id)->name;

            $icon = "";
            $id = ClinicOrder::query()->latest('created_at')->first()? ClinicOrder::query()->latest('created_at')->first()->id : null;
            Patient::notify($title_ar,  $title_en,$messsage,  $messsage_en, $icon,$request->patient_id, $id, "DOCTOR");


            //////////////////////  notification to doctor///////////////////////////
            $doctor = (ClinicOrder::query()->latest('created_at')->first()->clinic->doctor)? ClinicOrder::query()->latest('created_at')->first()->clinic->doctor->id : null; //Clinic::find($request->clinic_id)->doctor->id;
             $message_ar_d ="لقد قام   ".Patient::find($request->patient_id)->name." بالحجز لديك و رقم هانفه " .Patient::find($request->patient_id)->phone;
            $message_en_d = "you have new book from ". Patient::find($request->patient_id)->name . "has phone number is" . Patient::find($request->patient_id)->phone ;

            $title_ar_d = "بيانات الحجز ";
            $title_en_d = "Reservation details";
            Doctor::notify($title_ar_d,  $title_en_d,$message_ar_d, $message_en_d, $icon,$doctor, $id);


            return Message::success($messsage, null,$messsage_en);
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR . $exc->getMessage(), null ,  $exc->getMessage());
        }
    }

    /**
     * rate the doctor from patient
     *
     * @param Request $request
     * @return void
     */
    public function rateDoctor(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'doctor_id' => 'required|numeric',
            'patient_id' => 'required|numeric',
            'api_token' => 'required',
            'rate' => 'required|numeric|min:1|max:5',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);

        // chekc if patient rate this doctor

        if (PatientRate::where("patient_id", $request->patient_id)->where("type", "PATIENT")->where("doctor_id", $request->doctor_id)->count() > 0)
            return Message::error(Message::$DOCTOR_RATE_ERROR,null,Message::$DOCTOR_RATE_ERROR_EN);

        try {
            PatientRate::create($request->all());
            return Message::success(Message::$RATE,  null,Message::$RATE_EN);
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }

    /**
     * toggle favourite doctor
     * 1) add doctor to favourite
     * 2) remove doctor from favourite
     *
     * @param Request $request
     * @return void
     */
    public function toggleFavourite(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'doctor_id' => 'required|numeric',
            'patient_id' => 'required|numeric',
            'api_token' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null ,Message::$API_LOGIN_EN);

        try {
            $favourite = Favourite::where("patient_id", $request->patient_id)->where("doctor_id", $request->doctor_id)->first();
            // chekc if patient rate this doctor
            if (!$favourite) {
                Favourite::create($request->all());
                return Message::success(Message::$ADD_FAVOURITE,  null,Message::$ADD_FAVOURITE_EN);
            } else {
                $favourite->delete();
                return Message::success(Message::$REMOVE_FAVOURITE,  null,Message::$REMOVE_FAVOURITE_EN);
            }
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }


    /**
     * get all doctor from favourite table
     *
     * @param Request $request
     * @return void
     */
    public function getFavouriteList(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'patient_id' => 'required|numeric',
            'api_token' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);

        try {
            $patient = Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->first();
            $doctors = $patient->favourites()->pluck("doctor_id");
            //return $doctors;
            $resault = Clinic::whereIn("doctor_id", $doctors)->get();
            return Message::success("",  Helper::jsonFilter($resault),"");
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }

    public function getReservations(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'patient_id' => 'required|numeric',
            'api_token' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null ,Message::$API_LOGIN_EN);

        try {
            $date = strtotime(date("Y-m-d"));
            $yesterday = date("Y-m-d", strtotime('-1 day', $date));

            $orders = ClinicOrder::where("active", "1")
                ->where("patient_id", $request->patient_id)
                ->where("date", ">=", $yesterday)
                ->orderBy('date', 'ASC')
                ->get();
                  $messsage = str_replace("n", count($orders), Message::$RESERVATION_FOR_PATIENT);
                  $messsage_en = str_replace("x", count($orders), Message::$RESERVATION_FOR_PATIENT_EN);

                   return Message::success($messsage, Helper::jsonFilter($orders), $messsage_en);

        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }
}
