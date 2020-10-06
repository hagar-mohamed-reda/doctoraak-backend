<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\ViewBuilder;

class Insurance extends Model
{

    protected $table = 'insurances';
    
    protected $fillable = [
        "name", "name_ar", "name_fr", "photo"
    ];
 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = [
        'url'
    ];
    
    public function getUrlAttribute() {
        if ($this->photo == 'insurance.png')
            return url('/image/insurance.png');
        
        return  url('/image/insurance') . "/" . $this->photo;
    }
    
    public function patients() {
        return $this->hasMany('App\Patient');
    }

    public function doctor_insurances() {
        return $this->hasMany('App\DoctorInsurance');
    }

    public function user_insurances() {
        return $this->hasMany('App\UserInsurance');
    }

    public function pharmacy_insurances() {
        return $this->hasMany('App\PharmacyInsurance');
    }

    public function lab_insurances() {
        return $this->hasMany('App\LabInsurance');
    }

    public function radiology_insurances() {
        return $this->hasMany('App\RadiologyInsurance');
    }

    public function getPharmacyOrdersRequiredInsuranceAccept() {
        $orders = PharmacyOrder::join("patients", "patients.id", "=", "patient_id")->
                where("insurance_id", $this->id)->
                where("pharmacy_id", "!=", null)->
                where("insurance_accept", 'required')->
                select("*", "pharmacy_orders.id as code", "pharmacy_orders.photo as order_image")->
                get();

        $filteredOrders = [];

        foreach ($orders as $order) { 
            if ($order->patient->insurance_id  = $this->id) {
                $filteredOrders[] = $order; 
            }
        }

        return $filteredOrders;
    }

    public function getLabOrdersRequiredInsuranceAccept() {
        $orders = LabOrder::join("patients", "patients.id", "=", "patient_id")->
                where("insurance_id", $this->id)->
                where("insurance_accept", 'required')->
                select("*", "lab_orders.id as code", "lab_orders.photo as order_image")->
                get();

        $filteredOrders = [];

        foreach ($orders as $order) {
            if ($order->lab->lab_insurances->where("insurance_id", $order->patient->insurance_id)->count() > 0) {
                $filteredOrders[] = $order;
            }
        }

        return $filteredOrders;
    }

    public function getRadiologyOrdersRequiredInsuranceAccept() {
        $orders = RadiologyOrder::join("patients", "patients.id", "=", "patient_id")->
                where("insurance_id", $this->id)->
                where("insurance_accept", 'required')->
                select("*", "radiology_orders.id as code", "radiology_orders.photo as order_image")->
                get();

        $filteredOrders = [];

        foreach ($orders as $order) {
            if ($order->radiology->radiology_insurances->where("insurance_id", $order->patient->insurance_id)->count() > 0) {
                $filteredOrders[] = $order;
            }
        }

        return $filteredOrders;
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");
 

        $builder->setAddRoute(url('/insurance/store'))
                ->setEditRoute(url('/insurance/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), "required" => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), "required" => false]) 
                ->setCol(["name" => "photo", "label" => __('photo'), 'type' => 'image']) 
                ->setUrl(url('/image/insurance'))
                ->build();

        return $builder;
    }
}
