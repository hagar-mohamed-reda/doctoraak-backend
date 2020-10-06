<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Doctor;

class DoctorReportController extends Controller
{
    public function index() {
        $doctors = Doctor::all();
        return view("report.doctor", compact("doctors"));
    }
}
