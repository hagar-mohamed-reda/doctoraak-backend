<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\Helper;

class  PharmacyOrder extends Model
{


    protected $table = 'pharmacy_orders';
    protected $fillable = [
        'pharmacy_id',
        'order_id',
        'patient_id',
        'notes'


    ];

    protected $appends = [
        'accept'
    ];

    public function getAcceptAttribute() {
        $ar = $this->getAcceptedRequest();

        return $ar? 1 : 0;
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo('App\Pharmacy', 'pharmacy_id');
    }

    public function pharmacy_requests()
    {
        return $this->hasMany('App\PharmacyRequest');
    }

    public function getAcceptedPharmacy() {
        return $this->getAcceptedRequest()->pharmacy;
    }

    public function getAcceptedRequest() {
        return $this->pharmacy_requests->where("accept", '1')->first();
    }

    public function pharmacy_order_details()
    {
        return $this->hasMany('App\PharmacyOrderDetails', 'pharmacy_order');
    }

     public function getJson()
    {
        $this->patient = $this->patient()->first();
        // $this->pharmacy = $this->pharmacy_requests()->first();

        $this->photo = url('/image/pharmacyorder') . "/" . $this->photo;
        $this->details = Helper::jsonFilter($this->pharmacy_order_details()->get());

        return $this;
    }
}
