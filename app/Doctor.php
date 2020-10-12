<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TheSeer\Tokenizer\Exception;
use App\helper\ViewBuilder;
use App\helper\Helper;
use DB;

class Doctor extends Model {

    protected $table = 'doctors';
    protected $fillable = [
        'name',
        'name_ar',
        'name_fr',
        'email',
        'phone',
        'phone2',
        'title',
        'gender',
        'specialization_id',
        'firebase_token',
        'photo',
        'degree_id',
        'cv',
        'cv2',
        'password',
        'active',
        'api_token',
        'sms_code',
        'reservation_rate',
        'degree_rate',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = [
        'insurance_company', 'url', 'cv_url', 'cv2_url'
    ];

    public function getUrlAttribute() {
        if (!$this->photo || $this->photo == 'doctor.png') {
            return url('/image/icon/doctor.png');
        }

        return url("image/doctor/" . $this->photo);
    }

    public function getCvUrlAttribute() {
        return url("file/doctor/" . $this->cv);
    }

    public function getCv2UrlAttribute() {
        return url("file/doctor/" . $this->cv2);
    }

    public function getInsuranceCompanyAttribute() {
        $ids = $this->doctor_insurances()->pluck("insurance_id");
        return Insurance::whereIn("id", $ids)->get();
    }

    public function degree() {
        return $this->belongsTo('App\Degree', 'degree_id');
    }

    public function specialization() {
        return $this->belongsTo('App\Specialization', 'specialization_id');
    }

      public function getJson()
    {
        $this->photo = url('/image/doctor/') . "/" . $this->photo;

        $this->cv = url('/file/doctor/') . "/" .  $this->cv;

         $this->doctor_insurances = $this->doctor_insurances;
        $this->degree = $this->degree;
        $this->specialization = $this->specialization;

        return $this;
    }

    public function clinics() {
        return $this->hasMany('App\Clinic', 'doctor_id');
    }

    public function blocks() {
        return $this->hasMany('App\PatientBlock');
    }

    public function rates() {
        return $this->hasMany('App\PatientRate');
    }

    public function favourites() {
        return $this->hasMany('App\Favourite');
    }

    public function getRate() {
        try {
            return (($this->degree_rate + $this->reservation_rate) / 10) * 10;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function doctor_insurances() {
        return $this->hasMany('App\DoctorInsurance');
    }


    /**
     * return specialization as array of two index
     * [specialization->id, specialization->name]
     *
     * @return array
     */
    public function getSpecializationArray() {
        $specializations = [];
        foreach (Specialization::all() as $sepcail) {
            $specializations[] = [$sepcail->id, $sepcail->name];
        }

        return $specializations;
    }


    /**
     * return degree as array of two index
     * [degree->id, degree->name]
     *
     * @return array
     */
    public function getDegreeArray() {
        $degrees = [];
        foreach (Degree::all() as $degree) {
            $degrees[] = [$degree->id, $degree->name];
        }

        return $degrees;
    }

    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");

        $insurances = [
        ];
        foreach (Insurance::get(['id', 'name']) as $item)
            $insurances[] = [$item->id, $item->name];


        $builder->setPageTitle("doctors")
                ->setAddRoute(url('/doctor/store'))
                ->setEditRoute(url('/doctor/update') . "/" . $this->id)
                ->setAddTitle("add doctor")
                ->setEditTitle("edit doctor")
                ->setCol(["name" => "id", "editable" => false])
                ->setCol(["name" => "name", "label" => "الاسم"])
                ->setCol(["name" => "name_ar", "label" => "الاسم بالعربيه", "required" => false, "important" => false])
                ->setCol(["name" => "name_fr", "label" => "الاسم بالفرنسيه", "required" => false, "important" => false])
                ->setCol(["name" => "phone", "label" => "الهاتف", "type" => "phone"])
                ->setCol(["name" => "email", "label" => "الاميل", "important" => false, "type" => "email"])
                ->setCol(["name" => "password", "label" => "كلمة المرور", "type" => "password"])
                ->setCol(["name" => "specialization_id", "label" => "التخصص", "type" => "select", "data" => $this->getSpecializationArray()])
                ->setCol(["name" => "degree_id", "label" => "الدرجه العلميه", "type" => "select", "data" => $this->getDegreeArray()])
                ->setCol(["name" => "gender", "label" => "النوع", "type"=> "select", "data"=> [["male", "male"],["female", "female"]]])
                ->setCol(["name" => "active", "label" => "التفعيل", "type" => "checkbox"])
                ->setCol(["name" => "sms_code", "label" => "sms code",  "editable" => false])
                ->setCol(["name" => "created_at", "label" => "created_at",  "editable" => false])
                ->setCol(["name" => "cv2", "label" => "السيره الذاتيه المعدله", "type" => "pdf", "required" => false])
                ->setCol(["name" => "cv", "label" => "السيره الذاتيه", "type" => "pdf", "required"=> false])
                ->setCol(["name" => "photo", "label" => "الصوره", "type" => "image", "required"=> false])
                ->setCol(["name" => "insurance_id", "label" => __('insurance_companies'), "type" => "multi_select", "data" => $insurances])
                ->setCol(["name" => "reservation_rate", "label" => "تقيم الحجزات", "type" => "rate", "col" => "w3-col l12 m12 s12"])
                ->setCol(["name" => "degree_rate", "label" => "تقيم الدرجه العلميه", "type" => "rate", "col" => "w3-col l12 m12 s12"])
                ->setUrl(url('/image/doctor'))
                ->build();

        return $builder;
    }

  public static function notify($title_ar, $title_en,$message_ar, $message_en, $icon, $userId, $orderId=null) {
        $userType = "DOCTOR";
        try{
           // DB::statement("delete from notifications where user_type='DOCTOR' and user_id=$userId  ");
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
                Doctor::find($userId)->firebase_token
            ];

            return Helper::firebaseNotification($token, $data);


        }catch(\Exception $e){}
    }

}

