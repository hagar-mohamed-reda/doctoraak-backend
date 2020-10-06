<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\Helper;
use App\helper\ViewBuilder;

use DB;

class Pharmacy extends Model
{


    protected $table = 'pharmacies';
     protected $fillable = [
        'name',
        'name_ar',
        'name_fr',
        'email',
        'phone',
        'phone2',
        'address',
        'address_ar',
        'firebase_token',
        'address_fr',
        'lng',
        'lat', 
        'city_id',
        'area_id',
        'photo',
        'active',
        'delivery',
        'password',
        'api_token',
        'sms_code',
        'avaliable_days',
        'pharmacy_doctor_id',
    ];
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = [
        'insurance_company', 'insurance_id', 'url'
    ];
    
    public function getUrlAttribute() {
        return url('image/pharmacy') . "/" . $this->photo;
    }
    
    public function getInsuranceIdAttribute() {
        return implode(',', $this->pharmacy_insurances()->select('insurance_id')->distinct()->get(['insurance_id'])->pluck('insurance_id')->toArray());
    }
    
    public function getInsuranceCompanyAttribute() {
        $ids = $this->pharmacy_insurances()->pluck("insurance_id");
        return Insurance::whereIn("id", $ids)->get();
    }
    
    public function pharmacy_insurances()
    {
        return $this->hasMany('App\PharmacyInsurance');
    }

    public function working_hours()
    {
        return $this->hasMany('App\PharmacyWorkingHours');
    }

    public function doctor()
    {
        return $this->belongsTo('App\PharmacyDoctor', 'pharmacy_doctor_id');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function pharmacy()
    {
        return $this->hasMany('App\PharmacyRequest');
    }
    
    public function orders()
    {
        return $this->hasMany('App\PharmacyOrder', 'pharmacy_id');
    }
    
    public function insuranceNames() {
        $insuranceIds = $this->pharmacy_insurances()->get(['insurance_id'])->pluck('insurance_id')->toArray();
        $insuranceNames = Insurance::whereIn('id', $insuranceIds)->get(['name'])->pluck('name')->toArray();
         
        if (count($insuranceNames) > 0)
            return implode(',', $insuranceNames);
        
        return "";
    }
    
    public function getJson()
    {
        // $this->doctor = $this->doctor()->first();
        // $this->doctor->photo = url('/image/doctor/') . "/" . $this->doctor->photo;
          $this->pharmacy_insurances = $this->pharmacy_insurances;
      //  $this->pharmacy_insurances->name = $this->pharmacy_insurances->name;
        $this->working_hours = $this->working_hours;
        $this->photo = url('/image/pharmacy/') . "/" . Pharmacy::find($this->id)->photo;

        return $this;
    }  
    
    public function getChartData() {
        $data = [];
        $dates = DB::select("select DISTINCT created_at from pharmacy_orders where pharmacy_id=" . $this->id . " order by created_at ");
        
        foreach($dates as $date) {
            $data[$date->created_at] = PharmacyOrder::whereBetween("created_at", [$date->created_at, $date->created_at])->where("pharmacy_id", $this->id)->count();
        }
        
        return $data;
    }
    
    public static function notify($title_ar, $title_en,$message_ar, $message_en, $icon, $userId, $orderId=null) {
        $userType = "PHARMACY";
        try{
            $notification = Notification::create([
                "title_en" => $title_en,
                "title_ar" => $title_ar,
                "message_en" =>$message_en,
                "message_ar"=>$message_ar,
                "icon"=>$icon,
                "order_id"=> $orderId,
                "user_id"=>$userId,
                "user_type"=>$userType
                ]);
                
                
                      
            $data = [
                "title_ar" => $title_ar,
                "title_en" => $title_en,
                "body_ar" => $message_ar,
                "body_en" => $message_en,    
            ];
            
            $token = [
                Pharmacy::find($userId)->firebase_token
            ];
                
            return Helper::firebaseNotification($token, $data);
         
            
        }catch(\Exception $e){}
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
        ];
        foreach (City::get(['id', 'name']) as $item)
            $cities[] = [$item->id, $item->name];

        $pharmacyDoctors = [
            ['', __('all')]
        ];
        foreach (PharmacyDoctor::get(['id', 'name']) as $item)
            $pharmacyDoctors[] = [$item->id, $item->name];

        $areas = [ 
        ];
        foreach (Area::get(['id', 'name', 'city_id']) as $item)
            $areas[] = [$item->id, $item->name, $item->city_id];
 

        $insurances = [ 
        ];
        foreach (Insurance::get(['id', 'name']) as $item)
            $insurances[] = [$item->id, $item->name];
 

        $builder->setAddRoute(url('/pharmacy/store'))
                ->setEditRoute(url('/pharmacy/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), 'required' => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), 'required' => false])
                
                ->setCol(["name" => "email", "label" => __('email'), 'type' => "email", "required" => false])
                ->setCol(["name" => "phone", "label" => __('phone')])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"]) 
                ->setCol(["name" => "phone2", "label" => __('phone2'), "required" => false]) 
                ->setCol(["name" => "city_id", "label" => __('city'), "type" => "select", "data" => $cities])
                ->setCol(["name" => "area_id", "label" => __('area'), "type" => "select", "data" => $areas])
                ->setCol(["name" => "pharmacy_doctor_id", "label" => __('pharmacy_doctor'), "type" => "select", "data" => $pharmacyDoctors])
                ->setCol(["name" => "map", "label" => __('location'), "type" => "map"])
                ->setCol(["name" => "photo", "label" => __('photo'), "type" => "image", "required" => false])
                ->setCol(["name" => "avaliable_days", "label" => __('available_days'), 'type' => "number"])
                ->setCol(["name" => "insurance_id", "label" => __('insurance_companies'), "type" => "multi_select", "data" => $insurances])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"]) 
                ->setCol(["name" => "delivery", "label" => __('delivery'), "type" => "checkbox"])  
                 
                ->setUrl(url('/image/pharmacy'))
                ->build();

        return $builder;
    }

    
    
    
}


