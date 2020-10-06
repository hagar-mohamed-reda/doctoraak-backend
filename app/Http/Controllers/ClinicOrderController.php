<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClinicOrder;
use App\Message;
use App\Clinic;
use Yajra\DataTables\DataTables;

class ClinicOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("clinicOrder.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        $query = ClinicOrder::query();
        
        
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('notes', 'like', '%'.request()->search_string.'%')
                    ->orWhere('reservation_number', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->doctor_id > 0) {
            $clinicIds = Clinic::where('doctor_id', request()->doctor_id)->get('id')->pluck('id')->toArray();
            $query->whereIn('clinic_id', $clinicIds);
        }
        
        if (request()->patient_id > 0) {
            $query->where('patient_id', request()->patient_id);
        }
        
        if (request()->part_id > 0) {
            $query->where('part_id', request()->part_id);
        }
        
        if (request()->reservation_number) {
            $query->where('reservation_number', request()->reservation_number);
        }
          
        if (request()->date_from && request()->date_to) {
            $query->whereBetween('date', [request()->date_from, request()->date_to]);
        }
        
        if (request()->type) {
            $query->where('type', request()->type);
        } 
        
        if (request()->active == 1) {
            $query->where('clinic_orders.active', "1");
        }
        
        if (request()->active == 2) {
            $query->where('clinic_orders.active', "0");
        }
         
        return DataTables::of($query)
                        ->addColumn('action', function(ClinicOrder $clinicOrder) {
                            return view("clinicOrder.action", compact("clinicOrder"));
                        })
                        ->addColumn('patient_id', function(ClinicOrder $clinicOrder) {
                            return optional($clinicOrder->patient)->name;
                        })
                        ->addColumn('doctor', function(ClinicOrder $clinicOrder) {
                            return optional(optional($clinicOrder->clinic)->doctor)->name;
                        })
                        ->editColumn('type', function(ClinicOrder $clinicOrder) {
                            return $clinicOrder->getReservationTypeAr();
                        })
                        ->editColumn('active', function(ClinicOrder $clinicOrder) {
                            $label = ($clinicOrder->active == 1) ? "success" : "danger";
                            $text = ($clinicOrder->active == 1) ? "نشط" : "غير نشط";

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->editColumn('notes', function(ClinicOrder $clinicOrder) {
                            return str_replace(",", "<br>", $clinicOrder->notes);
                        })
                        ->rawColumns(['action', 'active', 'notes'])
                        ->make(true);
    }

    /**
     *
     * @param ClinicOrder $clinicOrder
     * @return type
     */
    public function active(ClinicOrder $clinicorder) {
        try {
            $clinicorder->active = 1;
            $clinicorder->update();
            return Message::redirect("clinicorder", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("clinicorder", Message::$ERROR, 0);
        }
    }


    /**
     *
     */
    public function deactive(ClinicOrder $clinicorder) {
        try {
            $clinicorder->active = 0;
            $clinicorder->update();
            return Message::redirect("clinicorder", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("clinicorder", Message::$ERROR, 0);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('clinicOrder.create');
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
    public function show(ClinicOrder $clinicOrder) {
        // no code here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicOrder $clinicOrder) {
        // no code here
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClinicOrder $clinicOrder) {
        // no code here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicOrder $clinicOrder) {
        // no code here
    }

}
