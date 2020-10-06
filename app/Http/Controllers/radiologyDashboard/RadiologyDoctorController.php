<?php

namespace App\Http\Controllers\radiologyDashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\RadiologyDoctor;
use App\Radiology;
use App\Message;
use App\RadiologyOrder;
use Yajra\DataTables\DataTables;

class RadiologyDoctorController extends Controller
{
    public function index() {
        $id = session("doctor");
        if ($id == null)
            return redirectTo("radiologydoctordashboard/login");

        $user = RadiologyDoctor::find($id);
        return view("radiologyUserDashboard.index", compact("user"));
    }

    public function login() {
        return view("radiologyUserDashboard.login");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RadiologyOrder $radiologyorder) {
        return view("radiologyUserDashboard.show", compact("radiologyorder"));
    }
    
    public function logout() {
        session(["doctor" => null]);
        return Message::redirectTo("radiologydoctordashboard/login", "", 0);
    }

    public function sign(Request $request) {
        try{
            $users = RadiologyDoctor::where("username", $request->email)->where("password", $request->password)->get();

            if (count($users) > 0) {
                $user = $users[0];
                session(["doctor" => $user->id]);

                return Message::redirectTo("radiologydoctordashboard", Message::$SUCCESS_LOGIN, 1);
            } else {
                return Message::redirectTo("radiologydoctordashboard/login", Message::$LOGIN_ERROR, 0);
            }
        }catch(\Exception $e) {
            return Message::redirectTo("radiologydoctordashboard/login", Message::$LOGIN_ERROR, 0);
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
            $radiologyOrders = RadiologyOrder::join("radiologies", "radiologies.id", "=", "radiology_id")->where("radiology_doctor_id", session("doctor"))->select('*', "radiology_orders.id as code");
            return DataTables::of($radiologyOrders)
                            ->addColumn('action', function(RadiologyOrder $radiologyOrder) {
                                $radiologyOrder->id = $radiologyOrder->code;
                                return view("radiologyUserDashboard.action", compact("radiologyOrder"));
                            })
                            ->editColumn('insurance_accept', function(RadiologyOrder $radiologyOrder) {
                                if ($radiologyOrder->insurance_accept == 'accepted') {
                                    $label = "success";
                                    $text = "موافقه";
                                }else if ($radiologyOrder->insurance_accept == 'required') {
                                    $label = "primary";
                                    $text = "مطلوب";
                                } else if ($radiologyOrder->insurance_accept == 'refused') {
                                    $label = "danger";
                                    $text = "مرفوض";
                                } else {
                                    $label = "default";
                                    $text = "غير متاحه";
                                }

                                return "<span class='label label-$label' >$text</span>";
                            }) 
                            ->addColumn('patient', function(RadiologyOrder $radiologyOrder) {
                                return ($radiologyOrder->patient)? $radiologyOrder->patient->name : '';
                            })
                            ->addColumn('radiology', function(RadiologyOrder $radiologyOrder) {
                                return ($radiologyOrder->name)? $radiologyOrder->name : '';
                            })
                            ->addColumn('image', function(RadiologyOrder $radiologyOrder) {
                                return "<img src='" . url('/image/radiologyorder') . "/" . $radiologyOrder->photo . "' height='60px' onclick='viewImage(this)'  >";
                            })
                            ->rawColumns(['action', 'image', 'insurance_accept'])
                            ->make(true);
    }
}
