<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    public static $TYPE_CLINIC = 1;
    public static $TYPE_PHARMACY = 2;
    public static $TYPE_LAB = 3;
    public static $TYPE_RADIOLOGY = 4;

    public function getName() {
        if ($this->user_type == self::$TYPE_CLINIC) {
            return Clinic::find($this->user_id)->doctor->name;
        }else if ($this->user_type == self::$TYPE_PHARMACY) {
            return Pharmacy::find($this->user_id)->name;
        }else if ($this->user_type == self::$TYPE_LAB) {
            return Lab::find($this->user_id)->name;
        }else if ($this->user_type == self::$TYPE_RADIOLOGY) {
            return Radiology::find($this->user_id)->name;
        } else
            return '';
    }

    public function getModelName() {
        if ($this->user_type == Payment::$TYPE_CLINIC) {
            return "clinic";
        }else if ($this->user_type == Payment::$TYPE_PHARMACY) {
            return "pharmacy";
        }else if ($this->user_type == Payment::$TYPE_LAB) {
            return "lab";
        }else if ($this->user_type == Payment::$TYPE_RADIOLOGY) {
            return "radiology";
        } else
            return '';
    }

    public static function getPaymentModelValue($pays) {
        $paymentModel = [];
        foreach($pays as $pay) {
            if (isset($paymentModel[$pay->user_type])) {
                $paymentModel[$pay->user_type]["value"] += $pay->value;
            } else {
                $paymentModel[$pay->user_type] = [
                    "value" => $pay->value,
                    "name" => $pay->getModelName(),
                    "color" => helper\Helper::randColor()
                ];
            }
        }

        return $paymentModel;
    }
}
