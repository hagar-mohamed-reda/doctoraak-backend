<?php

namespace App\Http\Controllers\report\insurance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\ClinicOrder;

use App\UserInsurance;

class ClinicOrderReportController extends Controller {

    public function index(Request $request) { 
        $items = [];
        
        $userInsurance = UserInsurance::find(session("insurance"));
        if ($request->has("datefrom") && $request->has("dateto")) { 
            $items = ClinicOrder::join("patients", "patients.id", "=", "patient_id")
                    ->join("clinics", "clinics.id", "=", "clinic_orders.clinic_id")
                    ->join("doctors", "clinics.doctor_id", "=", "doctors.id")
                    ->join("doctor_insurances", "doctor_insurances.doctor_id", "=", "doctors.id")
                    ->where("doctor_insurances.insurance_id", $userInsurance->insurance->id)
                    ->where("patients.insurance_id", $userInsurance->insurance->id)
                    ->whereBetween("clinic_orders.created_at", [$request->datefrom, $request->dateto])
                    ->get();
        }
        
        return view("report.insurance.clinicOrder", compact("items"));
    }

}
