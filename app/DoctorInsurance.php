<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorInsurance extends Model
{

    protected $table = 'doctor_insurances';
    public function doctor()
    {
        return $this->belongsTo('App\doctor', 'doctor_id');
    }

    public function insurance()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }
}
