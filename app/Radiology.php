<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\Helper;
use App\helper\ViewBuilder;

use DB;


class Radiology extends Model
{

    protected $table = 'radiologies';

    protected $fillable = [ 
        'name',
        'name_ar',
        'name_fr',
        'phone',
        'phone2',
        'city_id',
        'area_id',
        'lng',
        'lat',
        'api_token',
        'firebase_token',
        'sms_code',
        'email',
        'password',
        'active',
        'delivery',
        'avaliable_days',
        'radiology_doctor_id',
        'photo'
    ];
    
    
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = [
        'insurance_company', 'url', 'insurance_id'
    ];
    
    public function getInsuranceIdAttribute() {
        return implode(',', $this->radiology_insurances()->select('insurance_id')->distinct()->get(['insurance_id'])->pluck('insurance_id')->toArray());
    }
     
    
    public function getUrlAttribute() {
        if (!$this->photo || $this->photo == 'radiology.png') {
            return url('/image/icon/doctor.png');
        }
        
        return url("image/radiology/" . $this->photo);
    }
    
    public function getInsuranceCompanyAttribute() {
        $ids = $this->radiology_insurances()->pluck("insurance_id");
        return Insurance::whereIn("id", $ids)->get();
    }
    
    public function doctor()
    {
        return $this->belongsTo('App\RadiologyDoctor', 'radiology_doctor_id');
    }

    public function orders()
    {
        return $this->hasMany('App\RadiologyOrder');
    }

    public function working_hours()
    {
        return $this->hasMany('App\RadiologyWorkingHours','radiology_id');
    }
 
    public function getChartData() {
        $data = [];
        $dates = DB::select("select DISTINCT created_at from radiology_orders where radiology_id=" . $this->id . " order by created_at ");
        
        foreach($dates as $date) {
            $data[$date->created_at] = RadiologyOrder::whereBetween("created_at", [$date->created_at, $date->created_at])->where("radiology_id", $this->id)->count();
        }
        
        return $data;
    }
    
    public function radiology_insurances()
    {
        return $this->hasMany('App\RadiologyInsurance');
    }

    public function insuranceNames()
    {
        $insurances = $this->radiology_insurances()->get();
        $insuranceNames = [];

        foreach ($insurances as $insurance) {
            $insuranceNames[] = $insurance->insurance->first()->name;
        }

        return $insuranceNames;
    }
     public function getJson()
    {
        $this->photo = url('/image/radiology/') . "/" . Radiology::find($this->id)->photo;
        $this->radiology_insurances = $this->radiology_insurances;
        $this->working_hours = $this->working_hours()->get();
      

        return $this;
    }
    public static function notify($title_ar, $title_en,$message_ar, $message_en, $icon, $userId, $orderId=null) {
        $userType = "LAB";
        try{
            $notification = Notification::create([
                "title_en" => $title_en,
                "title_ar" => $title_ar,
                "message_en" =>$message_en,
                "message_ar"=>$message_ar,
                "icon"=>$icon,
                "user_id"=>$userId,
                "order_id"=> $orderId,
               "user_type"=>$userType
                ]);
                
                      
            $data = [
                "title_ar" => $title_ar,
                "title_en" => $title_en,
                "body_ar" => $message_ar,
                "body_en" => $message_en,    
            ];
            
            $token = [
                Radiology::find($userId)->firebase_token
            ];
                
            return Helper::firebaseNotification($token, $data);
         
            
        }catch(\Exception $e){}
    }
    
    public function city() {
        return $this->belongsTo('App\City', 'city_id');
    }
    
    public function area() {
        return $this->belongsTo('App\Area', 'area_id');
    }
    
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     *       
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");


        $cities = [
            ['', __('all')]
        ];
        foreach (City::get(['id', 'name']) as $item)
            $cities[] = [$item->id, $item->name];

        $areas = [
            ['', __('all')]
        ];
        foreach (Area::get(['id', 'name', 'city_id']) as $item)
            $areas[] = [$item->id, $item->name, $item->city_id];

        $doctors = [
            ['', __('all')]
        ];
        foreach (RadiologyDoctor::get(['id', 'name']) as $item)
            $doctors[] = [$item->id, $item->name];
         

        $insurances = [ 
        ];
        foreach (Insurance::get(['id', 'name']) as $item)
            $insurances[] = [$item->id, $item->name];
 
        $builder->setAddRoute(url('/radiology/store'))
                ->setEditRoute(url('/radiology/update') . "/" . $this->id)
                ->setCol(["name" => "id", "radiologyel" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "radiologyel" => __('name')])
                ->setCol(["name" => "name_ar", "radiologyel" => __('name_ar'), 'required' => false])
                ->setCol(["name" => "name_fr", "radiologyel" => __('name_fr'), 'required' => false])
                ->setCol(["name" => "phone", "radiologyel" => __('phone')])
                ->setCol(["name" => "password", "radiologyel" => __('password'), 'type' => "password"])
                ->setCol(["name" => "phone2", "radiologyel" => __('phone2'), 'required' => false])
                ->setCol(["name" => "radiology_doctor_id", "radiologyel" => __('doctor'), "type" => "select", "data" => $doctors])
                ->setCol(["name" => "city_id", "radiologyel" => __('city'), "type" => "select", "data" => $cities])
                ->setCol(["name" => "area_id", "radiologyel" => __('area'), "type" => "select", "data" => $areas])
                ->setCol(["name" => "map", "radiologyel" => __('location'), "type" => "map"])
                ->setCol(["name" => "photo", "radiologyel" => __('photo'), "type" => "image", "required" => false]) 
                ->setCol(["name" => "email", "radiologyel" => __('email'), 'type' => "email", "required" => false]) 
                ->setCol(["name" => "insurance_id", "radiologyel" => __('insurance_companies'), "type" => "multi_select", "data" => $insurances])
                ->setCol(["name" => "active", "radiologyel" => __('active'), "type" => "checkbox"])
                ->setCol(["name" => "delivery", "radiologyel" => __('delivery'), "type" => "checkbox"])
                ->setCol(["name" => "avairadiologyle_days", "radiologyel" => __('avairadiologyle_days'), 'type' => "number"])
                 
                ->setUrl(url('/image/radiology'))
                ->build();

        return $builder;
    }
}

