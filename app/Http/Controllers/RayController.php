<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ray;
use App\Message;
use Yajra\DataTables\DataTables;

class RayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("ray.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() {
        $rays = Ray::all();
        //return Datatables::of($rays)->make(true);
        return DataTables::of($rays)
                        ->addColumn('action', function(Ray $ray) {
                            $delete = route("rayDelete", ["ray" => $ray->id]);
                            $update = route("rayUpdate", ["ray" => $ray->id]);
                            return view("ray.action", compact("delete", "update"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("ray.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            Ray::create($request->all());
            return Message::redirect("ray/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("ray/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ray $ray) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ray $ray) {
        $obj = $ray;
        return view("ray.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ray $ray) {
        try {
            $ray->update($request->all());
            return Message::redirect("ray/", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("ray/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ray $ray) {
        try {
            $ray->delete();
            return Message::redirect("ray/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("ray/", Message::$ERROR, 0);
        }
    }

}
