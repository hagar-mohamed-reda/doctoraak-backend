<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LabDoctor;
use App\Message;
use Yajra\DataTables\DataTables;

class LabDoctorController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("labdoctor.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        return DataTables::of(LabDoctor::query())
                        ->addColumn('action', function(LabDoctor $labdoctor) {
                            return view("labdoctor.action", compact("labdoctor"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("labdoctor.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (LabDoctor::where("username", $request->username)->count() > 0)
            return Message::redirect("", Message::$USERNAME_UNIQUE, 0);

        try {
            LabDoctor::create($request->all());

            return Message::redirect("labdoctor/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("labdoctor/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LabDoctor $labdoctor) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LabDoctor $labdoctor) { 
        return $labdoctor->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LabDoctor $labdoctor) {
        if (LabDoctor::where("username", $request->username)->count() > 0 && $labdoctor->username != $request->username)
            return Message::redirect("", Message::$USERNAME_UNIQUE, 0);

        try {
            $labdoctor->update($request->all());

            return Message::redirect("labdoctor/", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("labdoctor/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LabDoctor $labdoctor) {
        if ($labdoctor->labs()->count() > 0)
            return Message::redirect("labdoctor/", Message::$DELETE_ERROR, 0);
        try {
            $labdoctor->delete();
            return Message::redirect("labdoctor/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("labdoctor/", Message::$ERROR, 0);
        }
    }

}
