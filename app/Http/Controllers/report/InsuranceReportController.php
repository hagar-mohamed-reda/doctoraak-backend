<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Insurance;

class InsuranceReportController extends Controller
{
    public function index() {
        $insurances = Insurance::all();
        
        return view("report.insurance", compact("insurances"));
    }
}
