<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Message;
use App\helper\Helper;
use Yajra\DataTables\DataTables;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("area.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        return DataTables::of(Area::query())
                        ->addColumn('action', function(Area $area) {
                            $delete = route("areaDelete", ["area" => $area->id]);
                            $update = route("areaUpdate", ["area" => $area->id]);
                            return view("area.action", compact("delete", "update"));
                        })->editColumn('city_id', function(Area $area) {
                            return ($area->city)? $area->city->name : '';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("area.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            Area::create($request->all());

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
    public function show(Area $area) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area) {
        $obj = $area;
        return view("area.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area) {
        try {
            $area->update($request->all());
            return Helper::responseJson(1, Message::$EDIT);
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area) {
        try {
            $area->delete();
            return Helper::responseJson(1, Message::$REMOVE);
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
        }
    }

}
