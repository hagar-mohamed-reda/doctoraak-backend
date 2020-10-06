<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicOrder extends Model
{ 
    protected $table = 'clinic_orders';

    protected $fillable = [
        'active',
        'clinic_id',
        'doctor_id',
        'part_id',
        'type',
        'notes',
        'date',
        'reservation_number'
    ];
    
    public function clinic()
    {
        return $this->belongsTo('App\Clinic', 'clinic_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

     public function getJson()
    {
        $this->patient = $this->patient()->first();
        $this->patient->photo = url('/image/patient') . "/" . $this->patient->photo;
        $this->clinic = $this->clinic()->first();
        $this->clinic->doctor = $this->clinic->doctor;
        $this->clinic->doctor->photo = url('/image/doctor/') . "/" . $this->clinic->doctor->photo;
       
        return $this;
    }
    
    public function getReservationTypeAr() {
        switch ($this->type) {
            case 1: return 'حجز';
            case 2: return 'استشاره';
            case 3: return 'متابعه';
        }
    }
}
