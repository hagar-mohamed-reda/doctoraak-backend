<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\ViewBuilder;

class Icu extends Model
{

    protected $table = 'icus';
    protected $fillable = [
        "id", "name", "name_ar", "name_fr", "description", "description_ar", "description_fr", "phone",
        "city_id", "area_id", "bed_number", "rate", "lng", "lat"
    ];

    public function city() {
        return $this->belongsTo("App\City", 'city_id');
    }

    public function area() {
        return $this->belongsTo("App\Area", 'area_id');
    }

    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");


        $cities = [
            ['', __('all')]
        ];
        foreach (City::get(['id', 'name']) as $item)
            $cities[] = [$item->id, $item->name];

        $areas = [
            ['', __('all')]
        ];
        foreach (Area::get(['id', 'name', 'city_id']) as $item)
            $areas[] = [$item->id, $item->name, $item->city_id];


        $builder->setAddRoute(url('/icu/store'))
                ->setEditRoute(url('/icu/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), "required" => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), "required" => false])
                ->setCol(["name" => "description", "label" => __('description'), "type" => "textarea", "required" => false])
                ->setCol(["name" => "description_ar", "label" => __('description_ar'), "type" => "textarea", "required" => false])
                ->setCol(["name" => "description_fr", "label" => __('description_fr'), "type" => "textarea", "required" => false])
                ->setCol(["name" => "bed_number", "label" => __('bed_number'), "type" => "number"])
                ->setCol(["name" => "map", "label" => __('location'), "type" => "map"])
                ->setCol(["name" => "phone", "label" => __('phone')])
                ->setCol(["name" => "city_id", "label" => __('city'), "type" => "select", "data" => $cities])
                ->setCol(["name" => "area_id", "label" => __('area'), "type" => "select", "data" => $areas])
                ->setCol(["name" => "rate", "label" => __('rate'), "type" => "rate", "col" => "w3-col l12 m12 s12"])
                ->setUrl(url('/image/icu'))
                ->build();

        return $builder;
    }


}
