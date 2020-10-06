<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Message;
use App\User;

class RoleController extends Controller
{
    
    public function index(User $user) {
        return view("user.role", compact("user"));
    }
    
    public function addRole(Request $request) {
        if ($request->role == Role::$USER) {
            return Message::redirect("", Message::$USER_ROLE_ERROR, 0);
        }
        
        $role = Role::where("user_id", $request->user_id)->where("role", $request->role)->first(); 
        
        if ($role) { 
            $role->delete();
            return Message::redirect("", Message::$REMOVE, 1);
        } else {
            Role::create($request->all());
            return Message::redirect("", Message::$DONE, 1);
        } 
    }
}
