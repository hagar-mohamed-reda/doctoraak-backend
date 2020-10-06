<?php

namespace App\Http\Controllers\insuranceDashboard;

use App\Http\Controllers\Controller;
use App\Message;
use App\Patient;
use App\Pharmacy;
use App\UserInsurance;
use App\PharmacyOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PharmacyOrderController extends Controller
{

    /**
     * accept on pharmacy order
     * @param Request $request
     * @param \App\Http\Controllers\insuranceDashboard\PharmacyOrder $order
     */
    public function acceptOrder(Request $request, PharmacyOrder $order) {
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
            Pharmacy::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "", $order->pharmacy_id);
            Patient::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "",  $order->patient_id);
            return Message::redirect("insuranceuserdashboard", Message::$DONE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("insuranceuserdashboard", Message::$ERROR, 0);
        }
    }
    public function refuseOrder(Request $request, PharmacyOrder $order) {
        try {
            $order->notes = $request->notes;
            $order->insurance_accept = 'refused';
            $order->insurance_code = time();
            $order->update();

            $t1_ar = "طلب رقم " . $order->id;
            $b1_ar = "تم الرفض على طلبك من قبل شركة التامين ";
            $b1_ar .= " ملاحظات على طلبك من شركة التامين" . $order->notes;
            $t1_en = "order number ". $order->id;
            $b1_en = "insurance company refused your order ";
            $b1_en .= " notes from insurance company " . $order->notes;
            Pharmacy::notify($t1_ar, $t1_en, $b1_ar, $b1_en,  "", $order->pharmacy_id);
            Patient::notify($t1_ar, $t1_en, $b1_ar, $b1_en, "",  $order->patient_id);
            return Message::redirect("insuranceuserdashboard", Message::$DONE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("insuranceuserdashboard", Message::$ERROR, 0);
        }
    }

    /**
     *
     * @param PharmacyOrder $pharmacyOrder
     * @return type
     */
    public function show(PharmacyOrder $pharmacyOrder) {
        return view("insuranceUserDashboard.pharmacy.show", compact("pharmacyOrder"));
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

        $pharmacyOrders = $insuranceuser->insurance->getPharmacyOrdersRequiredInsuranceAccept();
        //return Datatables::of($pharmacyOrders)->make(true);
        return DataTables::of($pharmacyOrders)
            ->addColumn('action', function (PharmacyOrder $pharmacyOrder) {
                $id = $pharmacyOrder->code;
                //$request = ($pharmacyOrder->getAcceptedRequest())? $pharmacyOrder->getAcceptedRequest()->id : $pharmacyOrder->pharmacy_requests()->first()->id;
                return view("insuranceUserDashboard.pharmacy.action", compact("id"));
            })
            ->editColumn('insurance_accept', function (PharmacyOrder $pharmacyOrder) {
                if ($pharmacyOrder->insurance_accept == 'required') {
                    $pharmacyel = "warning";
                    $text = "مطلوب موافقه";
                }

                return "<span class='label label-$pharmacyel' >$text</span>";
            })
            ->addColumn('patient', function (PharmacyOrder $pharmacyOrder) {
                return ($pharmacyOrder->patient)? $pharmacyOrder->patient->name : '';
            })
            ->editColumn('id', function (PharmacyOrder $pharmacyOrder) {
                return $pharmacyOrder->code;
            })
            ->addColumn('pharmacy', function (PharmacyOrder $pharmacyOrder) {
                return ($pharmacyOrder->pharmacy)? $pharmacyOrder->pharmacy->name : '';
            })
            ->addColumn('image', function (PharmacyOrder $pharmacyOrder) {
                if (strlen(($pharmacyOrder->order_image)) <= 0)
                    return "";
                return "<img src='" . url('/image/pharmacyorder') . "/" . $pharmacyOrder->order_image . "' height='60px' onclick='viewImage(this)'  >";
            })
            ->rawColumns(['action', 'image', 'insurance_accept'])
            ->make(true);
    }
}
