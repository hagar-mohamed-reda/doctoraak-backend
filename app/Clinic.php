<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Clinic;
use DB;

use App\helper\ViewBuilder;

class Clinic extends Model
{

    protected $table = 'clinics';
    protected $fillable = [ 
        'fees',
        'fees2',
        'lng',
        'lat',
        'address',
        'city_id',
        'area_id',
        'phone',
        'waiting_time',
        'active',
        'photo',
        'doctor_id',
        'availability',
        'available_days',
    ];
    protected $appends = [
        "free_days", 'url', 'city', 'area'
    ];
    
    public function getCityAttribute() {
        return $this->city()->first();
    }
    
    public function getAreaAttribute() {
        return $this->area()->first();
    }

    public function getUrlAttribute() {
        if (!$this->photo || $this->photo == 'doctor.png') {
            return url('/image/icon/doctor.png');
        }
        
        return url("image/clinic/" . $this->photo);
    }
    
    public function getFreeDaysAttribute() {
        $freeDays = [];
        $date = date("Y-m-d");
        $counter = 0;
        
        while($counter <= 90) {
            $day = [
                "date" => $date
            ];
            
            // part 1
            if ($this->canReserve($date, 1)) {
                $day["part_id"] = 1; 
            }
            
            // part 2
            if ($this->canReserve($date, 2)) {
                $day["part_id"] = 2; 
            }
            
            // part 3 => part 1 and 2 available
            if ($this->canReserve($date, 1) && $this->canReserve($date, 2)) {
                $day["part_id"] = 3; 
            }
            
            if (isset($day["part_id"]))
                $freeDays[] = $day;
             
            if (count($freeDays) >= 20)
                break;
            
            $date = date('Y-m-d', strtotime($date. ' + 1 day'));
            $counter ++;
        }
        
        return $freeDays;
    }

    public function canReserve($date, $part) { 
        try{
            $workingHours = ClinicWorkingHours::where("day", WorkingHours::getDay($date))->where("clinic_id", $this->id)->first();

            $partQueueMaxNumber = 0;
            
            if ($part == 1)
            $partQueueMaxNumber = $workingHours->reservation_number_1;
            else if ($part == 2)
            $partQueueMaxNumber = $workingHours->reservation_number_2;
    
            if ($workingHours->active == '0')
                return false;
    
            $clinicOrdersPartQueueNumber = ClinicOrder::where("date", $date)->where("clinic_id", $this->id)->where("part_id", $part)->where("active", '1')->count() + 1; 
        
            if ($clinicOrdersPartQueueNumber < $partQueueMaxNumber)
                return true; 
        }catch(\Exception $e){ 
        }
        
        return false;
    }
    
    public function getChartData() {
        $data = [];
        $dates = DB::select("select DISTINCT date from clinic_orders where clinic_id=" . $this->id);
        
        foreach($dates as $date) {
            $data[$date->date] = ClinicOrder::where("date", $date->date)->where("clinic_id", $this->id)->count();
        }
        
        return $data;
    }

    public static $RESERVATION = 1;
    public static $CONSULTATION = 2;
    public static $CONTINUE = 3; 

    public function doctor() {
        return $this->belongsTo('App\Doctor', 'doctor_id');
    }

    public function orders() {
        return $this->hasMany('App\ClinicOrder');
    }

    public function working_hours() {
        return $this->hasMany('App\ClinicWorkingHours', 'clinic_id');
    }

    public function getJson($patient = null) {
        $this->working_hours = $this->working_hours()->get();
        $doctor = $this->doctor()->first();
        $this->doctor->rate = $doctor->getRate();
        $this->doctor->photo = url('/image/doctor/') . "/" . $doctor->photo;
        $this->doctor->cv2 = url('/file/doctor/') . "/" . $doctor->cv2;
        $this->photo .=(Clinic::find($this->id)) ? url('/image/clinic/') . "/" . Clinic::find($this->id)->photo : '';
        $this->degree = $this->doctor->degree;
        $this->specialization = $this->doctor->specialization;
        $this->specialization = $this->doctor->specialization;

        //$this->favourite = $patient? $this->doctor->favourites->where("patient_id", $patient)->count() : 0;

        return $this;
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
        foreach (Doctor::get(['id', 'name']) as $item)
            $doctors[] = [$item->id, $item->name];


        $builder->setAddRoute(url('/clinic/store'))
                ->setEditRoute(url('/clinic/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "fees", "label" => __('fees'), 'type' => "number"])
                ->setCol(["name" => "fees2", "label" => __('fees2'), 'type' => "number"])
                ->setCol(["name" => "waiting_time", "label" => __('waiting_time'), 'type' => "number"])
                ->setCol(["name" => "map", "label" => __('location'), "type" => "map"])
                ->setCol(["name" => "doctor_id", "label" => __('doctor'), "type" => "select", "data" => $doctors])
                ->setCol(["name" => "city_id", "label" => __('city'), "type" => "select", "data" => $cities])
                ->setCol(["name" => "area_id", "label" => __('area'), "type" => "select", "data" => $areas])
                ->setCol(["name" => "phone", "label" => __('phone')])
                //->setCol(["name" => "address", "label" => __('address'), "required" => false])
                ->setCol(["name" => "photo", "label" => __('photo'), "type" => "image", "required" => false]) 
                ->setCol(["name" => "available_days", "label" => __('available_days'), 'type' => "number"])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                ->setCol(["name" => "availability", "label" => __('availability'), "type" => "checkbox"])
                ->setUrl(url('/image/clinic'))
                ->build();

        return $builder;
    }

    
}
