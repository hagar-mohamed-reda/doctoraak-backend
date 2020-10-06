<?php

namespace App\Http\Controllers\insuranceDashboard;

use App\Http\Controllers\Controller;
use App\Message;
use App\Patient;
use App\Lab;
use App\UserInsurance;
use App\LabOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabOrderController extends Controller
{

    /**
     * accept on lab order
     * @param Request $request
     * @param \App\Http\Controllers\insuranceDashboard\LabOrder $order
     */
    public function acceptOrder(Request $request, LabOrder $order) {
        try {
            $order->notes = $request->notes;
            $order->insurance_accept = 'accept';
            $order->insurance_code = time();
            $order->update();
            
            $t1_ar = "طلب رقم " . $order->id;
            $b1_ar = "تم الموافقه على طلبك من قبل شركة التامين كود الموافقه " . $order->insurance_code . " ";
            $b1_ar .= " ملاحظات على طلبك من شركة التامين" . $order->notes;
            $t1_en = "order number ". $order->id;
            $b1_en = "insurance company accepted your order and the accepted code " . $order->insurance_code;
            $b1_en .= " notes from insurance company " . $order->notes;
            Lab::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "", $order->lab_id);
            Patient::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "",  $order->patient_id);
            return Message::redirect("insuranceuserdashboard", Message::$DONE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("insuranceuserdashboard", Message::$ERROR, 0);
        }
    }

    public function refuseOrder(Request $request, LabOrder $order) {
        try {
            $order->notes = $request->notes;
            $order->insurance_accept = 'refused';
            $order->update();

            $t1_ar = "طلب رقم " . $order->id;
            $b1_ar = "تم الرفض على طلبك من قبل شركة التامين ";
            $b1_ar .= " ملاحظات على طلبك من شركة التامين" . $order->notes;
            $t1_en = "order number ". $order->id;
            $b1_en = "insurance company refused your order ";
            $b1_en .= " notes from insurance company " . $order->notes;
            Lab::notify($t1_ar, $t1_en, $b1_ar, $b1_en,  "", $order->lab_id);
            Patient::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "",  $order->patient_id);
            return Message::redirect("insuranceuserdashboard", Message::$DONE, 1);
        } catch (Exception $exc) {
            return Message::redirect("insuranceuserdashboard", Message::$ERROR, 0);
        }
    }
    /**
     *
     * @param LabOrder $labOrder
     * @return type
     */
    public function show(LabOrder $labOrder) {
        return view("insuranceUserDashboard.lab.show", compact("labOrder"));
    }

    /**
     *
     * @return ajax data
     */
    public function getData()
    {
        $id = session("insurance");
        if ($id == null) {
            return;
        }

        $insuranceuser = UserInsurance::find($id);
        $labOrders = $insuranceuser->insurance()->first()->getLabOrdersRequiredInsuranceAccept(); 
        return DataTables::of($labOrders)
            ->editColumn('action', function (LabOrder $labOrder) {
                $id = $labOrder->code;
                return view("insuranceUserDashboard.lab.action", compact("id"));
            })
            ->editColumn('insurance_accept', function (LabOrder $labOrder) {
                if ($labOrder->insurance_accept == 'required') {
                    $label = "warning";
                    $text = "مطلوب موافقه";
                }

                return "<span class='label label-$label' >$text</span>";
            })
            ->editColumn('id', function (LabOrder $labOrder) {
                return $labOrder->code;
            })
            ->editColumn('patient', function (LabOrder $labOrder) {
                return ($labOrder->patient)? $labOrder->patient->name : "";
            })
            ->editColumn('lab', function (LabOrder $labOrder) {
                return ($labOrder->lab)? $labOrder->lab->name : '';
            })
            ->editColumn('image', function (LabOrder $labOrder) {
                if (strlen(($labOrder->order_image)) <= 0)
                    return "";
                return "<img src='" . url('/image/laborder') . "/" . $labOrder->order_image . "' height='40px' class='w3-round' onclick='viewImage(this)'  >";
            })
            ->rawColumns(['action', 'image', 'insurance_accept'])
            ->make(true);
    }
}
