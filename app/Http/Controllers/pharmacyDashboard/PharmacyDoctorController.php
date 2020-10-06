<?php

namespace App\Http\Controllers\pharmacyDashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PharmacyDoctor;
use App\Pharmacy;
use App\Message;
use App\PharmacyRequest;
use App\PharmacyOrder;
use Yajra\DataTables\DataTables;

class PharmacyDoctorController extends Controller
{

    public function index() {
        $id = session("doctor");
        if ($id == null)
            return redirectTo("pharmacydoctordashboard/login");

        $user = PharmacyDoctor::find($id);
        return view("pharmacyUserDashboard.index", compact("user"));
    }

    public function login() {
        return view("pharmacyUserDashboard.login");
    }

    public function logout() {
        session(["doctor" => null]);
        return Message::redirectTo("pharmacydoctordashboard/login", "", 0);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PharmacyOrder $pharmacyorder) {
         return view("pharmacyUserDashboard.show", compact("pharmacyorder"));
    }
    
    
    public function sign(Request $request) {
        try {
            $users = PharmacyDoctor::where("username", $request->email)->where("password", $request->password)->get();

            if (count($users) > 0) {
                $user = $users[0];
                session(["doctor" => $user->id]);

                return Message::redirectTo("pharmacydoctordashboard", Message::$SUCCESS_LOGIN, 1);
            } else {
                return Message::redirectTo("pharmacydoctordashboard/login", Message::$LOGIN_ERROR, 0);
            }
        } catch (\Exception $e) {
            return Message::redirectTo("pharmacydoctordashboard/login", Message::$LOGIN_ERROR, 0);
        }
    }

    /**
     *
     * @return ajax data
     */
    public function getData() {
        $id = session("doctor");
        if ($id == null)
            return;
        $pharmacyOrders = PharmacyOrder::join("pharmacies", "pharmacies.id", "=", "pharmacy_id")
                ->where("pharmacy_doctor_id", session("doctor"))
                ->select('*', "pharmacy_orders.id as code");

        return DataTables::of($pharmacyOrders)
                        ->addColumn('action', function(PharmacyOrder $pharmacyOrder) {
                            $pharmacyOrder->id = $pharmacyOrder->code;
                            return view("pharmacyUserDashboard.action", compact("pharmacyOrder"));
                        })
                        ->editColumn('insurance_accept', function(PharmacyOrder $pharmacyOrder) {
                            if ($pharmacyOrder->insurance_accept == 'accepted') {
                                $label = "success";
                                $text = "موافقه";
                            } else if ($pharmacyOrder->insurance_accept == 'required') {
                                $label = "primary";
                                $text = "مطلوب";
                            } else if ($pharmacyOrder->insurance_accept == 'refused') {
                                $label = "danger";
                                $text = "مرفوض";
                            } else {
                                $label = "default";
                                $text = "غير متاحه";
                            }

                            return "<span class='label label-$label' >$text</span>";
                        })
                        ->addColumn('patient', function(PharmacyOrder $pharmacyOrder) {
                            return ($pharmacyOrder->patient) ? $pharmacyOrder->patient->name : '';
                        })
                        ->addColumn('pharmacy', function(PharmacyOrder $pharmacyOrder) {
                            return ($pharmacyOrder->pharmacy) ? $pharmacyOrder->pharmacy->name : '';
                        })
                        ->addColumn('image', function(PharmacyOrder $pharmacyOrder) {
                            return "<img src='" . url('/image/pharmacyorder') . "/" . $pharmacyOrder->photo . "' height='40px' onclick='viewImage(this)'  >";
                        })
                        ->rawColumns(['action', 'image', 'insurance_accept'])
                        ->make(true);
    }
}
