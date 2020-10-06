<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PharmacyInsurance extends Model
{



    protected $table = 'pharmacy_insurances';
    public function insurance()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo('App\Pharmacy', 'pharmacy_id');
    }
}
