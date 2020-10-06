<?php

namespace App\Http\Controllers\report\insurance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\LabOrder;

use App\UserInsurance;

class LabOrderReportController extends Controller {

    public function index(Request $request) { 
        $items = [];
        
        $userInsurance = UserInsurance::find(session("insurance"));
        if ($request->has("datefrom") && $request->has("dateto")) { 
            $items = LabOrder::join("patients", "patients.id", "=", "patient_id")
                    ->join("lab_insurances", "lab_insurances.lab_id", "=", "lab_orders.lab_id")
                    ->where("lab_insurances.insurance_id", $userInsurance->insurance->id)
                    ->where("patients.insurance_id", $userInsurance->insurance->id)
                    ->whereBetween("lab_orders.created_at", [$request->datefrom, $request->dateto])
                    ->get();
        }
         
        return view("report.insurance.labOrder", compact("items"));
    }

}
