<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiologyInsurance extends Model
{

    protected $table = 'radiology_insurances';
    public function insurance()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }

    public function radiology()
    {
        return $this->belongsTo('App\Radiology', 'lab_id');
    }
}
