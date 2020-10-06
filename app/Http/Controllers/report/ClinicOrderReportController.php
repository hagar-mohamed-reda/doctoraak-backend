<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ClinicOrderReportController extends Controller
{
    public function index(Request $request) {
        if ($request->has("datefrom") && $request->has("dateto"))
            $resault = DB::select("select DISTINCT(date) as d, (SELECT count(id) from clinic_orders where d=date) as number from clinic_orders where date between '$request->datefrom' and '$request->dateto' ");
        else 
            $resault = DB::select("select DISTINCT(date) as d, (SELECT count(id) from clinic_orders where d=date) as number from clinic_orders ");
        
        //return $resault;
        return view("report.clinicOrder", compact("resault"));
    }
}
