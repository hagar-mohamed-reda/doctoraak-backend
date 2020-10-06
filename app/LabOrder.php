<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\Helper;

class LabOrder extends Model
{

    protected $table = 'lab_orders';
    
    
    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public function lab()
    {
        return $this->belongsTo('App\Lab', 'lab_id');
    } 

    public function lab_order_details()
    {
        return $this->hasMany('App\LabOrderDetails', 'lab_order');
    }
    
    /**
     * return object as json 
     * 
     * @return \App\LabOrder
     * 
     */
    public function getJson() {
        $this->patient = $this->patient()->first();
       // $this->lab = $this->lab()->first();
         $this->photo = url('/image/laborder/') . "/" . LabOrder::find($this->id)->photo;
         $this->details = Helper::jsonFilter($this->lab_order_details()->get());
        
        return $this;
    }
}
