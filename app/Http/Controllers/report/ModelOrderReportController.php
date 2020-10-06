<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ModelOrderReportController extends Controller
{
    public function index(Request $request) {
        // clinic orders
        if ($request->has("datefrom") && $request->has("dateto"))
            $clinicorders = DB::select("select DISTINCT(date) as d, (SELECT count(id) from clinic_orders where d=date) as number from clinic_orders where date between '$request->datefrom' and '$request->dateto' ");
        else 
            $clinicorders = DB::select("select DISTINCT(date) as d, (SELECT count(id) from clinic_orders where d=date) as number from clinic_orders ");
        
        // lab orders 
        if ($request->has("datefrom") && $request->has("dateto"))
            $laborders = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from lab_orders where d=created_at) as number from lab_orders where created_at between '$request->datefrom' and '$request->dateto' ");
        else 
            $laborders = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from lab_orders where d=created_at) as number from lab_orders ");
        
        
        
        // radiology orders 
        if ($request->has("datefrom") && $request->has("dateto"))
            $radiologyorders = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from radiology_orders where d=created_at) as number from radiology_orders where created_at between '$request->datefrom' and '$request->dateto' ");
        else 
            $radiologyorders = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from radiology_orders where d=created_at) as number from radiology_orders ");
        
        
        // pharmacy orders 
        if ($request->has("datefrom") && $request->has("dateto"))
            $pharmacyorders = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from pharmacy_orders where d=created_at) as number from pharmacy_orders where created_at between '$request->datefrom' and '$request->dateto' ");
        else 
            $pharmacyorders = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from pharmacy_orders where d=created_at) as number from pharmacy_orders ");
        
        //return $resault;
        return view("report.modelOrder", compact("clinicorders", "laborders", "radiologyorders", "pharmacyorders"));
    }
}
