<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MedicineType;
use App\Message;
use Yajra\DataTables\DataTables;

class MedicineTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("medicinetype.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() {
        $medicinetypes = MedicineType::all();
        //return Datatables::of($medicinetypes)->make(true);
        return DataTables::of($medicinetypes)
                        ->addColumn('action', function(MedicineType $medicinetype) {
                            $delete = route("medicinetypeDelete", ["medicinetype" => $medicinetype->id]);
                            $update = route("medicinetypeUpdate", ["medicinetype" => $medicinetype->id]);
                            return view("medicinetype.action", compact("delete", "update"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("medicinetype.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            MedicineType::create($request->all());
            
            return Message::redirect("medicinetype/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("medicinetype/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MedicineType $medicinetype) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicineType $medicinetype) {
        $obj = $medicinetype;
        return view("medicinetype.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicineType $medicinetype) {
        try {
            $medicinetype->update($request->all());
            
            return Message::redirect("medicinetype/", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("medicinetype/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicineType $medicinetype) {
        try {
            $medicinetype->delete();
            return Message::redirect("medicinetype/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("medicinetype/", Message::$ERROR, 0);
        }
    }

}
