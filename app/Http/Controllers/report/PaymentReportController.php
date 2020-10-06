<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Payment;

class PaymentReportController extends Controller
{
    public function index(Request $request) {
        if ($request->has("datefrom") && $request->has("dateto"))
            $pays = $this->filterDate(Payment::whereBetween("created_at", [$request->datefrom, $request->dateto])->get());
        else
            $pays = $this->filterDate(Payment::all());

        $paymentModels = Payment::getPaymentModelValue($pays);

        return view("report.payment", compact("pays", "paymentModels"));
    }

    public function filterDate($pays) {
        $fitlerPays = [];

        foreach($pays as $pay) {
            $date = date("Y-m-d",  strtotime($pay->created_at));
            if (isset($fitlerPays[$date]))
                $fitlerPays[$date]->value += $pay->value;
            else
                $fitlerPays[$date] = $pay;
        }

        return $fitlerPays;
    }

}
