<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserInsurance;
use App\Message;
use Yajra\DataTables\DataTables;



class UserInsuranceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("userinsurance.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        $query = UserInsurance::query();
         
        if (strlen(request()->search_string) > 0) {
            $query
                    ->where('name', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_ar', 'like', '%'.request()->search_string.'%')
                    ->orWhere('email', 'like', '%'.request()->search_string.'%')
                    ->orWhere('name_fr', 'like', '%'.request()->search_string.'%');
        }
        
        if (request()->insurance_id > 0) {
            $query->where('insurance_id', request()->insurance_id);
        }
        
        return DataTables::of($query)
                        ->addColumn('action', function(UserInsurance $userinsurance) {
                            return view("userinsurance.action", compact("userinsurance"));
                        })
                        ->editColumn('insurance_id', function(UserInsurance $userinsurance) {
                            return optional($userinsurance->insurance)->name;
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("userinsurance.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {   
        if (UserInsurance::where("email", $request->email)->count() > 0)
            return Message::redirect("", Message::$EMAIL_UNIQUE, 0);

        try {
            UserInsurance::create($request->all());

            return Message::redirect("userinsurance/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("userinsurance/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserInsurance $userinsurance) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserInsurance $userinsurance) {
        return $userinsurance->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserInsurance $userinsurance) {
        /*if (UserInsurance::where("email", $request->email)->count() > 0)
            return Message::redirect("", Message::$EMAIL_UNIQUE, 0);
*/
        try {
            $userinsurance->update($request->all());

            return Message::redirect("userinsurance/", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("userinsurance/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserInsurance $userinsurance) {
        try {
            $userinsurance->delete();
            return Message::redirect("userinsurance/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("userinsurance/", Message::$ERROR, 0);
        }
    }

}
