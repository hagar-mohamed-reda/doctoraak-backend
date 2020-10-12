<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\helper\Helper;
use App\helper\ViewBuilder;

use DB;


class Lab extends Model
{

    protected $table = 'labs';

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
        'lab_doctor_id',
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

    public function getJson()
    {
        $this->photo = url('/image/lab/') . "/" . Lab::find($this->id)->photo;
        $this->lab_insurances = $this->lab_insurances;
        $this->working_hours = $this->working_hours()->get();


        return $this;
    }

    public function getInsuranceIdAttribute() {
        return implode(',', $this->lab_insurances()->select('insurance_id')->distinct()->get(['insurance_id'])->pluck('insurance_id')->toArray());
    }


    public function getUrlAttribute() {
        if (!$this->photo || $this->photo == 'lab.png') {
            return url('/image/icon/doctor.png');
        }

        return url("image/lab/" . $this->photo);
    }

    public function getInsuranceCompanyAttribute() {
        $ids = $this->lab_insurances()->pluck("insurance_id");
        return Insurance::whereIn("id", $ids)->get();
    }

    public function doctor()
    {
        return $this->belongsTo('App\LabDoctor', 'lab_doctor_id');
    }

    public function orders()
    {
        return $this->hasMany('App\LabOrder');
    }

    public function working_hours()
    {
        return $this->hasMany('App\LabWorkingHours','lab_id');
    }

    public function getChartData() {
        $data = [];
        $dates = DB::select("select DISTINCT created_at from lab_orders where lab_id=" . $this->id . " order by created_at ");

        foreach($dates as $date) {
            $data[$date->created_at] = LabOrder::whereBetween("created_at", [$date->created_at, $date->created_at])->where("lab_id", $this->id)->count();
        }

        return $data;
    }

    public function lab_insurances()
    {
        return $this->hasMany('App\LabInsurance');
    }

    public function insuranceNames()
    {
        $insurances = $this->lab_insurances()->get();
        $insuranceNames = [];

        foreach ($insurances as $insurance) {
            $insuranceNames[] = $insurance->insurance->first()->name;
        }

        return $insuranceNames;
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
                Lab::find($userId)->firebase_token
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
        foreach (LabDoctor::get(['id', 'name']) as $item)
            $doctors[] = [$item->id, $item->name];


        $insurances = [
        ];
        foreach (Insurance::get(['id', 'name']) as $item)
            $insurances[] = [$item->id, $item->name];

        $builder->setAddRoute(url('/lab/store'))
                ->setEditRoute(url('/lab/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "name_ar", "label" => __('name_ar'), 'required' => false])
                ->setCol(["name" => "name_fr", "label" => __('name_fr'), 'required' => false])
                ->setCol(["name" => "phone", "label" => __('phone')])
                ->setCol(["name" => "password", "label" => __('password'), 'type' => "password"])
                ->setCol(["name" => "phone2", "label" => __('phone2'), 'required' => false])
                ->setCol(["name" => "lab_doctor_id", "label" => __('doctor'), "type" => "select", "data" => $doctors])
                ->setCol(["name" => "city_id", "label" => __('city'), "type" => "select", "data" => $cities])
                ->setCol(["name" => "area_id", "label" => __('area'), "type" => "select", "data" => $areas])
                ->setCol(["name" => "map", "label" => __('location'), "type" => "map"])
                ->setCol(["name" => "photo", "label" => __('photo'), "type" => "image", "required" => false])
                ->setCol(["name" => "email", "label" => __('email'), 'type' => "email", "required" => false])
                ->setCol(["name" => "insurance_id", "label" => __('insurance_companies'), "type" => "multi_select", "data" => $insurances])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                ->setCol(["name" => "delivery", "label" => __('delivery'), "type" => "checkbox"])
                ->setCol(["name" => "available_days", "label" => __('available_days'), 'type' => "number"])

                ->setUrl(url('/image/lab'))
                ->build();

        return $builder;
    }
}

