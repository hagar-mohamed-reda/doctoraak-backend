<?php

namespace App\Http\Controllers\report\insurance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\PharmacyOrder;

use App\UserInsurance;

class PharmacyOrderReportController extends Controller {

    public function index(Request $request) { 
        $items = [];
        
        $userInsurance = UserInsurance::find(session("insurance"));
        if ($request->has("datefrom") && $request->has("dateto")) { 
            $items = PharmacyOrder::join("patients", "patients.id", "=", "patient_id")
                    //->join("pharmacy_insurances", "pharmacy_insurances.pharmacy_id", "=", "pharmacy_orders.pharmacy_id")
                    //->where("pharmacy_insurances.insurance_id", $userInsurance->insurance->id)
                    ->where("patients.insurance_id", $userInsurance->insurance->id)
                    ->whereBetween("pharmacy_orders.created_at", [$request->datefrom, $request->dateto])
                    ->get();
        }
         
        return view("report.insurance.pharmacyOrder", compact("items"));
    }

}
