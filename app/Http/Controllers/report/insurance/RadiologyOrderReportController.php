<?php

namespace App\Http\Controllers\report\insurance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\RadiologyOrder;

use App\UserInsurance;

class RadiologyOrderReportController extends Controller {

    public function index(Request $request) { 
        
        $items = [];
        $userInsurance = UserInsurance::find(session("insurance"));
        if ($request->has("datefrom") && $request->has("dateto")) { 
            $items = RadiologyOrder::join("patients", "patients.id", "=", "radiology_orders.patient_id")
                    ->join("radiology_insurances", "radiology_insurances.radiology_id", "=", "radiology_orders.radiology_id")
                    ->where("radiology_insurances.insurance_id", $userInsurance->insurance->id)
                    ->where("patients.insurance_id", $userInsurance->insurance->id)
                    ->whereBetween("radiology_orders.created_at", [$request->datefrom, $request->dateto])
                    ->get();
        }
        
        return view("report.insurance.radiologyOrder", compact("items"));
    }

}
