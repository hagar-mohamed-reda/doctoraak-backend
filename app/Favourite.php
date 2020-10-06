<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{

    protected $table = 'favourites';
     /**
     * fillable fields
     */
    protected $fillable = [
        "doctor_id", "patient_id"
    ];
    public function doctor()
    {
        return $this->belongsTo('App\Doctor', 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }
     /**
     * get json object
     *
     * @return void
     */
    public function getJson()
    {
          $doctor = $this->doctor()->first();
          $this->doctor->photo = url('/image/doctor/') . "/" . $doctor->photo;
          $this->degree = $this->doctor->degree;
          $this->specialization = $this->doctor->specialization;


        return $this;
    }
    
   /* 
     public function getJson()
    {
        $doctor = $this->doctor()->first();
        $this->doctor->rate = $doctor->getRate();
        $this->doctor->photo = url('/image/doctor/') . "/" . $doctor->photo;
        $this->working_hours = $this->working_hours()->get();
        $this->photo = url('/image/clinic/') . "/" . Clinic::find($this->id)->photo;
     
        return $this;
    }*/
}
