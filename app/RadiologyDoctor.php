<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\ViewBuilder;

class RadiologyDoctor extends Model
{

    protected $table = 'radiology_doctors';
     
    protected $filradiologyle = [
        "name", "name_ar", "name_fr", "username", "password"
    ];
 
    
    public function radiologys()
    {
        return $this->hasMany('App\Radiology');
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     *      
     */
    public function getViewBuilder() { 
        $builder = new ViewBuilder($this, "rtl");

 

        $builder->setAddRoute(url('/radiologydoctor/store'))
                ->setEditRoute(url('/radiologydoctor/update') . "/" . $this->id)
                ->setCol(["name" => "id", "radiologyel" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "radiologyel" => __('name')])
                ->setCol(["name" => "name_ar", "radiologyel" => __('name_ar'), 'required' => false])
                ->setCol(["name" => "name_fr", "radiologyel" => __('name_fr'), 'required' => false])
                ->setCol(["name" => "username", "radiologyel" => __('username')])
                ->setCol(["name" => "password", "radiologyel" => __('password'), "type" => "password"])
        
                ->setUrl(url('/image/radiologydoctor'))
                ->build();

        return $builder;
    }
    
}
