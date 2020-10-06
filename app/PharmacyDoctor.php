<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\ViewBuilder;
use App\Pharmacy;

class PharmacyDoctor extends Model
{

    protected $table = 'pharmacy_doctors';

    protected $fillable = [
        "name", "name_ar", "name_fr", "username", "password"
    ];
 

    public function pharmacydoctors()
    {
        return $this->hasMany('App\Pharmacy');
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     *      
     */
    public function getViewBuilder() { 
        $builder = new ViewBuilder($this, "rtl");

 

        $builder->setAddRoute(url('/pharmacydoctor/store'))
                ->setEditRoute(url('/pharmacydoctor/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), 'required' => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), 'required' => false])
                ->setCol(["name" => "username", "label" => __('username')])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"])
        
                ->setUrl(url('/image/pharmacydoctor'))
                ->build();

        return $builder;
    }
    
}
