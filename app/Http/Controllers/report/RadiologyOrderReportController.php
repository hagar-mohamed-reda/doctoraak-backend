<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class RadiologyOrderReportController extends Controller
{
    
    public function index(Request $request) {
        if ($request->has("datefrom") && $request->has("dateto"))
            $resault = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from radiology_orders where d=created_at) as number from radiology_orders where created_at between '$request->datefrom' and '$request->dateto' ");
        else 
            $resault = DB::select("select DISTINCT(created_at) as d, (SELECT count(id) from radiology_orders where d=created_at) as number from radiology_orders ");
        
        //return $resault;
        return view("report.radiologyOrder", compact("resault"));
    }
}
