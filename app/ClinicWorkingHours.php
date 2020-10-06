<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicWorkingHours extends WorkingHours
{

    protected $table = 'clinic_working_hours';
    protected $fillable = [

        'day',
        'part1_from',
        'part2_from',
        'part1_to',
        'part2_to',
        'waiting_time',
        'active',
        'clinic_id',
        'reservation_number_1',
        'reservation_number_2',



    ];
    public function clinic()
    {
        return $this->belongsTo('App\Clinic', 'clinic_id');
    }
 
    public static function calculateReservationTime($waitingTime, $reservationNumber, $partFrom)
    {
        $reservationNumber -= 1;
        $hours = date("H", strtotime($partFrom));
        $minutes = date("i", strtotime($partFrom));

        $reservationHours = ($reservationNumber * $waitingTime) / 60;
        $reservationMinutes = ($reservationHours - ((int) $reservationHours)) * 60;


        $hours += ((int) $reservationHours);
        $minutes += $reservationMinutes;

        $time = $hours . ":" . $minutes;

        return date("H:i:s", strtotime($time));
    }
}
