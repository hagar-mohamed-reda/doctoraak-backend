<?php

namespace App;
use App\helper\Helper;
use App\helper\ViewBuilder;

use Illuminate\Database\Eloquent\Model;

use DB;

class Patient extends Model
{

    /**
     * table name of model
     * 
     * @var String
     */
    protected $table = 'patients';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_ar',
        'name_fr',
        'email',
        'phone',
        'gender',
        'birthdate',
        'firebase_token',
        'photo',
        'insurance_code',
        'insurance_code_id',
        'insurance_id',
        'address',
        'password',
        'api_token',
        'sms_code',
        'address_ar',
        'address_fr',
        'block_days',
        'block_date'

    ];
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = [
        'url', 'insurance'
    ];
    
    
    public function getInsuranceAttribute() {
        return $this->insurance()->first();
    }
    
    /**
     * defualt block days
     *
     * @var Integer
     */
    public static $BLOCK_DAYS = 7;
    
    
    /**
     * 
     */
    public function getUrlAttribute() { 
        $url = url('/image/patient/') . "/" . $this->photo;
        
        if ($this->photo == 'patient.png') {
            if ($this->gender == 'female')
                $url = url('/image/female.png');
        }
        
        return $url;
    }
    
    
    /** 
     * un block patient account
     * 
     * @return null
     */
    public function unblock() {
        if ($this->active == '1' && $this->block_days == null)
            return;
        
        // init block days 
        $date = date("Y-m-d");
        
        $start = \Carbon\Carbon::parse($this->block_date);
        $end =  \Carbon\Carbon::parse($date);
        
        $days = $end->diffInDays($start);
        
        if ($days >= $this->block_days) {
            $this->active = '1';
            $this->update();
            
            Patient::notify(
                trans("messages.warning", [], "ar"),
                trans("messages.warning", [], "en"),
                trans("messages.your_account_unblock", [], "ar"),
                trans("messages.your_account_unblock", [], "en"),
                "",
                $this->id
            );
        }  
    }
    
    /**
     * block account of patient of some days 
     * and notify the patient with number of block days
     * 
     * @param String $reason => the reason of block
     * @return null
     */
    public function block($reason) {
        // init block days
        $blockDays = $this->block_days? $this->block_days * 2 : self::$BLOCK_DAYS;
        
        // deactive patient account
        $this->active = '0';
        $this->block_days = $blockDays;
        $this->block_date = date("Y-m-d");
        $this->api_token = null;
        $this->update();
        
        Patient::notify(
            trans("messages.warning", [], "ar"),
            trans("messages.warning", [], "en"),
            trans("messages.your_account_block_for", ["days" => $blockDays, "reason" => $reason["ar"]], "ar"),
            trans("messages.your_account_block_for", ["days" => $blockDays, "reason" => $reason["en"]], "en"),
            "",
            $this->id
        );
    }
    
     
    /**
     * add spam for the patient
     * if the patient cancel any order
     * 
     * @return null
     */
    public function spam($spam) { 
        $date = date("Y-m-d");
        PatientSpam::create([
            "date" => $date,
            "patient_id" => $this->id,
            "spam" => $spam
        ]);
        
        $spamNumber = $this->spams()->where("date", $date)->count();
        
        if ($spamNumber >= 2) {
            $reason = $this->getBlockReason();
            $this->block($reason);
        }
    }
    
    /**
     * get reason of block
     * 
     * @return Array
     */
    public function getBlockReason() {
        $reason = [
            "ar" => "",
            "en" => "",
        ];
        $spam = $this->spams()->latest()->first();
        
        if ($spam) {
            if ($spam->spam == "CANCEL_ORDER") { 
                $reason = [
                    "ar" => "قمت بعمل الغاء لطلبات اكثر من مره",
                    "en" => "you have been canceled more than one order."
                ];
            }
        }
        
        return $reason;
    }
    
    /**
     * patient spams
     * 
     * @return Collection
     */
    public function spams()
    {
        return $this->hasMany('App\PatientSpam', 'patient_id');
    }
    
    
    public function getChartData() {
        $data = [];
        $dates = [];
        $dates1 = DB::select("select DISTINCT created_at from clinic_orders where patient_id=" . $this->id . " order by created_at ");
        $dates2 = DB::select("select DISTINCT created_at from lab_orders where patient_id=" . $this->id . " order by created_at ");
        $dates3 = DB::select("select DISTINCT created_at from radiology_orders where patient_id=" . $this->id . " order by created_at ");
        $dates4 = DB::select("select DISTINCT created_at from pharmacy_orders where patient_id=" . $this->id . " order by created_at ");
        
        foreach($dates1 as $date) {
            $d = $date->created_at;
            $d = date("Y-m-d", strtotime($d));
            
            if (!isset($dates[$d]))
                $dates[$d] = [0,0,0,0];
        }
        
        foreach($dates2 as $date) {
            $d = $date->created_at;
            $d = date("Y-m-d", strtotime($d));
            
            if (!isset($dates[$d]))
                $dates[$d] = [0,0,0,0];
        }
        foreach($dates3 as $date) {
            $d = $date->created_at;
            $d = date("Y-m-d", strtotime($d));
            
            if (!isset($dates[$d]))
                $dates[$d] = [0,0,0,0];
        }
        
        foreach($dates4 as $date) {
            $d = $date->created_at;
            $d = date("Y-m-d", strtotime($d));
            
            if (!isset($dates[$d]))
                $dates[$d] = [0,0,0,0];
        }
        
        foreach($dates as $key => $date) {
            $index = $key;
            $key = date("Y-m-d H:i:s", strtotime($key)); 
            $data[$index] = [
                ClinicOrder::whereBetween("created_at", [$key, $key])->where("patient_id", $this->id)->count(),
                LabOrder::whereBetween("created_at", [$key, $key])->where("patient_id", $this->id)->count(),
                RadiologyOrder::whereBetween("created_at", [$key, $key])->where("patient_id", $this->id)->count(),
                PharmacyOrder::whereBetween("created_at", [$key, $key])->where("pharmacy_id", $this->id)->count()    
            ];
        }
        
        return $data;
    }
    
    public function orders()
    {
        return $this->hasMany('App\ClinicOrder');
    }

    public function lab_orders()
    {
        return $this->hasMany('App\LabOrder');
    }

    public function radiology_orders()
    {
        return $this->hasMany('App\RadiologyOrder');
    }
    
    public function blocks()
    {
        return $this->hasMany('App\PatienBlock');
    }

    public function rates()
    {
        return $this->hasMany('App\PatientRate');
    }

    public function pharmacy_orders()
    {
        return $this->hasMany('App\PharmacyOrder');
    }


    public function insurance()
    {
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }

    public function favourites()
    {
        return $this->hasMany('App\Favourite');
    }
    
    public static function notify($title_ar,$title_en,$message_ar, $message_en, $icon, $userId, $orderId=null, $orderType=null) {
        $userType = "PATIENT";
        try{
           // DB::statement("delete from notifications where user_type='PATIENT' and user_id=$userId ");
            $notification = Notification::create([
                "title_en" => $title_en,
                "title_ar" => $title_ar,
                "message_en" =>$message_en,
                "message_ar"=>$message_ar,
                "icon"=>$icon? $icon : 'icon',
                "user_id"=>$userId,
                "user_type"=>  $userType,
                "order_id"=>  $orderId,
                "order_type"=>  $orderType
               
                ]);
                
          
                      
            $data = [
                "title_ar" => $title_ar,
                "title_en" => $title_en,
                "body_ar" => $message_ar,
                "body_en" => $message_en,    
            ];
            
            $token = [
                Patient::find($userId)->firebase_token
            ];
                
            Helper::firebaseNotification($token, $data);  
        }catch(Exception $e){}
    }
    
    public function getJson()
    { 
        $photo = $this->photo;
        $this->photo = url('/image/patient/') . "/" . $this->photo;
        
        if ($photo == 'patient.png') {
            if ($this->gender == 'female')
                $this->photo = url('/image/female.png');
        }
        
        return $this;
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");
        $gender = [
            ['male', __('male')],
            ['female', __('female')],
        ];
        
        $insurances = [];
        
        foreach(Insurance::all() as $item)
            $insurances[] = [$item->id, $item->name];
        
        $builder->setAddRoute(url('/patient/store'))
                ->setEditRoute(url('/patient/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), "required" => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), "required" => false])
                ->setCol(["name" => "email", "label" => __('email'), "type" => "email"])
                ->setCol(["name" => "phone", "label" => __('phone')])
                ->setCol(["name" => "birthdate", "label" => __('birthdate'), "type" => "date", "required" => false])
                ->setCol(["name" => "gender", "label" => __('gender'), "type" => "select", "data" => $gender])
                ->setCol(["name" => "insurance_id", "label" => __('insurance'), "type" => "select", "data" => $insurances, "required" => false])
                ->setCol(["name" => "photo", "label" => __('photo'), "type" => "image", "required" => false])
                 
                ->setCol(["name" => "insurance_code_id", "label" => __('insurance_code_id'), "required" => false])
                ->setCol(["name" => "address", "label" => __('address')])
                ->setCol(["name" => "address_ar", "label" => __('address_ar'), "required" => false])
                ->setCol(["name" => "address_fr", "label" => __('address_fr'), "required" => false])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password", "required" => false]) 
                
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                
                
                ->setUrl(url('/image/patient'))
                ->build();

        return $builder;
    }
    
}

