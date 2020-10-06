<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Insurance;
use App\helper\Helper;
use App\Message;
use DataTables;

class InsuranceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("insurance.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() {  
        $query = Insurance::query();
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%');
        }
        
        
        return DataTables::eloquent($query)
                        ->addColumn('action', function(Insurance $insurance) { 
                            return view("insurance.action", compact("insurance"));
                        }) 
                        ->editColumn('photo', function(Insurance $insurance) {
                            return "<img src='" . $insurance->url . "' class='w3-circle ".Helper::randColor()."' height='40px' width='40px' onclick='viewImage(this)'  >";
                        })
                        ->rawColumns(['action', 'photo'])
                        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("insurance.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $insurance = new Insurance;
            if ($request->hasFile('photo')) {
                $insurance->photo = Helper::uploadImg($request->file("photo"), "/insurance/");
            }

            $insurance->name = $request->name;
            $insurance->name_ar = $request->name_ar;
            $insurance->name_fr = $request->name_fr;

            $insurance->save();
            return Message::redirect("insurance/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("insurance/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Insurance $insurance) {
        return view('insurance.show', compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Insurance $insurance) {
        return $insurance->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Insurance $insurance) {
        try {
            if ($request->hasFile('photo')) {
                Helper::removeFile(public_path("/image/insurance/" . $insurance->photo));
                $insurance->photo = Helper::uploadImg($request->file("photo"), "/insurance/");
            }

            $insurance->name = $request->name;
            $insurance->name_ar = $request->name_ar;
            $insurance->name_fr = $request->name_fr;

            $insurance->update();
            return Message::redirect("insurance/", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("insurance/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Insurance $insurance) {
        if (    $insurance->patients->count() > 0 ||
                $insurance->doctor_insurances->count() > 0 ||
                $insurance->user_insurances->count() > 0 ||
                $insurance->pharmacy_insurances->count() > 0 ||
                $insurance->lab_insurances->count() > 0 ||
                $insurance->radiology_insurances->count() > 0 )
            return Message::redirect("insurance/", Message::$DELETE_ERROR, 0);
        
        
        try {
            Helper::removeFile(public_path("/image/insurance/" . $insurance->photo));
            $insurance->delete();
            return Message::redirect("insurance/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("insurance/", Message::$ERROR, 0);
        }
    }

}
