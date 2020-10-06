<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\ViewBuilder;

class LabDoctor extends Model
{

    protected $table = 'lab_doctors';
     
    protected $fillable = [
        "name", "name_ar", "name_fr", "username", "password"
    ];
 
    
    public function labs()
    {
        return $this->hasMany('App\Lab');
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     *      
     */
    public function getViewBuilder() { 
        $builder = new ViewBuilder($this, "rtl");

 

        $builder->setAddRoute(url('/labdoctor/store'))
                ->setEditRoute(url('/labdoctor/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), 'required' => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), 'required' => false])
                ->setCol(["name" => "username", "label" => __('username')])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"])
        
                ->setUrl(url('/image/labdoctor'))
                ->build();

        return $builder;
    }
    
}
