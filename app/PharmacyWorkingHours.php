<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PharmacyWorkingHours extends WorkingHours
{

    protected $table = 'pharmacy_working_hours';
    
    protected $fillable = [ 
        'pharmacy_id',
        'day',
        'part_from',
        'part_to',
        'active' 
    ]; 
    
     public static $DAYS = [
        "Sat" => 1,
        "Sun" => 2,
        "Mon" => 3,
        "Tue" => 4,
        "Wed" => 5,
        "Thu" => 6,
        "Fri" => 7,
    ];
    public function pharmacy()
    {
        return $this->belongsTo('App\Pharmacy', 'pharmacy_id');
    }
}
