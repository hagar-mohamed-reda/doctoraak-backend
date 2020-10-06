<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientRate extends Model


{

    /**
     * table name
     */
    protected $table = 'patient_rates';

    /**
     * fillable fields
     */
    protected $fillable = [
        "doctor_id", "patient_id", "rate", "type"
    ];
    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Doctor', 'doctor_id');
    }
}
