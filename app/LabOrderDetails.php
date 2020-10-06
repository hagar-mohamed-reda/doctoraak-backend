<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabOrderDetails extends Model
{


    protected $table = 'lab_order_details';
    public function lab_order()
    {
        return $this->belongsTo('App\LabOrderDetails', 'lab_order');
    }

    public function analysis()
    {
        return $this->belongsTo('App\Analysis', 'analysis_id');
    }
    
    /**
     * return object as json 
     * 
     * @return \App\LabOrderDetails
     * 
     */
    public function getJson() {
        $this->analysis = $this->analysis()->first();
        
        return $this;
    }
}
