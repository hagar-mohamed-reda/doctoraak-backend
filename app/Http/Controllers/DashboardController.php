<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\View;
use App\User;

class DashboardController extends Controller
{
    public function index() {
        $user = User::find(session("user"));
        return view("index", compact("user"));
    }

    public function dashboard() {
        $views = View::monthView();
        return view("main", compact("views"));
    }


}
