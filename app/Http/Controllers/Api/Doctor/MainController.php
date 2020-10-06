<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Clinic;
use App\ClinicWorkingHours;
use App\WorkingHours;
use App\ClinicOrder;
use App\Doctor;
use App\Patient;
use App\DoctorInsurance;
use App\Message;
use App\PatientRate;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class MainController extends Controller
{
    /**
     * create clinic and it's working hours
     *
     * @param Request $request
     * @return void
     */
    public function createClinic(Request $request)
    {
        // conert json to php stdclass
        $data = json_decode($request->working_hours);
        ///////////
        
        $validator = validator()->make($request->all(), [
            'fees' => 'required',
            'fees2' => 'required',
            'lang' => 'required',
            'latt' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'area' => 'required',
            'waiting_time' => 'required',
            'doctor_id' => 'required',
            'api_token' => 'required',
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
     
        try {
            $user = Clinic::create([
                'fees' => $request->fees,
                'fees2' => $request->fees2,
                'lang' => $request->lang,
                'latt' => $request->latt,
                'phone' => $request->phone,
                'city' => $request->city,
                'area' => $request->area,
                'waiting_time' => $request->waiting_time,
                'doctor_id' => $request->doctor_id,
            ]);

            if ($request->hasFile('photo')) {
                $user->photo = Helper::uploadImg($request->file("photo"), "/clinic/");
            }

            // 7 weed days number
            // check on week day number
            if (count($data) < 7) {
                return Message::error(Message::$CLINIC_WORKIING_HOURS_LIMIT,null,Message::$CLINIC_WORKIING_HOURS_LIMIT_EN);
            }

            foreach ($data as $working_hour) {
                $d = new ClinicWorkingHours;
                $d->clinic_id = $user->id;
                $d->part1_from =  $working_hour->part1_from;
                $d->part2_from =  $working_hour->part2_from;
                $d->part1_to =  $working_hour->part1_to;
                $d->part2_to =  $working_hour->part2_to;
                $d->day = $working_hour->day;
                $d->active = $working_hour->active;
                $d->reservation_number_1 = Helper::workingHours($working_hour->part1_to, $working_hour->part1_from, $request->waiting_time);
                $d->reservation_number_2 = Helper::workingHours($working_hour->part2_to, $working_hour->part2_from, $request->waiting_time);
                $d->save();
            }

            $user->save();
            return Message::success(Message::$DONE, $user->getJson(),Message::$DONE_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,null,Message::$ERROR_EN);
        }
    }


    /**
     * update clinic 
     *
     * @param Request $request
     * @return void
     */
    public function updateClinic(Request $request)
    { 
        $validator = validator()->make($request->all(), [
            'fees' => 'required',
            'fees2' => 'required',
            'lang' => 'required',
            'latt' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'area' => 'required',
            'clinic_id' => 'required',
            'waiting_time' => 'required',
            'doctor_id' => 'required',
            'api_token' => 'required',
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
     
        try {
            $user = Clinic::find($request->clinic_id);
            
            $user->update($request->all());
            
            if ($request->hasFile('photo') && $request->photo != null) {
                // remove old image
                Helper::removeFile(public_path() . "/clinic/" . $user->photo);
                
                // upload new image
                $user->photo = Helper::uploadImg($request->file("photo"), "/clinic/");
                
                // update changes
                $user->update();
            } 
  
            return Message::success(Message::$DONE, $user->getJson(),Message::$DONE_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,null,Message::$ERROR_EN);
        }
    }

    /**
     * update clinic and it's working hours
     *
     * @param Request $request
     * @return void
     */
    public function updateClinicWorkingHours(Request $request)
    {
        // conert json to php stdclass
        $data = json_decode($request->working_hours);
        ///////////
        
        $validator = validator()->make($request->all(), [  
            'clinic_id' => 'required',
            'api_token' => 'required',
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
     
        try {
            // remove old working hours
            DB::statement("delete from clinic_working_hours where clinic_id='" . $request->clinic_id . "' ");
            
            $user = Clinic::find($request->clinic_id);
  
            // 7 weed days number
            // check on week day number
            if (count($data) < 7) {
                return Message::error(Message::$CLINIC_WORKIING_HOURS_LIMIT,null,Message::$CLINIC_WORKIING_HOURS_LIMIT_EN);
            }

            foreach ($data as $working_hour) {
                $d = new ClinicWorkingHours;
                $d->clinic_id = $user->id;
                $d->part1_from =  $working_hour->part1_from;
                $d->part2_from =  $working_hour->part2_from;
                $d->part1_to =  $working_hour->part1_to;
                $d->part2_to =  $working_hour->part2_to;
                $d->day = $working_hour->day;
                $d->active = $working_hour->active;
                $d->reservation_number_1 = Helper::workingHours($working_hour->part1_to, $working_hour->part1_from, $user->waiting_time);
                $d->reservation_number_2 = Helper::workingHours($working_hour->part2_to, $working_hour->part2_from, $user->waiting_time);
                $d->save();
            }

            $user->save();   
            return Message::success(Message::$DONE, $user->getJson(),Message::$DONE_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR . " - "  . $e->getMessage(),null,Message::$ERROR_EN);
        }
    }
    
    
    /**
     * set the clinic un available in today
     *
     * @param Request $request
     * @return void
     */
    public function updateAvailability(Request $request) { 
        $validator = validator()->make($request->all(), [  
            'clinic_id' => 'required',
            'api_token' => 'required',
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
     
        try{
            
            $clinic = Clinic::find($request->clinic_id);
             
            $date = date("Y-m-d");
            if ($clinic->availability_date == $date)
                return Message::error("انت بالفعل غير متاح", null, "you are already not available");
            
            
                $clinicOrders = ClinicOrder::where("date", $date)->get();
                foreach($clinicOrders as $order) {
                    $patient = Patient::find($order->patient_id);
                    $doctorName = $order->doctor? $order->doctor->name : '';
                    
                    //$title_ar,$title_en,$message_ar, $message_en, $icon, $userId
                    $title_ar = "حجزك رقم " . $order->id;
                    $message_ar = "تم الغاء حجز اليوم من قبل الدكتور ";
                    $message_ar .= " من فضلك قم باعادة الحجز فى يوم اخر ";
                    $doctorName;
                    
                    Patient::notify($title_ar, "your order " . $order->id, $message_ar, "your reservation has been cancel from the doctor ". $doctorName . " please reserve in another day ", null, $order->patient_id, $order->id, "DOCTOR");
                    
                    $order->active = '0';
                    $order->update();
                    
                }
                 
                $clinic->availability_date = $date; 
            
            $clinic->update();
            
            
            return Message::success(Message::$DONE,  null,Message::$DONE_EN);
        }catch(\Exception $e){
            return Message::error(Message::$ERROR,null,Message::$ERROR_EN);
        }
    }

    /**
     *  get all reservation for doctor per week
     *
     * @param Request $request
     * @return void
     */  
    public function getOrder(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'clinic_id' => 'required|numeric',
                'doctor_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
             return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      
        // check if this clinic  belonge to doctor id
        if (Clinic::find($request->clinic_id)->doctor_id != $request->doctor_id)
            return Message::error(Message::$ERROR,null,Message::$ERROR_EN);

        try {
            $date = date("Y-m-d");
            $dates = WorkingHours::getStartAndEndDateOfWeek($date);


            $orders = ClinicOrder::where("active", "1")
                ->whereBetween("date", $dates)
                ->where("clinic_id", $request->clinic_id)->get();

            $messsage = str_replace("n", count($orders), Message::$CLINIC_RESERVATION_PER_WEEK);
            $messsage_en = str_replace("x", count($orders), Message::$CLINIC_RESERVATION_PER_WEEK_EN);
          
            return Message::success($messsage, Helper::jsonFilter($orders), $messsage_en);
        } catch (Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }

   /**
     *  get all clinic  for doctor 
     *
     * @param Request $request
     * @return void
     */ 
     public function getClinic(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [ 
                'doctor_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
             return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      
        try {
            $clinics=Clinic::where('doctor_id',$request->doctor_id)->get(); 
            return Message::success('العيادات الخاصه بك', Helper::jsonFilter( $clinics),'Your Clinics');
            
        } catch (Exception $exc) {
            return Message::error(Message::$ERROR, null ,Message::$ERROR_EN);
        }
    }

    /**
     *  doctor reject order
     *
     * @param Request $request
     * @return void
     */
     
    

    public function rejectOrder(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'order_id' => 'required|numeric',
            'doctor_id' => 'required|numeric',
            'api_token' => 'required'
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first());
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      
        try {
            $resource = ClinicOrder::find($request->order_id);
            // update clinic order status
            $resource->update([
                'active' => 0
            ]);
            
             $a= ClinicOrder::find($request->order_id)->patient_id;
             $title_ar="الغاء الحجز ";
             $title_en="order cancled";
             $message="لقد تم الغاء الحجز الخاص بك عند دكتور ".Doctor::find($request->doctor_id)->name;
             $message_en="DR " .Doctor::find($request->doctor_id)->name.  " cancle your order";
             Patient::notify($title_ar,  $title_en,$message,  $message_en, $icon='icon.png',$a, $resource->id, "DOCTOR");
            return Message::success(Message::$REJECT_ORDER, null, $message_en);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR , null,Message::$ERROR_EN);
        }
    }


    public function rateDoctor(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'doctor_id' => 'required|numeric',
            'patient_id' => 'required|numeric',
            'api_token' => 'required',
            'rate' => 'required|numeric|min:1|max:5',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
               return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      
        // chekc if patient rate this doctor
        if (PatientRate::where("patient_id", $request->patient_id)->where("type", "2")->where("doctor_id", $request->doctor_id)->count() > 0)
            return Message::error(Message::$DOCTOR_RATE_ERROR,null,Message::$DOCTOR_RATE_ERROR_EN);

        try {
            PatientRate::create($request->all());
            return Message::success(Message::$DOCTOR_RATE,  null,Message::$DOCTOR_RATE_EN);
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }

    /**
     * old methods
     *
     * @param Request $request
     * @return void
     */
    public function patientBlock(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'doctor_id' => 'required|numeric',
            'patient_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first());
        }

        // chekc if patient login
        if (Doctor::where("api_token", $request->api_token)->where("id", $request->doctor_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN);

        $resource = PatientBlock::where('doctor_id', $request->doctor_id)
            ->where('patient_id', $request->patient_id)
            ->first();
        if ($resource) {
            $resource->update([
                'doctor_id' => $request->doctor_id,
                'patient_id' => $request->patient_id
            ]);
        }
        return Message::success(Message::$SUCCESS,  $resource,Message::$SUCCESS_EN);
    }
}
