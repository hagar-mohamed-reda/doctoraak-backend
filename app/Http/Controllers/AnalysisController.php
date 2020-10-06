<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Analysis;
use App\Message;
use App\helper\Helper;
use Yajra\DataTables\DataTables;

class AnalysisController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("analysis.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {
        $analysiss = Analysis::all();
        //return Datatables::of($analysiss)->make(true);
        return DataTables::of($analysiss)
                        ->addColumn('action', function(Analysis $analysis) {
                            $delete = route("analysisDelete", ["analysis" => $analysis->id]);
                            $update = route("analysisUpdate", ["analysis" => $analysis->id]);
                            return view("analysis.action", compact("delete", "update"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("analysis.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            Analysis::create($request->all());

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
    public function show(Analysis $analysis) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Analysis $analysis) {
        $obj = $analysis;
        return view("analysis.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Analysis $analysis) {
        try {
            $analysis->update($request->all());
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
    public function destroy(Analysis $analysis) {
        try {
            $analysis->delete();
            return Helper::responseJson(1, Message::$REMOVE);
        } catch (\Exception $ex) {
            return Helper::responseJson(0, Message::$ERROR);
        }
    }

}
