<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PharmacyDoctor;
use App\Message;
use Yajra\DataTables\DataTables;

class PharmacyDoctorController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("pharmacydoctor.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {
        $query = PharmacyDoctor::query();
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('username', 'like', '%'.request()->search_string.'%');
        }
        
        return DataTables::of($query)
                        ->addColumn('action', function(PharmacyDoctor $pharmacydoctor) {
                            return view("pharmacydoctor.action", compact("pharmacydoctor"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("pharmacydoctor.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (PharmacyDoctor::where("username", $request->username)->count() > 0)
            return Message::redirect("", Message::$USERNAME_UNIQUE, 0);
        try {
            PharmacyDoctor::create($request->all());

            return Message::redirect("pharmacydoctor/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("pharmacydoctor/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PharmacyDoctor $pharmacydoctor) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PharmacyDoctor $pharmacydoctor) {
        return $pharmacydoctor->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PharmacyDoctor $pharmacydoctor) {
        if (PharmacyDoctor::where("username", $request->username)->count() > 0 && $pharmacydoctor->username != $request->username)
            return Message::redirect("", Message::$USERNAME_UNIQUE, 0);
        try {
            $pharmacydoctor->update($request->all());
            return Message::redirect("pharmacydoctor/", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("pharmacydoctor/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PharmacyDoctor $pharmacydoctor) {
        if ($pharmacydoctor->pharmacys()->count() > 0)
            return Message::redirect("pharmacydoctor/", Message::$DELETE_ERROR, 0);
        try {
            $pharmacydoctor->delete();
            return Message::redirect("pharmacydoctor/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("pharmacydoctor/", Message::$ERROR, 0);
        }
    }

}
