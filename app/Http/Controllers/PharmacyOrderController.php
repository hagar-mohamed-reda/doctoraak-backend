<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PharmacyOrder;
use App\PharmacyRequest;
use App\Message;
use Yajra\DataTables\DataTables;
use DB;

class PharmacyOrderController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("pharmacyOrder.index");
    }

    /**
     * 
     * @return ajax data
     */
    public function getData() { 
        $query = PharmacyOrder::query();
        
        
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
        
        if (request()->pharmacy_id > 0) {
            $query->where('pharmacy_id', request()->pharmacy_id);
        }
         
        return DataTables::of($query)
                        ->addColumn('action', function(PharmacyOrder $pharmacyorder) {
                            return view("pharmacyOrder.action", compact("pharmacyorder"));
                        }) 
                        ->addColumn('patient_id', function(PharmacyOrder $pharmacyorder) {
                            return optional($pharmacyorder->patient)->name;
                        })
                        ->addColumn('pharmacy_id', function(PharmacyOrder $pharmacyorder) {
                            return optional($pharmacyorder->pharmacy)->name;
                        })  
                        ->rawColumns(['action'])
                        ->make(true);
    }

    /**
     * 
     * @param PharmacyOrder $pharmacyOrder
     * @return type
     */
    public function active(PharmacyOrder $pharmacyorder) {
        // no code here
    }

    /**
     * 
     */
    public function deactive(PharmacyOrder $pharmacyorder) {
        // no code here
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PharmacyOrder $pharmacyorder) {
        return view('pharmacyOrder.show', compact('pharmacyorder'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PharmacyOrder $pharmacyOrder) {
        // no code here
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PharmacyOrder $pharmacyOrder) {
        // no code here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PharmacyRequest $pharmacyorder) {
        try {
            DB::statement(" delete from pharmacy_order_details where pharmacy_order='".$pharmacyorder->pharmacy_order->id."' ");
            $pharmacyorder->delete();
            return Message::redirect("pharmacyorder", Message::$REMOVE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("pharmacyorder", Message::$ERROR, 0);
        }
    }

}
