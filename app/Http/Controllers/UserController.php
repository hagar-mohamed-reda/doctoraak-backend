<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use DataTables;
use Illuminate\Foundation\Console\Presets\React;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("user.index");
    }

    /**
     *
     * @return ajax data
     */
    public function getData() { 
        //return Datatables::of($users)->make(true);
        return DataTables::eloquent(User::query())
                        ->addColumn('action', function(User $user) { 
                            return view("user.action", compact("user"));
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }

    /**
     * check on email validation
     *
     * @param Request $request
     * @return void
     */
    public function checkEmail(Request $request) {
        $userCount = User::where("email", $request->email)->count();

        return ($userCount > 0)? 0 : 1;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("user.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try { 
            User::create($request->all());
            return Message::redirect("user/create", Message::$DONE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("user/create", Message::$ERROR, 0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        return $user->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        try {
            $user->update($request->all());
            return Message::redirect("user/", Message::$EDIT, 1);
        } catch (\Exception $ex) {
            return Message::redirect("user/", Message::$ERROR, 0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        try {
            $user->delete();
            return Message::redirect("user/", Message::$REMOVE, 1);
        } catch (\Exception $ex) {
            return Message::redirect("user/", Message::$ERROR, 0);
        }
    }

}
