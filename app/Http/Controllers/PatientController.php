<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Message;
use App\helper\Helper;
use DataTables;

class PatientController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("patient.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        $query = Patient::query();
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('address', 'like', '%'.request()->search_string.'%')
                    ->orWhere('address_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('address_fr', 'like', '%'.request()->search_string.'%')
                    ->orWhere('email', 'like', '%'.request()->search_string.'%')
                    ->orWhere('phone', 'like', '%'.request()->search_string.'%')
                    ->orWhere('insurance_code_id', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->insurance_id > 0) {
            $query->where('insurance_id', request()->insurance_id);
        }
        
        if (request()->gender) {
            $query->where('gender', request()->gender);
        }
        
        if (request()->active == 1) {
            $query->where('active', "1");
        }
        
        if (request()->active == 2) {
            $query->where('active', "0");
        }
        
        
        return DataTables::eloquent($query)
                        ->addColumn('action', function(Patient $patient) {
                            return view("patient.action", compact("patient"));
                        })
                        ->editColumn('name', function(Patient $patient) {
                            return "<a href='#' class='w3-text-blue' onclick='showPage(\"patient/show/".$patient->id." \")' >" . $patient->name . "</a>";
                        })
                        ->editColumn('insurance_id', function(Patient $patient) {
                            return optional($patient->insurance)->name;
                        })
                        ->editColumn('photo', function(Patient $patient) {
                            return "<img src='" . $patient->url . "' class='w3-circle' height='40px' width='40px' onclick='viewImage(this)'  >";
                        })
                        ->addColumn('insurance_status', function(Patient $patient) {
                            return $patient->insurance_code_id? __('has_insurance') : __('normal');
                        })
                        ->editColumn('active', function(Patient $patient) {
                            $label = ($patient->active == 1) ? "success" : "danger";
                            $text = ($patient->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->rawColumns(['action', 'photo', 'active', 'name'])
                        ->toJson();
    }

    /**
     *
     * @param Patient $patient
     * @return type
     */
    public function active(Patient $patient) {
        try {
            $patient->active = 1;
            $patient->update(); 
            
            return Helper::responseJson(1, Message::$EDIT); 
        } catch (\Exception $ex) { 
            return Helper::responseJson(0, Message::$ERROR);
        }
    }


    /**
     *
     */
    public function deactive(Patient $patient) {
        try {
            $patient->active = 0;
            $patient->update();
            return Helper::responseJson(1, Message::$EDIT); 
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
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
        try {
            $patient = Patient::create($request->all());
            $patient->update([
                'password' => bcrypt($request->password),
            ]);
            
            return Helper::responseJson(1, Message::$DONE); 
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient) {
        try {
            $patient->update($request->all());
            
            if ($patient->password != $request->password)
                $patient->update([
                    'password' => bcrypt($request->password),
                ]);
                
            
            return Helper::responseJson(1, Message::$DONE); 
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient) {
        return view('patient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient) {
        return $patient->getViewBuilder()->loadEditView();
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient) {
        // no code here
    }

}
