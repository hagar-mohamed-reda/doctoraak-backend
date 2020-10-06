<?php

namespace App;
use App\helper\Helper;
use Illuminate\Database\Eloquent\Model;

class RadiologyOrder extends Model
{


    protected $table = 'radiology_orders';
    
     protected $fillable = [
      'photo',
      'radiology_id',
      'patient_id'

    ];
     
    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }


    public function radiology()
    {
        return $this->belongsTo('App\Radiology', 'radiology_id');
    }



    public function radiology_order_details()
    {
        return $this->hasMany('App\RadiologyOrderDetails', 'radiology_order');
    }

    public function getJson()
    {
        $this->patient = $this->patient()->first();
        // $this->radiology = $this->radiology()->first();
         $this->photo = url('/image/radiologyorder/') . "/" . RadiologyOrder::find($this->id)->photo;
       
        $this->details = Helper::jsonFilter($this->radiology_order_details()->get());

        return $this;
    }
}
