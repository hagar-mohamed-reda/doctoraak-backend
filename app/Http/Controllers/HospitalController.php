<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use App\Doctor;
use App\Message; 
use DataTables; 
use App\helper\Helper; 

class HospitalController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("doctor.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        return DataTables::eloquent(Doctor::query()->where("isHospital", '1'))
                        ->editColumn('action', function(Doctor $doctor) {
                            $delete = route("doctorDelete", ["doctor" => $doctor->id]);
                            $update = route("doctorUpdate", ["doctor" => $doctor->id]);
                            return view("hospital.action", compact("delete", "update"));
                        })
                        ->editColumn('specialization', function(Doctor $doctor) {
                            return $doctor->specialization()->first()->name;
                        })
                        ->editColumn('degree', function(Doctor $doctor) {
                            return $doctor->degree()->first()->name;
                        })
                        ->editColumn('image', function(Doctor $doctor) {
                            return "<img src='" . url('/image/doctor') . "/" . $doctor->photo . "' height='60px' onclick='viewImage(this)'  >";
                        })
                        ->editColumn('file', function(Doctor $doctor) {
                            return "<span class='label label-info' data-src='" . url('/file/doctor') . "/" . $doctor->cv . "' onclick='viewFile(this)'  >$doctor->cv</span>";
                        })
                        ->editColumn('active', function(Doctor $doctor) {
                            $label = ($doctor->active == 1) ? "success" : "danger";
                            $text = ($doctor->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->rawColumns(['action', 'file', 'image', 'active'])
                        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("doctor.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $doctor = new Doctor;
            $doctor->name = $request->name;
            $doctor->name_ar = $request->name_ar;
            $doctor->name_fr = $request->name_fr;
            $doctor->phone = $request->phone;
            $doctor->email = $request->email;
            $doctor->password = $request->password;
            $doctor->specialization_id = $request->specialization;
            $doctor->degree_id = $request->degree;
            $doctor->reservation_rate = $request->reservation_rate;
            $doctor->degree_rate = $request->degree_rate;
            $doctor->active = $request->has("active") ? 1 : 0;

            // upload image
            if ($request->hasFile('image')) {
                $doctor->photo = Helper::uploadImg($request->file("image"), "/doctor/");
            }

            // upload cv
            if ($request->hasFile('cv')) {
                $doctor->cv = Helper::uploadFile($request->file("cv"), "/doctor/");
            }

            $doctor->save();
            return Message::redirect("doctor/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("doctor/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor) {
        $obj = $doctor;
        return view("doctor.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor) {
        try {
            $doctor->name = $request->name;
            $doctor->name_ar = $request->name_ar;
            $doctor->name_fr = $request->name_fr;
            $doctor->phone = $request->phone;
            $doctor->email = $request->email;
            $doctor->password = $request->password;
            $doctor->specialization_id = $request->specialization;
            $doctor->degree_id = $request->degree;
            $doctor->reservation_rate = $request->reservation_rate;
            $doctor->degree_rate = $request->degree_rate;
            $doctor->active = $request->has("active") ? 1 : 0;

            // upload image
            if ($request->hasFile('image')) {
                // remove old image
                Helper::removeFile(public_path() . '/image/doctor/' . $doctor->photo);
                $doctor->photo = Helper::uploadImg($request->file("image"), "/doctor/");
            }

            // upload cv
            if ($request->hasFile('cv')) {
                // remove old cv
                Helper::removeFile(public_path() . '/file/doctor/' . $doctor->cv);
                $doctor->cv = Helper::uploadFile($request->file("cv"), "/doctor/");
            }

            $doctor->update();
            return Message::redirect("doctor", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("doctor", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor) {
        if ($doctor->clinics()->count() > 0 ||
                $doctor->blocks()->count() ||
                $doctor->rates()->count() ||
                $doctor->favourites()->count() ||
                $doctor->doctor_insurances()->count()) {
            return Message::$DELETE_ERROR;
        }

        try {
            $doctor->delete();
            return Message::redirect("doctor/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("doctor/", Message::$ERROR, 0);
        }
    }

}
