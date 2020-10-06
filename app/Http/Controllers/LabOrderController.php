<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LabOrder;
use App\Message;
use Yajra\DataTables\DataTables;
use DB;

class LabOrderController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("labOrder.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() { 
        $query = LabOrder::query();
        
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('code', 'like', '%'.request()->search_string.'%')
                    ->orWhere('insurance_accept', 'like', '%'.request()->search_string.'%')
                    ->orWhere('insurance_code', 'like', '%'.request()->search_string.'%') 
                    ->orWhere('created_at', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->patient_id > 0) {
            $query->where('patient_id', request()->patient_id);
        }
        
        if (request()->lab_id > 0) {
            $query->where('lab_id', request()->lab_id);
        }
         
        return DataTables::of($query)
                        ->addColumn('action', function(LabOrder $laborder) {
                            return view("labOrder.action", compact("laborder"));
                        }) 
                        ->addColumn('patient_id', function(LabOrder $laborder) {
                            return optional($laborder->patient)->name;
                        })
                        ->addColumn('lab_id', function(LabOrder $laborder) {
                            return optional($laborder->lab)->name;
                        })  
                        ->rawColumns(['action'])
                        ->make(true);
    }

    /**
     * 
     * @param LabOrder $labOrder
     * @return type
     */
    public function active(LabOrder $laborder) {
        // no code here
    }

    /**
     * 
     */
    public function deactive(LabOrder $laborder) {
        // no code here
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // no code here
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // no code here
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LabOrder $laborder) {
        return view("labOrder.show", compact("laborder"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LabOrder $labOrder) {
        // no code here
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LabOrder $labOrder) {
        // no code here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LabOrder $laborder) {
        try {
            DB::statement(" delete from lab_order_details where lab_order='$laborder->id' ");
            $laborder->delete();
            return Message::redirect("laborder", Message::$REMOVE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("laborder", Message::$ERROR, 0);
        }
    }

}
