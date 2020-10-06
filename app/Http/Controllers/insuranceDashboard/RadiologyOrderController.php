<?php

namespace App\Http\Controllers\insuranceDashboard;

use App\Http\Controllers\Controller;
use App\Message;
use App\Patient;
use App\Radiology;
use App\UserInsurance;
use App\RadiologyOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RadiologyOrderController extends Controller
{

    /**
     * accept on radiology order
     * @param Request $request
     * @param \App\Http\Controllers\insuranceDashboard\RadiologyOrder $order
     */
    public function acceptOrder(Request $request, RadiologyOrder $order) { 
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
            Radiology::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "", $order->radiology_id); 
            Patient::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "",  $order->patient_id);
            return Message::redirect("insuranceuserdashboard", Message::$DONE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("insuranceuserdashboard", Message::$ERROR, 0);
        }
    }
    public function refuseOrder(Request $request, RadiologyOrder $order) {
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
            Radiology::notify($t1_ar, $t1_en, $b1_ar, $b1_en,  "", $order->radiology_id); 
            Patient::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "",  $order->patient_id);
            return Message::redirect("insuranceuserdashboard", Message::$DONE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("insuranceuserdashboard", Message::$ERROR, 0);
        }
    }

    /**
     *
     * @param RadiologyOrder $radiologyOrder
     * @return type
     */
    public function show(RadiologyOrder $radiologyOrder) {
        return view("insuranceUserDashboard.radiology.show", compact("radiologyOrder"));
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
        $radiologyOrders = $insuranceuser->insurance()->first()->getRadiologyOrdersRequiredInsuranceAccept();
        //return Datatables::of($radiologyOrders)->make(true);
        return DataTables::of($radiologyOrders)
            ->editColumn('action', function (RadiologyOrder $radiologyOrder) {
                $id = $radiologyOrder->code;
                return view("insuranceUserDashboard.radiology.action", compact("id"));
            }) 
            ->editColumn('insurance_accept', function (RadiologyOrder $radiologyOrder) {
                if ($radiologyOrder->insurance_accept == 'required') {
                    $radiologyel = "warning";
                    $text = "مطلوب موافقه";
                }

                return "<span class='label label-$radiologyel' >$text</span>";
            })
            ->editColumn('id', function (RadiologyOrder $radiologyOrder) {
                return $radiologyOrder->code;
            })
            ->editColumn('patient', function (RadiologyOrder $radiologyOrder) {
                return ($radiologyOrder->patient)? $radiologyOrder->patient->name : '';
            })
            ->editColumn('radiology', function (RadiologyOrder $radiologyOrder) {
                return ($radiologyOrder->radiology)? $radiologyOrder->radiology->name : '';
            })
            ->editColumn('image', function (RadiologyOrder $radiologyOrder) {
                if (strlen(($radiologyOrder->order_image)) <= 0)
                    return "";
                return "<img src='" . url('/image/radiologyorder') . "/" . $radiologyOrder->order_image . "' height='60px' onclick='viewImage(this)'  >";
            })
            ->rawColumns(['action', 'image', 'insurance_accept'])
            ->make(true);
    }
}
