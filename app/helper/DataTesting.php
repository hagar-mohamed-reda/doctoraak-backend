<?php

namespace App\helper;

use App\Patient;
use DB;
use App\View;

class DataTesting
{

    public static $DOMAIN = "doctoraak.com";
    public static $CENTER = [29.996417899999997, 31.153229699999994];
    public static $MAX_MILE = 20;
    public static $PHONE = '01123904214';
    public static $KM = 5;
    public static $MAXRESERVATIONFORPATIENT = 2;
    public static $MAXPHARMACYORDER = 5;
    public static $MAXPHARMACYORDERDETAILS = 10;
    public static $MAXLABORDER = 2;
    public static $MAXRADIOLOGYORDER = 2;

    public static function insertDoctors($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `doctors` "
                    . "(`name`, `phone`, `email`, `password`, `active`, `specialization_id`, `degree_id`, `reservation_rate`, `degree_rate`)" .
                    "VALUES ('doctor$i', '" . (time() + $i) . "', 'doctor$i@" . self::$DOMAIN . "', '12345678', '1', '" . rand(1, 5) . "', '" . rand(1, 5) . "', '" . rand(1, 5) . "', '" . rand(1, 5) . "')"
            );
            // insert doctors insurance
            DB::statement(
                "INSERT INTO `doctor_insurances` (`doctor_id`, `insurance_id`)" .
                    "VALUES ('$i', '" . rand(1, 5) . "')"
            );
        }

        return true;
    }

    public static function insertClinics($number, $doctorMaxNumber)
    {
        $waitingtimes = range(5, 20, 5);
        for ($i = 1; $i <= $number; $i++) {
            $point = View::generate_random_point(self::$CENTER, rand(1, self::$MAX_MILE));

            $waitingtime = $waitingtimes[rand(0, sizeof($waitingtimes) - 1)];

            DB::statement(
                "INSERT INTO `clinics` "
                    . "(`id`, `phone`, `fees`,`city`, `area`, "
                    . "`lang`, `latt`, `waiting_time`, `doctor_id`, `active`) VALUES "
                    . "('$i', '" . (time() + $i) . "', '" . rand(5, 50) . "', '" . rand(1, 5) . "', '" . rand(1, 5) . "', "
                    . "'" . $point[0] . "', '" . $point[1] . "', '" . $waitingtime . "', '" . rand(1, $doctorMaxNumber) . "', '1');"
            );

            // insert clinic waiting time
            for ($j = 1; $j <= 7; $j++) {
                $part1from = rand(8, 9);
                $part1to = rand(12, 14);
                $part2from = rand(15, 16);
                $part2to = rand(20, 21);

                $reservation1 = Helper::workingHours($part1to . ':00:00', $part1from . ':00:00', $waitingtime);
                $reservation2 = Helper::workingHours($part2to . ':00:00', $part2from . ':00:00', $waitingtime);

                if ($j < 6)
                    $active = 1;
                else
                    $active = 0;

                DB::statement(
                    "INSERT INTO `clinic_working_hours` "
                        . "(`clinic_id`, `day`, `part1_from`, `part1_to`, `part2_from`, `part2_to`, "
                        . "`active`, `reservation_number_1`, `reservation_number_2`"
                        . ") VALUES "
                        . "('$i', '$j', '0" . $part1from . ":00:00', '" . $part1to . ":00:00', '" . $part2from . ":00:00', '" . $part2to . ":00:00', "
                        . "'$active', '$reservation1', '$reservation2');"
                );
            }
        }

        return true;
    }

    public static function insertLabs($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            $point = View::generate_random_point(self::$CENTER, rand(1, self::$MAX_MILE));
            DB::statement(
                "INSERT INTO `labs` "
                    . "(`id`, `name`, `phone`, "
                    . "`email`, `password`, "
                    . "`active`, `city`, `area`, "
                    . "`latt`, `lang`, `lab_doctor_id`)" .
                    "VALUES "
                    . "('$i', 'lab$i', '" . (time() + $i) . "', 'lab$i@" . self::$DOMAIN . "', '123456',"
                    . "'1', '" . rand(1, 5) . "', '" . rand(1, 5) . "', '$point[0]', '$point[1]', '" . rand(1, 5) . "')"
            );
            // insert lab insurance
            DB::statement(
                "INSERT INTO `lab_insurances` (`lab_id`, `insurance_id`)" .
                    "VALUES ('$i', '" . rand(1, 5) . "')"
            );

            // insert lat working hours
            for ($j = 1; $j <= 7; $j++) {
                $part1from = rand(8, 9);
                $part1to = rand(20, 23);

                if ($j < 6)
                    $active = 1;
                else
                    $active = 0;

                DB::statement(
                    "INSERT INTO `lab_working_hours` "
                        . "(`lab_id`, `day`, `part_from`, `part_to`, `active`"
                        . ") VALUES "
                        . "('$i', '$j', '0" . $part1from . ":00:00', '" . $part1to . ":00:00', '$active');"
                );
            }
        }

        return true;
    }

    public static function insertRadiologys($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            $point = View::generate_random_point(self::$CENTER, rand(1, self::$MAX_MILE));
            DB::statement(
                "INSERT INTO `radiologies` "
                    . "(`id`, `name`, `phone`, "
                    . "`email`, `password`, "
                    . "`active`, `city`, `area`, "
                    . "`latt`, `lang`, `radiology_doctor_id`)" .
                    "VALUES "
                    . "('$i', 'lab$i', '" . (time() + $i) . "', 'lab$i@" . self::$DOMAIN . "', '123456',"
                    . "'1', '" . rand(1, 5) . "', '" . rand(1, 5) . "', '$point[0]', '$point[1]', '" . rand(1, 5) . "')"
            );
            // insert radiology insurance
            DB::statement(
                "INSERT INTO `radiology_insurances` (`radiology_id`, `insurance_id`)" .
                    "VALUES ('$i', '" . rand(1, 5) . "')"
            );

            // insert radiology working hours
            for ($j = 1; $j <= 7; $j++) {
                $part1from = rand(8, 9);
                $part1to = rand(20, 23);

                if ($j < 6)
                    $active = 1;
                else
                    $active = 0;

                DB::statement(
                    "INSERT INTO `radiology_working_hours` "
                        . "(`radiology_id`, `day`, `part_from`, `part_to`, `active`"
                        . ") VALUES "
                        . "('$i', '$j', '0" . $part1from . ":00:00', '" . $part1to . ":00:00', '$active');"
                );
            }
        }

        return true;
    }

    public static function insertArea($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `areas` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('area$i', 'area$i', 'area$i')"
            );
        }

        return true;
    }

    public static function insertRays($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `rays` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('ray$i', 'ray$i', 'ray$i')"
            );
        }

        return true;
    }

    public static function initSettings()
    {
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('1', 'distance', '" . self::$KM . "')");
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('2', 'phone', '" . self::$PHONE . "')");
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('3', 'max_reservation_for_patient', '" . self::$MAXRESERVATIONFORPATIENT . "')");
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('4', 'max_pharmacy_order', '" . self::$MAXPHARMACYORDER . "')");
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('5', 'max_order_details', '" . self::$MAXPHARMACYORDERDETAILS . "')");
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('6', 'max_lab_order', '" . self::$MAXLABORDER . "')");
        DB::statement("INSERT INTO `settings` (`id`, `option`, `value`) VALUES ('7', 'max_radiology_order', '" . self::$MAXRADIOLOGYORDER . "')");

        DB::statement("INSERT INTO `users` "
            . "(`id`, `name`, `email`, `password`) VALUES "
            . "('1', 'admin', 'admin@doctoraak.com', 'admin');");

        DB::statement("INSERT INTO `user_roles` (`id`, `user_id`, `role`) VALUES "
            . "('1', '1', '9');");

        return true;
    }

    public static function insertDegrees($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `degrees` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('degree$i', 'degree$i', 'degree$i')"
            );
        }

        return true;
    }

    public static function insertDoctorsOfModels($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `lab_doctors` (`name`, `username`, `password`)" .
                    "VALUES ('lab_doctor$i', 'lab_doctor$i', '123456')"
            );
            DB::statement(
                "INSERT INTO `radiology_doctors` (`name`, `username`, `password`)" .
                    "VALUES ('radiology_doctor$i', 'radiology_doctor$i', '123456')"
            );
            DB::statement(
                "INSERT INTO `pharmacy_doctors` (`name`, `username`, `password`)" .
                    "VALUES ('pharmacy_doctor$i', 'pharmacy_doctor$i', '123456')"
            );
        }

        return true;
    }

    public static function insertUserInsurances($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `user_insurances` (`name`, `email`, `password`, `insurance_id`)" .
                    "VALUES ('user_insurance$i', 'user$i@" . self::$DOMAIN . "', '123456', '" . rand(1, 5) . "')"
            );
        }

        return true;
    }

    public static function insertViews($number)
    {
        $month = date("Y-m");

        for ($i = 0; $i < $number; $i++) {
            $view = new View;
            $view->ip = "" . rand(0, 255) . "." . rand(0, 255) . "." . rand(0, 255) . "." . rand(0, 255);
            $view->date = $month . "-" . rand(1, 30);
            $points = View::generate_random_point(self::$CENTER, rand(1, self::$MAX_MILE));
            $view->lng = $points[1];
            $view->lat = $points[0];

            $view->save();
        }

        return true;
    }

    public static function insertInsurances($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `insurances` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('insurance$i', 'insurance$i', 'insurance$i')"
            );
        }

        return true;
    }

    public static function insertSpecializations($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `specializations` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('speciailization$i', 'speciailization$i', 'speciailization$i')"
            );
        }

        return true;
    }

    public static function insertAnalysis($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `analyses` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('analysis$i', 'analysis$i', 'analysis$i')"
            );
        }

        return true;
    }

    public static function insertCity($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `cities` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('city$i', 'city$i', 'city$i')"
            );
        }

        return true;
    }

    public static function insertMedicines($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `medicines` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('medicine$i', 'medicine$i', 'medicine$i')"
            );
        }

        return true;
    }

    public static function insertMedicineTypes($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            DB::statement(
                "INSERT INTO `medicine_types` (`name`, `name_ar`, `name_fr`)" .
                    "VALUES ('medicine_type$i', 'medicine_type$i', 'medicine_type$i')"
            );
        }

        return true;
    }

    public static function insertIncubation($number)
    {
        for ($i = 1; $i < $number; $i++) {
            $point = View::generate_random_point(self::$CENTER, rand(1, 20));
            DB::statement("INSERT INTO `incubations` "
                . "(`name`, `name_ar`, `name_fr`, `description`, `description_ar`, `description_fr`, `city`, `area`, `lng`, `lat`, `bed_number`, `rate`) VALUES "
                . "('incubation$i',  'incubation$i',     'incubation$i',     'incubationDescription$i',         'incubationDescription$i',            'incubationDescription$i',            '" . rand(1, 4) . "',    '" . rand(1, 4) . "',    '" . $point[1] . "', '" . $point[0] . "', '" . rand(5, 20) . "', '" . rand(1, 5) . "');");
        }
    }

    public static function insertIcu($number)
    {
        for ($i = 1; $i < $number; $i++) {
            $point = View::generate_random_point(self::$CENTER, rand(1, 20));
            DB::statement("INSERT INTO `icus` "
                . "(`name`, `name_ar`, `name_fr`, `description`, `description_ar`, `description_fr`, `city`, `area`, `lng`, `lat`, `bed_number`, `rate`) VALUES "
                . "('icu$i',  'icu$i',     'icu$i',     'icu$i',         'icu$i',            'icu$i',            '" . rand(1, 4) . "',    '" . rand(1, 4) . "',    '" . $point[1] . "', '" . $point[0] . "', '" . rand(5, 20) . "', '" . rand(1, 5) . "');");
        }
    }

    public static function insertPharmacy($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            $point = View::generate_random_point(self::$CENTER, rand(1, self::$MAX_MILE));
            DB::statement(
                "INSERT INTO `pharmacies` "
                    . "(`id`, `name`, `phone`, "
                    . "`email`, `password`, "
                    . "`active`, `address`, "
                    . "`latt`, `lang`, `pharmacy_doctor_id`)" .
                    "VALUES "
                    . "('$i', 'pharmacy$i', '" . (time() + $i) . "', 'pharmacy$i@" . self::$DOMAIN . "', '123456',"
                    . "'1', 'address$i', '$point[0]', '$point[1]', '" . rand(1, 5) . "')"
            );
            // insert lab insurance
            DB::statement(
                "INSERT INTO `pharmacy_insurances` (`pharmacy_id`, `insurance_id`)" .
                    "VALUES ('$i', '" . rand(1, 5) . "')"
            );

            // insert pharmacy working hours
            for ($j = 1; $j <= 7; $j++) {
                $part1from = rand(7, 9);
                $part1to = rand(20, 23);

                if ($j < 6)
                    $active = 1;
                else
                    $active = 0;

                DB::statement(
                    "INSERT INTO `pharmacy_working_hours` "
                        . "(`pharmacy_id`, `day`, `part_from`, `part_to`, `active`"
                        . ") VALUES "
                        . "('$i', '$j', '0" . $part1from . ":00:00', '" . $part1to . ":00:00', '$active');"
                );
            }
        }

        return true;
    }

    public static function insertPatients($number)
    {
        for ($i = 1; $i <= $number; $i++) {
            $n = time() . rand(5555, 999999999);
            DB::statement(
                "INSERT INTO `patients` ("
                    . "`name`, "
                    . "`name_ar`, "
                    . "`name_fr`, "
                    . "`phone`, "
                    . "`sms_code`, "
                    . "`api_token`, "
                    . "`email`, "
                    . "`gender`, "
                    . "`password`, "
                    . "`active`, "
                    . "`insurance_id`, "
                    . "`birthdate`, "
                    . "`photo`, "
                    . "`insurance_code`, "
                    . "`address`, "
                    . "`address_ar`, "
                    . "`address_fr`"
                    . ") VALUES ('patient$i', 'patient$i', 'patient$i', "
                    . "'" . time() . "', '" . time() . "', '" . time() . "', "
                    . "'patient$i@" . self::$DOMAIN . "', "
                    . "'male', '" . bcrypt('12345678') . "', '1', '" . rand(1, 5) . "',"
                    . "'2019-02-02', 'patient.png', '" . (time() + $i) . "', 'address', 'address', 'address')"
            );
        }

        return true;
    }

    public static function fillDB($number = 50)
    {
        echo "<pre>";
        self::initSettings();
        echo "settings init done <br>";

        self::insertAnalysis(5);
        echo "5 analysis done <br>";

        self::insertArea(5);
        echo "5 area done <br>";

        self::insertCity(5);
        echo "5 city done <br>";

        self::insertRays(5);
        echo "5 ray done <br>";

        self::insertDegrees(5);
        echo "5 degree done <br>";

        self::insertMedicines(5);
        echo "5 medicine done <br>";

        self::insertMedicineTypes(5);
        echo "5 medicine type done <br>";

        self::insertInsurances(5);
        echo "5 insurance done <br>";

        self::insertUserInsurances(5);
        echo "5 user insurance done <br>";

        self::insertDoctorsOfModels(5);
        echo "5 pharmacy doctor done <br>";
        echo "5 lab doctor done <br>";
        echo "5 radiology doctor done <br>";

        self::insertSpecializations(5);
        echo "5 sepcialization done <br>";

        //
        self::insertDoctors($number);
        echo "$number doctors done <br>";

        self::insertClinics($number, $number);
        echo "$number clinic done <br>";

        self::insertIncubation($number);
        echo "$number incubation done <br>";

        self::insertIcu($number);
        echo "$number icu done <br>";

        self::insertLabs($number);
        echo "$number lab done <br>";

        self::insertPatients($number);
        echo "$number patient done <br>";

        self::insertPharmacy($number);
        echo "$number pharmacy done <br>";

        self::insertRadiologys($number);
        echo "$number radiology done <br>";

        self::insertViews($number);
        echo "$number view done <br>";

        echo "<pre>";
    }
}
