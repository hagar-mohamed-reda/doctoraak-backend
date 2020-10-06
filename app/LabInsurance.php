<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabInsurance extends Model
{


    protected $table = 'lab_insurances';
    public function insurance()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }

    public function lab()
    {
        return $this->belongsTo('App\Lab', 'lab_id');
    }
}
