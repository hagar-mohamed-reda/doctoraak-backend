<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

 
class Role  extends Model  
{
    protected $table = "user_roles";
    
    public static $PATIENT = 1;
    public static $ICU = 2;
    public static $INCUBATION = 3;
    public static $DOCTOR = 4;
    public static $PHARMACY = 5;
    public static $LAB = 6;
    public static $RADIOLOGY = 7;
    public static $INSURANCE = 8;
    public static $USER = 9;
    public static $REPORT = 10;
    public static $OPTION = 11;
    
    public static function canAccess($user, $role) {
        if (!$user)
            $user = session("user");
        return Role::where("user_id", $user)->where("role", $role)->count() > 0? true : false;
    }
    
    protected $fillable = [
        "user_id", "role"
    ];
}
