<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RadiologyOrder;
use App\Message;
use Yajra\DataTables\DataTables;
use DB;


class RadiologyOrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("radiologyOrder.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        $query = RadiologyOrder::query();
        
        
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
        
        if (request()->radiology_id > 0) {
            $query->where('radiology_id', request()->radiology_id);
        }
         
        return DataTables::of($query)
                        ->addColumn('action', function(RadiologyOrder $radiologyorder) {
                            return view("radiologyOrder.action", compact("radiologyorder"));
                        }) 
                        ->addColumn('patient_id', function(RadiologyOrder $radiologyorder) {
                            return optional($radiologyorder->patient)->name;
                        })
                        ->addColumn('radiology_id', function(RadiologyOrder $radiologyorder) {
                            return optional($radiologyorder->radiology)->name;
                        })  
                        ->rawColumns(['action'])
                        ->make(true);
    }

    /**
     *
     * @param RadiologyOrder $radiologyOrder
     * @return type
     */
    public function active(RadiologyOrder $radiologyorder) {
        // no code here
    }

    /**
     *
     */
    public function deactive(RadiologyOrder $radiologyorder) {
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
    public function show(RadiologyOrder $radiologyorder) {
        return view("radiologyOrder.show", compact("radiologyorder"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RadiologyOrder $radiologyOrder) {
        // no code here
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RadiologyOrder $radiologyOrder) {
        // no code here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RadiologyOrder $radiologyorder) {
        try {
            DB::statement(" delete from radiology_order_details where radiology_order='$radiologyorder->id' ");
            $radiologyorder->delete();
            return Message::redirect("radiologyorder", Message::$REMOVE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("radiologyorder", Message::$ERROR, 0);
        }
    }

}
