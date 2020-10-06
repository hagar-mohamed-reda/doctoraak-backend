<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiologyOrderDetails extends Model
{

    protected $table = 'radiology_order_details';
    public function radiology_order()
    {
        return $this->belongsTo('App\RadiologyOrderDetails', 'radiology_order');
    }

    public function rays()
    {
        return $this->belongsTo('App\Ray', 'rays_id');
    }
     public function getJson()
    {
        $this->rays = $this->rays()->first();

        return $this;
    }
}
