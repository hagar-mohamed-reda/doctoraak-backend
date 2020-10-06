<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiologyWorkingHours extends WorkingHours
{


    protected $table = 'radiology_working_hours';

     public static $DAYS = [
        "Sat" => 1,
        "Sun" => 2,
        "Mon" => 3,
        "Tue" => 4,
        "Wed" => 5,
        "Thu" => 6,
        "Fri" => 7,
    ];
 
    public function radiology()
    {
        return $this->belongsTo('App\Radiology', 'radiology_id');
    }
}
