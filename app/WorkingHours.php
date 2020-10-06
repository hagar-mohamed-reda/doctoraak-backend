<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingHours extends Model {

    public static $DAYS = [
        "Sat" => 1,
        "Sun" => 2,
        "Mon" => 3,
        "Tue" => 4,
        "Wed" => 5,
        "Thu" => 6,
        "Fri" => 7,
    ];

    public static function getDayName($day) { 
        $days = [
            1 => "Sat",
            2 => "Sun",
            3 => "Mon",
            4 => "Tue",
            5 => "Wed",
            6 => "Thu",
            7 => "Fri",
        ];
        
        return isset($days[$day])? $days[$day] : '';
    }
    
    /**
     * get day number in week from date
     * 
     * @param type $date
     * @return type
     */
    public static function getDay($date) {
        return self::$DAYS[date('D', strtotime($date))];
    }

    /**
     * return start date of week and end date
     * 
     * @param type string of date "Y-m-d"
     * @return type
     */
    public static function getStartAndEndDateOfWeek($date) {
        // get current day of week
        $day = WorkingHours::getDay($date);
        
        // get different days from week start
        $diffDaysFromWeekStart = $day - 1;
        
        // get different days from week end
        $diffDaysFromWeekEnd = 7 - $day;

        // calculate start date of week
        $startDateOfWeek = date('Y-m-d', strtotime($date. ' - '.$diffDaysFromWeekStart.' days'));
        
        // calculate start date of week
        $endDateOfWeek = date('Y-m-d', strtotime($date. ' + '.$diffDaysFromWeekEnd.' days'));

        return [$startDateOfWeek, $endDateOfWeek];
    }

}
