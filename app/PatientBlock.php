<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientBlock extends Model
{

    protected $table = 'patient_blocks';
    public function patient_block()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public function doctor_block()
    {
        return $this->belongsTo('App\Doctor', 'doctor_id');
    }
}
