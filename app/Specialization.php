<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{

    protected $table = 'specializations';
     protected $fillable = [

        'name_ar',
        'name',
        'name_fr',
        'icon',
    ];
    
    /**
     * appends attribute 
     * 
     */
    protected $appends = [
        'url'
    ];
    
    
    /**
     * init url attribute
     * 
     * @return String url
     */
    public function getUrlAttribute() {
        return url('image/special') . "/" . $this->icon;
    }

    
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
        ],
        "icon" => [
            "ar" => "الايقونه",
            "editable" => false,
            "required" => false
        ] 
    ];
    
    public function doctors()
    {
        return $this->hasMany('App\Doctor');
    }
    
    public function getJson() {
        //$this->icon = url('/image/special/') . "/" . $this->icon;
        return $this;
    }
}
