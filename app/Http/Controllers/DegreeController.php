<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Degree;
use App\Message;
use Yajra\DataTables\DataTables;

class DegreeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("degree.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() {
        $degrees = Degree::all();
        //return Datatables::of($degrees)->make(true);
        return DataTables::of($degrees)
                        ->addColumn('action', function(Degree $degree) {
                            $delete = route("degreeDelete", ["degree" => $degree->id]);
                            $update = route("degreeUpdate", ["degree" => $degree->id]);
                            return view("degree.action", compact("delete", "update"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("degree.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            Degree::create($request->all());
            
            return Message::redirect("degree/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("degree/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Degree $degree) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Degree $degree) {
        $obj = $degree;
        return view("degree.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Degree $degree) {
        try {
            $degree->update($request->all());
            
            return Message::redirect("degree/", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("degree/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Degree $degree) {
        try {
            $degree->delete();
            return Message::redirect("degree/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("degree/", Message::$ERROR, 0);
        }
    }

}
