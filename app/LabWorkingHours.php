<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabWorkingHours extends WorkingHours
{

    protected $table = 'lab_working_hours';
    public function lab()
    {
        return $this->belongsTo('App\Lab', 'lab_id');
    }

     public static $DAYS = [
        "Sat" => 1,
        "Sun" => 2,
        "Mon" => 3,
        "Tue" => 4,
        "Wed" => 5,
        "Thu" => 6,
        "Fri" => 7,
    ];
 
}
