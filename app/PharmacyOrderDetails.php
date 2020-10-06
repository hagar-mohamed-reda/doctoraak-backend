<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PharmacyOrderDetails extends Model
{
 
    protected $table = 'pharmacy_order_details';
    public function pharmacy_order()
    {
        return $this->belongsTo('App\PharmacyOrderDetails', 'pharmacy_order');
    }

    public function medicine()
    {
        return $this->belongsTo('App\Medicine', 'medicine_id');
    }

    public function medicine_type()
    {
        return $this->belongsTo('App\MedicineType', 'medicine_type_id');
    }

     public function getJson()
    {
        $this->medicine = $this->medicine;
        $this->medicine_type = $this->medicine_type;

        return $this;
    }
}
