<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\ViewBuilder;

class UserInsurance extends Model
{

    protected $table = 'user_insurances';

    protected $fillable = [
        "name", "insurance_id", "email", "password", "name_fr", "name_ar"
    ];
 
    public function insurance()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");


        $insurances = [ 
        ];
        foreach (Insurance::get(['id', 'name']) as $item)
            $insurances[] = [$item->id, $item->name];
 

        $builder->setAddRoute(url('/userinsurance/store'))
                ->setEditRoute(url('/userinsurance/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), "required" => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), "required" => false]) 
                ->setCol(["name" => "email", "label" => __('email'), "type" => "email"]) 
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"]) 
                ->setCol(["name" => "insurance_id", "label" => __('insurance'), "type" => "select", "data" => $insurances]) 
                ->setUrl(url('/image/userinsurance'))
                ->build();

        return $builder;
    }
    
}
