<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clinic;
use App\Message;
use Yajra\DataTables\DataTables;

use App\City;
use App\Area;

class ClinicController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("clinic.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {
        $query = Clinic::query();
        
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('available_days', 'like', '%'.request()->search_string.'%')
                    ->orWhere('waiting_time', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('fees', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('fees2', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('phone', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->doctor_id > 0) {
            $query->where('doctor_id', request()->doctor_id);
        }
        
        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }
        
        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }
          
        if (request()->fees) {
            $query->where('fees', request()->fees);
        }
        
        if (request()->waiting_time) {
            $query->where('waiting_time', request()->waiting_time);
        }
         
        if (request()->fees2) {
            $query->where('fees2', request()->fees2);
        }
        
        if (request()->active == 1) {
            $query->where('active', "1");
        }
        
        if (request()->active == 2) {
            $query->where('active', "0");
        }
        
        if (request()->availability == 1) {
            $query->where('availability', "1");
        }
        
        if (request()->availability == 2) {
            $query->where('availability', "0");
        }
        
        
        
        return DataTables::of($query->latest())
                        ->addColumn('action', function(Clinic $clinic) {
                            return view("clinic.action", compact("clinic"));
                        })
                        ->editColumn('doctor_id', function(Clinic $clinic) {
                            return optional($clinic->doctor)->name;
                        })
                        ->editColumn('availability', function(Clinic $clinic) {
                            $label = ($clinic->availability == 1) ? "success" : "danger";
                            $text = ($clinic->availability == 1) ? "يعمل اليوم" : "لا يعمل";

                            $html = "<span class='w3-padding' ><span class='label label-$label fa fa-circle'  > </span></span>";
                            return $html;
                        })
                        ->editColumn('photo', function(Clinic $clinic) {
                            
                            return "<img class='w3-circle' src='" . url('/image/clinic') . "/" . $clinic->photo . "' width='50px' height='50px' onclick='viewImage(this)'  >";
                        })
                        ->addColumn('address', function(Clinic $clinic) {
                            return optional($clinic->city()->first())->name . '-' . 
                                   optional($clinic->area()->first())->name;
                        })
                        
                        ->editColumn('active', function(Clinic $clinic) {
                            $label = ($clinic->active == 1) ? "success" : "danger";
                            $text = ($clinic->active == 1) ? "نشط" : "غير نشط";
                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->rawColumns(['action', 'photo', 'address', 'active', 'availability'])
                        ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function map() {
        $query = Clinic::query();
        
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('available_days', 'like', '%'.request()->search_string.'%')
                    ->orWhere('waiting_time', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('fees', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('fees2', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('phone', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->doctor_id > 0) {
            $query->where('doctor_id', request()->doctor_id);
        }
        
        if (request()->city_id > 0) {
            $query->where('city_id', request()->city_id);
        }
        
        if (request()->area_id) {
            $query->where('area_id', request()->area_id);
        }
          
        if (request()->fees) {
            $query->where('fees', request()->fees);
        }
        
        if (request()->waiting_time) {
            $query->where('waiting_time', request()->waiting_time);
        }
         
        if (request()->fees2) {
            $query->where('fees2', request()->fees2);
        }
        
        if (request()->active == 1) {
            $query->where('active', "1");
        }
        
        if (request()->active == 2) {
            $query->where('active', "0");
        }
        
        if (request()->availability == 1) {
            $query->where('availability', "1");
        }
        
        if (request()->availability == 2) {
            $query->where('availability', "0");
        }
        
        $resources = $query->get();
        return view('clinic.map', compact('resources'));
    }
    /**
     *
     * @param Clinic $clinic
     * @return type
     */
    public function active(Clinic $clinic) {
        try {
            $clinic->active = 1;
            $clinic->update();
            return Message::redirect("clinic", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("clinic", Message::$ERROR, 0);
        }
    }


    /**
     *
     */
    public function deactive(Clinic $clinic) {
        try {
            $clinic->active = 0;
            $clinic->update();
            return Message::redirect("clinic", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("clinic", Message::$ERROR, 0);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // no code here
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
        $validator = validator()->make($request->all(), [
            'fees' => 'required', 
            'fees2' => 'required', 
            'waiting_time' => 'required', 
            'lat' => 'required', 
            'lng' => 'required', 
        ], [
            'fees.required' => __('fees is required'),
            'fees2.required' => __('fees2 is required'),
            'waiting_time.required' => __('waiting_time is required'),
            'lat.required' => __('location is required'),
            'lng.required' => __('location is required'),
        ]);
         
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
          
        try {
            $data = $request->all(); 
            
            $clinic = Clinic::create($data);

            // upload image
            if ($request->hasFile('photo')) {
                $clinic->photo = Helper::uploadImg($request->file("photo"), "/clinic/");
            }
 
            $clinic->save();
            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Clinic $clinic) {
        return view('clinic.show', compact('clinic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Clinic $clinic) {
        return $clinic->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clinic $clinic) {
        $validator = validator()->make($request->all(), [
            'fees' => 'required', 
            'fees2' => 'required', 
            'waiting_time' => 'required',  
        ], [
            'fees.required' => __('fees is required'),
            'fees2.required' => __('fees2 is required'),
            'waiting_time.required' => __('waiting_time is required'), 
        ]);
         
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
          
        try {
            $data = $request->all();  
            // upload image
            if ($request->hasFile('photo')) {
                $clinic->photo = Helper::uploadImg($request->file("photo"), "/clinic/");
            }
 
            $clinic->update($data); 
            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clinic $clinic) {  
        if ($clinic->orders()->count() > 0) { 
            return Message::error(Message::$DELETE_ERROR);
        }
        
        try {
            $clinic->working_hours()->delete();
            $clinic->delete();
            return Message::redirect("doctor/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("doctor/", $ex->getMessage(), 0);
        }
    }

}
