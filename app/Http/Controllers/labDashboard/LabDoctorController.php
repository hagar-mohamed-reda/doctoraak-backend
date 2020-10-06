<?php

namespace App\Http\Controllers\labDashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\LabDoctor;
use App\Lab;
use App\Message;
use App\LabOrder;
use Yajra\DataTables\DataTables;

class LabDoctorController extends Controller
{

    public function index() {
        $id = session("doctor");
        if ($id == null)
            return redirectTo("labdoctordashboard/login");

        $user = LabDoctor::find($id);
        return view("labUserDashboard.index", compact("user"));
    }

    public function login() {
        return view("labUserDashboard.login");
    }

    public function logout() {
        session(["doctor" => null]);
        return Message::redirectTo("labdoctordashboard/login", "", 0);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LabOrder $laborder) {
        return view("labUserDashboard.show", compact("laborder"));
    }
    
    public function sign(Request $request) {
        try{
            $users = LabDoctor::where("username", $request->email)->where("password", $request->password)->get();

            if (count($users) > 0) {
                $user = $users[0];
                session(["doctor" => $user->id]);

                return Message::redirectTo("labdoctordashboard", Message::$SUCCESS_LOGIN, 1);
            } else {
                return Message::redirectTo("labdoctordashboard/login", Message::$LOGIN_ERROR, 0);
            }
        }catch(\Exception $e) {
            return Message::redirectTo("labdoctordashboard/login", Message::$LOGIN_ERROR, 0);
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
            $labOrders = LabOrder::join("labs", "labs.id", "=", "lab_id")->where("lab_doctor_id", session("doctor"))->select('*', "lab_orders.id as code");
            //return Datatables::of($labOrders)->make(true);
            return DataTables::of($labOrders)
                            ->addColumn('action', function(LabOrder $labOrder) {
                                $labOrder->id = $labOrder->code;
                                return view("labUserDashboard.action", compact("labOrder"));
                            })
                            ->editColumn('insurance_accept', function(LabOrder $labOrder) {
                                if ($labOrder->insurance_accept == 'accepted') {
                                    $label = "success";
                                    $text = "موافقه";
                                }else if ($labOrder->insurance_accept == 'required') {
                                    $label = "primary";
                                    $text = "مطلوب";
                                } else if ($labOrder->insurance_accept == 'refused') {
                                    $label = "danger";
                                    $text = "مرفوض";
                                } else {
                                    $label = "default";
                                    $text = "غير متاحه";
                                }

                                return "<span class='label label-$label' >$text</span>";
                            })
                            ->addColumn('patient', function(LabOrder $labOrder) {
                                return ($labOrder->patient)? $labOrder->patient->name : '';
                            })
                            ->addColumn('lab', function(LabOrder $labOrder) {
                                return ($labOrder->lab)? $labOrder->lab->name : '';
                            })
                            ->addColumn('image', function(LabOrder $labOrder) {
                                return "<img src='" . url('/image/laborder') . "/" . $labOrder->photo . "' height='60px' onclick='viewImage(this)'  >";
                            })
                            ->rawColumns(['action', 'image', 'insurance_accept'])
                            ->make(true);
    }
}
