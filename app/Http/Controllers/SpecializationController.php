<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialization;
use App\Message;
use App\helper\Helper;
use Yajra\DataTables\DataTables;

class SpecializationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("specialization.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() { 
        return DataTables::of(Specialization::query())
                        ->addColumn('action', function(Specialization $specialization) {
                            $delete = route("specializationDelete", ["specialization" => $specialization->id]);
                            $update = route("specializationUpdate", ["specialization" => $specialization->id]);
                            return view("specialization.action", compact("delete", "update"));
                        })->addColumn('file', function(Specialization $specialization) {
                            $icon = $specialization->icon;
                            return view("specialization.img", compact("icon"));
                        })->rawColumns(['action', 'file'])
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("specialization.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $specialization = new Specialization;
            $specialization->name = $request->name;
            $specialization->name_ar = $request->name_ar;
            $specialization->name_fr = $request->name_fr;

            if ($request->hasFile('image')) {
                $specialization->icon = Helper::uploadImg($request->file("image"), "/special/");
            }

            $specialization->save();
            return Message::redirect("specialization/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("specialization/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Specialization $specialization) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Specialization $specialization) {
        $obj = $specialization;
        return view("specialization.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specialization $specialization) {
        try {
            $specialization->name = $request->name;
            $specialization->name_ar = $request->name_ar;
            $specialization->name_fr = $request->name_fr;

            if ($request->hasFile('image')) {
                try {
                    unlink(public_path('/') . "/image/special/" . $specialization->icon);
                } catch (\Exception $e) {
                    
                }

                $specialization->icon = Helper::uploadImg($request->file("image"), "/special/");
            }

            $specialization->update();
            return Message::redirect("specialization", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("specialization", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specialization $specialization) {
        try { 
            try {
                unlink(public_path() . "/image/special/" . $specialization->icon);
            } catch (\Exception $e) {
                
            }
            $specialization->delete();
            return Message::redirect("specialization/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("specialization/", Message::$ERROR, 0);
        }
    }

}
