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

class InsuranceUserController extends Controller
{

    public function index()
    {
        $id = session("insurance");
        if ($id == null) {
            return redirect("insuranceuserdashboard/login");
        }

        $user = UserInsurance::find($id);
        return view("insuranceUserDashboard.index", compact("user"));
    }

    public function login()
    {
        return view("insuranceUserDashboard.login");
    }

    public function logout()
    {
        session(["insurance" => null]);
        return Message::redirectTo("insuranceuserdashboard/login", "", 0);
    }

    public function sign(Request $request)
    {
        try {
            $users = UserInsurance::where("email", $request->email)->where("password", $request->password)->get();

            if (count($users) > 0) {
                $user = $users[0];
                session(["insurance" => $user->id]);

                return Message::redirectTo("insuranceuserdashboard", Message::$SUCCESS_LOGIN, 1, url('/image/insurance/') . "/" . $user->insurance->photo, $user->insurance->name);
            } else {
                return Message::redirectTo("insuranceuserdashboard/login", Message::$LOGIN_ERROR, 0);
            }
        } catch (\Exception $e) {
            return Message::redirectTo("insuranceuserdashboard/login", Message::$LOGIN_ERROR, 0);
        }
    }


}
