<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Message;
use Yajra\DataTables\DataTables;


class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("city.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() {
        $citys = City::all();
        //return Datatables::of($citys)->make(true);
        return DataTables::of($citys)
                        ->addColumn('action', function(City $city) {
                            $delete = route("cityDelete", ["city" => $city->id]);
                            $update = route("cityUpdate", ["city" => $city->id]);
                            return view("city.action", compact("delete", "update"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("city.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            City::create($request->all());
            
            return Message::redirect("city/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("city/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city) {
        $obj = $city;
        return view("city.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city) {
        try {
            $city->update($request->all());
            
            return Message::redirect("city/", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("city/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city) {
        try {
            $city->delete();
            return Message::redirect("city/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("city/", Message::$ERROR, 0);
        }
    }

}
