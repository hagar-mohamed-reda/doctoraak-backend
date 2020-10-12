<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Doctor;
use App\DoctorInsurance;
use App\Message;
//use Yajra\DataTables\DataTables;
use DataTables;

use App\helper\Helper;
use DB;

class DoctorController extends Controller {

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
        $query = Doctor::query()->where("isHospital", '0');


        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('email', 'like', '%'.request()->search_string.'%')
                    ->orWhere('phone', 'like', '%'.request()->search_string.'%');
        }

        if (request()->specialization_id > 0) {
            $query->where('specialization_id', request()->specialization_id);
        }

        if (request()->degree_id) {
            $query->where('degree_id', request()->degree_id);
        }

        if (request()->gender) {
            $query->where('gender', request()->gender);
        }

        if (request()->reservation_rate) {
            $query->where('reservation_rate', request()->reservation_rate);
        }

        if (request()->degree_rate) {
            $query->where('degree_rate', request()->degree_rate);
        }

        if (request()->active == 1) {
            $query->where('active', "1");
        }

        if (request()->active == 2) {
            $query->where('active', "0");
        }

        return DataTables::eloquent($query)
                        ->addColumn('action', function(Doctor $doctor) {
                            return view("doctor.action", compact("doctor"));
                        })
                        ->editColumn('specialization_id', function(Doctor $doctor) {
                            return ($doctor->specialization)? $doctor->specialization->name : '';
                        })
                        ->editColumn('degree_id', function(Doctor $doctor) {
                            return ($doctor->degree)? $doctor->degree->name : '';
                        })
                        ->addColumn('photo', function(Doctor $doctor) {
                            return "<img class='w3-circle".Helper::randColor()."' style='padding: 3px' src='" .  $doctor->url . "' height='40px' width='40px' onclick='viewImage(this)'  >";
                        })

                        ->editColumn('active', function(Doctor $doctor) {
                            $label = ($doctor->active == 1) ? "success" : "danger";
                            $text = ($doctor->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->rawColumns(['action', 'photo', 'active'])
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
        $insurances = explode(',', $request->insurance_id);

        $validator = validator()->make($request->all(), [
            'photo' => 'required',
            'cv' => 'required',
        ]);


        //return $request->file("cv");

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        // check email
        if (Doctor::where("email", $request->email)->count() > 0)
            return Message::error(Message::$EMAIL_UNIQUE,  null,Message::$EMAIL_UNIQUE_EN);

        // check phone
        if (Doctor::where("phone", $request->phone)->count() > 0)
            return Message::error(Message::$PHONE_UNIQUE,  null ,Message::$PHONE_UNIQUE_EN);


        try {
            $doctor = new Doctor;
            $doctor->name = $request->name;
            $doctor->name_ar = $request->name_ar;
            $doctor->name_fr = $request->name_fr;
            $doctor->phone = $request->phone;
            $doctor->email = $request->email;
            $doctor->password = bcrypt($request->password);
            $doctor->specialization_id = $request->specialization_id;
            $doctor->degree_id = $request->degree_id;
            $doctor->reservation_rate = $request->reservation_rate;
            $doctor->degree_rate = $request->degree_rate;
            $doctor->active =  $request->active;
            $doctor->gender =  $request->gender;

            // upload image
            if ($request->hasFile('image')) {
                $doctor->photo = Helper::uploadImg($request->file("image"), "/doctor/");
            }

            // upload cv
            if ($request->hasFile('cv')) {
                $doctor->cv = Helper::uploadFile($request->file("cv"), "/doctor/");
            }

            // upload cv
            if ($request->hasFile('cv2')) {
                // remove old cv2
                $doctor->cv2 = Helper::uploadFile($request->file("cv2"), "/doctor/");
            }

            $doctor->save();

            foreach ($insurances as $insurance) {
                $d = new DoctorInsurance();
                $d->doctor_id = $doctor->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

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
        return view('doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor) {
        return $doctor->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor) {
        $insurances = explode(',', $request->insurance_id);
        try {
            $doctor->name = $request->name;
            $doctor->name_ar = $request->name_ar;
            $doctor->name_fr = $request->name_fr;
            $doctor->phone = $request->phone;
            $doctor->email = $request->email;
            $doctor->specialization_id = $request->specialization_id;
            $doctor->degree_id = $request->degree_id;
            $doctor->reservation_rate = $request->reservation_rate;
            $doctor->degree_rate = $request->degree_rate;
            $doctor->active =  $request->active;
            $doctor->gender =  $request->gender;

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

            // upload cv
            if ($request->hasFile('cv2')) {
                // remove old cv2
                Helper::removeFile(public_path() . '/file/doctor/' . $doctor->cv2);
                $doctor->cv2 = Helper::uploadFile($request->file("cv2"), "/doctor/");
            }
            $doctor->update();

            $doctor->doctor_insurances()->delete();
            foreach ($insurances as $insurance) {
                $d = new DoctorInsurance();
                $d->doctor_id = $doctor->id;
                $d->insurance_id = $insurance;
                $d->save();
            }

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
            return Message::redirect("doctor/", Message::$DELETE_ERROR, 0);
        }

        try {
            $doctor->delete();
            return Message::redirect("doctor/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("doctor/", Message::$ERROR, 0);
        }
    }

}
