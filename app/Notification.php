<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\helper\Helper;

class Notification extends Model
{

    protected $table = 'notifications';
    protected $fillable = [
        "title_ar", "title_en", "message_ar", "message_en", "user_type", "user_id", "order_id", "icon", "sent", "seen", "order_type"
    ];
    protected $appends = [
        "order"
    ];

    public function getOrderAttribute() {
        $order = null;

        if (!$this->order_id)
            return $order;
        // patient
        try {
            if ($this->order_type == "LAB")
                $order = (LabOrder::find($this->order_id)) ? LabOrder::find($this->order_id)->getJson() : null;

            else if ($this->order_type == "RADIOLOGY")
                $order = (RadiologyOrder::find($this->order_id)) ? RadiologyOrder::find($this->order_id)->getJson() : null;

            else if ($this->order_type == "PHARMACY")
                $order = (PharmacyOrder::find($this->order_id)) ? PharmacyOrder::find($this->order_id)->getJson() : null;

            else if ($this->order_type == "DOCTOR")
                $order = (ClinicOrder::find($this->order_id)) ? ClinicOrder::find($this->order_id)->getJson() : null;

            // for vendor
            if ($this->user_type == "LAB")
                $order = (LabOrder::find($this->order_id)) ? LabOrder::find($this->order_id)->getJson() : null;

            else if ($this->user_type == "RADIOLOGY")
                $order = (RadiologyOrder::find($this->order_id)) ? RadiologyOrder::find($this->order_id)->getJson() : null;

            else if ($this->user_type == "PHARMACY")
                $order = (PharmacyOrder::find($this->order_id)) ? PharmacyOrder::find($this->order_id)->getJson() : null;

            else if ($this->user_type == "DOCTOR")
                $order = (ClinicOrder::find($this->order_id)) ? ClinicOrder::find($this->order_id)->getJson() : null;
        } catch (\Exception $exc) {
            
        }

        return $order;
    }

    public static function notify($title_ar, $title_en, $message_ar, $message_en, $tokens, $userId, $userType, $orderId = null, $orderType = null) {

        try {
            $notification = Notification::create([
                        "title_en" => $title_en,
                        "title_ar" => $title_ar,
                        "message_en" => $message_en,
                        "message_ar" => $message_ar,
                        "order_id" => $orderId,
                        "user_id" => $userId,
                        "order_type" => $orderType,
                        "user_type" => $userType
            ]);


            $data = [
                "title_ar" => $title_ar,
                "title_en" => $title_en,
                "body_ar" => $message_ar,
                "body_en" => $message_en,
            ];


            return Helper::firebaseNotification($tokens, $data);
        } catch (\Exception $e) {
            
        }
    }
}
