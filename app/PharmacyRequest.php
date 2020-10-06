<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PharmacyRequest extends Model
{

    protected $table = 'pharmacy_requests';
    protected $fillable = [
        'accept',
    ];
    
    
    public function pharmacy_order()
    {
        return $this->belongsTo('App\PharmacyOrder', 'pharmacy_order_id');
    }


    public function pharmacy()
    {
        return $this->belongsTo('App\Pharmacy', 'pharmacy_id');
    }
}
