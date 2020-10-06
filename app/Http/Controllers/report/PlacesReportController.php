<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Clinic;
use App\Lab;
use App\Radiology;
use App\Pharmacy;
use App\Incubation;
use App\Icu;

class PlacesReportController extends Controller
{
    public function index() {
        $lnglats = $this->getLngLats();  
        return view("report.places", compact("lnglats"));
    }
    
    public function getLngLats() {
        $lnglats = [];
        $clinics = Clinic::all();
        $labs = Lab::all();
        $radiologys = Radiology::all();
        $pharmacys = Pharmacy::all();
        $incubations = Incubation::all();
        $icus = Icu::all();
        
        foreach($clinics as $clinic) {
            if ($clinic->lang != null && $clinic->latt != null)
            $lnglats[] = [
                "lng" => $clinic->lang,
                "lat" => $clinic->latt,
                "name" => $clinic->doctor()->first()->name
            ];
        }
        foreach($labs as $lab) {
            if ($lab->lang != null && $lab->latt != null)
            $lnglats[] = [
                "lng" => $lab->lang,
                "lat" => $lab->latt,
                "name" => $lab->name
            ];
        }
        foreach($radiologys as $radiology) {
            if ($radiology->lang != null && $radiology->latt != null)
            $lnglats[] = [
                "lng" => $radiology->lang,
                "lat" => $radiology->latt,
                "name" => $radiology->name
            ];
        }
        foreach($pharmacys as $pharmacy) {
            if ($pharmacy->lang != null && $pharmacy->latt != null)
            $lnglats[] = [
                "lng" => $pharmacy->lang,
                "lat" => $pharmacy->latt,
                "name" => $pharmacy->name
            ];
        }
        foreach($incubations as $incubation) {
            if ($incubation->lng != null && $incubation->lat != null)
            $lnglats[] = [
                "lng" => $incubation->lng,
                "lat" => $incubation->lat,
                "name" => $incubation->name
            ];
        }
        foreach($icus as $icu) {
            if ($icu->lng != null && $icu->lat != null)
            $lnglats[] = [
                "lng" => $icu->lng,
                "lat" => $icu->lat,
                "name" => $icu->name
            ];
        }
        
        return $lnglats;
    }
}
