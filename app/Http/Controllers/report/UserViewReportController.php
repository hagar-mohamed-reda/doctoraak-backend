<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\View;

class UserViewReportController extends Controller
{
    public function index(Request $request) { 
        
        if ($request->has("datefrom") && $request->has("dateto")) {
            $views = View::whereBetween('date', [$request->datefrom, $request->dateto])->get();
        } else
            $views = view::all(); 
        //return $views;
        $views = $this->fitlerView($views);
        return view("report.userview", compact("views"));
    }
    
    public function fitlerView($views) { 
        $viewsFilted = [];
        
        foreach($views as $view) {
            if (!isset($viewsFilted[$view->ip])) {
                $view->view = 1;
                $viewsFilted[$view->ip] = $view;
            } else {
                $viewsFilted[$view->ip]->view += 1;
            }
        }
        
        return $viewsFilted;
    }
}
