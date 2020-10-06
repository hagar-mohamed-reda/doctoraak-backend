<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\User;

class LoginController extends Controller {

    public function index() {
        $route = "signin";
        return view("login", compact("route"));
    }

    public function login(Request $request) {
        validator()->make($request->all(), [
            'email' => 'required',
            'password' => 'required',
                ], []);

        $user = User::where('email', $request->email)->where("password", $request->password)->first();

        if ($user) {
            Auth::login($user);
            session(["user"=> $user->id]);
            return Message::redirectTo("home", Message::$WELCOME . $user->name, 1);
        } else {
            return Message::redirectTo("login", Message::$LOGIN_ERROR, 0);
        }
    }

    public function logout() {
        Auth::logout();
        session(["user" => null]);
        return redirect("login");
    }

}
