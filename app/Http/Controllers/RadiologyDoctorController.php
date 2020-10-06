<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RadiologyDoctor;
use App\Message;
use Yajra\DataTables\DataTables;

class RadiologyDoctorController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("radiologydoctor.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        return DataTables::of(RadiologyDoctor::query())
                        ->addColumn('action', function(RadiologyDoctor $radiologydoctor) {
                            return view("radiologydoctor.action", compact("radiologydoctor"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("radiologydoctor.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (RadiologyDoctor::where("username", $request->username)->count() > 0)
            return Message::redirect("", Message::$USERNAME_UNIQUE, 0);

        try {
            RadiologyDoctor::create($request->all());

            return Message::redirect("radiologydoctor/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("radiologydoctor/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RadiologyDoctor $radiologydoctor) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RadiologyDoctor $radiologydoctor) { 
        return $radiologydoctor->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RadiologyDoctor $radiologydoctor) {
        if (RadiologyDoctor::where("username", $request->username)->count() > 0 && $radiologydoctor->username != $request->username)
            return Message::redirect("", Message::$USERNAME_UNIQUE, 0);

        try {
            $radiologydoctor->update($request->all());

            return Message::redirect("radiologydoctor/", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("radiologydoctor/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RadiologyDoctor $radiologydoctor) {
        if ($radiologydoctor->radiologys()->count() > 0)
            return Message::redirect("radiologydoctor/", Message::$DELETE_ERROR, 0);
        try {
            $radiologydoctor->delete();
            return Message::redirect("radiologydoctor/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("radiologydoctor/", Message::$ERROR, 0);
        }
    }

}
