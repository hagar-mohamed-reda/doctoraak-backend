<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medicine;
use App\Message;
use Yajra\DataTables\DataTables;

class MedicineController extends Controller
{ /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("medicine.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() {
        $medicines = Medicine::all();
        //return Datatables::of($medicines)->make(true);
        return DataTables::of($medicines)
                        ->addColumn('action', function(Medicine $medicine) {
                            $delete = route("medicineDelete", ["medicine" => $medicine->id]);
                            $update = route("medicineUpdate", ["medicine" => $medicine->id]);
                            return view("medicine.action", compact("delete", "update"));
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("medicine.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            Medicine::create($request->all());
            
            return Message::redirect("medicine/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("medicine/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Medicine $medicine) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Medicine $medicine) {
        $obj = $medicine;
        return view("medicine.edit", compact("obj"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicine $medicine) {
        try {
            $medicine->update($request->all());
            
            return Message::redirect("medicine/", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("medicine/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicine $medicine) {
        try {
            $medicine->delete();
            return Message::redirect("medicine/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("medicine/", Message::$ERROR, 0);
        }
    }

}
