<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ray extends Model
{


    protected $table = 'rays';
    
    protected $fillable = [
        "name", "name_ar", "name_fr"
    ];


    public $cols = [
        "id" => [
            "ar" => "الكود",
            "editable" => false,
            "required" => false
        ],
        "name" => [
            "ar" => "الاسم بالانجليزيه",
            "editable" => true,
            "required" => true
        ], 
        "name_ar" => [
            "ar" => "الاسم بالعربيه",
            "editable" => true,
            "required" => true
        ],
        "name_fr" => [
            "ar" => "الاسم بالفرنسيه",
            "editable" => true,
            "required" => true
        ] 
    ];
    
    public function radiology_order_details()
    {
        return $this->hasMany('App\RadiologOrderDetails');
    }
}
