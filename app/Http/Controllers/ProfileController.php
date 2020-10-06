<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;

class ProfileController extends Controller {

    public function index() {
        $id = session("user");
        $user = User::find($id);

        return view("profile", compact("user"));
    }

    public function update(Request $request) {
        try {
            $id = session("user");
            $user = User::find($id);

            $user->update($request->all());
            return Message::redirect("profile", Message::$DONE, 1);
        } catch (\Exception $exc) {
            return Message::redirect("profile", Message::$ERROR, 0);
        }
    }

}
