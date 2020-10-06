<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientSpam extends Model
{


    protected $table = 'patient_spam';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 
    protected $fillable = [
        'id', 'patient_id', 'date', 'spam'
    ];
    
    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    } 
}
