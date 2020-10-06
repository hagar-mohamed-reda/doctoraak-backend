<?php

namespace App\Http\Controllers\Api;

use App\Specialization;
use App\Degree;
use App\Settings;
use App\City;
use App\Medicine;
use App\MedicineType;
use App\Notification;
use App\Area;
use App\Analysis;
use App\Patient;
use App\Doctor;
use App\Pharmacy;
use App\Lab;
use App\Radiology;
use App\Ray;
use App\Insurance;
use App\Message;
use App\LabOrder;
use App\RadiologyOrder;
use App\PharmacyOrder;
use App\ClinicOrder; 
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;


class MainController extends Controller
{


    public function showPhone()
    {
        $data = Settings::find(2)->value;
        return Message::success(Message::$DONE,  $data ,Message::$DONE_EN);
    }
    
     public function showCarienseDetails()
     { 
      // Cariense Data in Arabic //..........
        $data_1[] = Settings::find(12)->value;
        $data_2[] = Settings::find(13)->value;
        $data_3[] = Settings::find(14)->value;
        $data_4[] = Settings::find(15)->value;
       // Cariense Data in English //.........
        $data_5[] = Settings::find(16)->value;
        $data_6[] = Settings::find(17)->value;
        $data_7[] = Settings::find(18)->value;
        $data_8[] = Settings::find(19)->value;
       // return  format [ [ ] , [ ] ] ........
        $result = [ $data_1 ,$data_2 ,  $data_3 , $data_4  ,$data_5 ,$data_6 ,  $data_7 , $data_8 ];
        return Message::success(Message::$DONE,   $result ,Message::$DONE_EN);
    }
  
   public function facebook()
    {
        $data1 = Settings::find(8)->value;
        $data2 = Settings::find(9)->value;
        $data3 = Settings::find(10)->value;
        $data4 = Settings::find(11)->value;
        $data =[  $data1 , $data2 , $data3 , $data4];
        return Message::success(Message::$DONE, $data ,Message::$DONE_EN);
    }


    public function showSpecialization()
    {
        $data = Helper::jsonFilter(Specialization::all());
        return Message::success(Message::$DONE,  $data ,Message::$DONE_EN);
    }

    public function showMedicines()
    {
        $data = Medicine::all();
        return Message::success(Message::$DONE,  $data ,Message::$DONE_EN);
    }
    
    
     public function medicineType()
    {
        $data = MedicineType::all();
        return Message::success(Message::$DONE,  $data ,Message::$DONE_EN);
    }
      public function showlab()
    {
        $data = Lab::select('*')->where('active','1')->get();
        return Message::success(Message::$DONE,  $data ,Message::$DONE_EN);
    }
    
     public function showRadiology()
    {
        $data = Radiology::select('*')->where('active','1')->get();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }


    public function showAnalysis()
    {
        $data = Analysis::all();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }

    public function showRays()
    {
        $data = Ray::all();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }


    public function showDegree()
    {
        $data = Degree::all();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }

    public function showCity()
    {
        $data = City::all();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }

    public function showArea(Request $request)
    {   
        $query = Area::query();
        
        if ($request->city_id) {
            $query->where("city_id", $request->city_id);
        }
        
        $data = $query->get();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }

    public function showInsurance()
    {
        $data = Insurance::all();
        return Message::success(Message::$DONE,  $data  ,Message::$DONE_EN);
    }
    
    
    
    
    //function for getting all notifications
   
      public function getNotification(Request $request)
    {
       
        $validator = validator()->make($request->all(),
            [
                'user_id'=>'required',
                'user_type'=>'required',
                'api_token'=>'required'
            ]);
            
            
        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        
        try {
        
          // chekc if Patient 
          if( $request->user_type =='PATIENT')
          {
            if (Patient::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
            else{
                $notification =Notification::where('user_id',$request->user_id)->where("user_type", "PATIENT")->orderBy('created_at', 'DESC')
               ->take(20)->get();
            } }
          
           // chekc if  DOCTOR 
          elseif( $request->user_type =='DOCTOR')
          {
            if (Doctor::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
             else{
                  $notification =Notification::where('user_id',$request->user_id)->where("user_type", "DOCTOR")->orderBy('created_at', 'DESC')
                ->take(20)->get();
                } }
          
           // chekc if LAB 
          elseif( $request->user_type =='LAB')
          {
            if (LAB::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
           return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
             else{  
                $notification =Notification::where('user_id',$request->user_id)->where("user_type", "LAB")->orderBy('created_at', 'DESC')->take(20)->get();
               
            }}
          
           // chekc if RADIOLOGY 
          elseif( $request->user_type =='RADIOLOGY')
          {
            if (Radiology::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
           return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
             else{
                $notification =Notification::where('user_id',$request->user_id)->where("user_type", "RADIOLOGY")->orderBy('created_at', 'DESC')
               ->take(20)->get();
            }}
          
            // chekc if PHARMACY 
          elseif( $request->user_type =='PHARMACY')
          {
            if (Pharmacy::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
          return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
              else{
                $notification =Notification::where('user_id',$request->user_id)->where("user_type", "PHARMACY")->orderBy('created_at', 'DESC')
                ->take(20)->get();
                
            }}
            
            
             return Message::success(Message::$DONE,$notification ,Message::$DONE_EN);
             
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null ,Message::$ERROR_EN);
        }
    }
   
    
    
    
     public function updateToken(Request $request)
    {
       
        $validator = validator()->make($request->all(),
            [
                'firebase_token' => 'required',
                'user_id'=>'required',
                'user_type'=>'required',
                'api_token'=>'required'
            ]);
            
            
        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        
        try {
        
          // chekc if Patient 
          if( $request->user_type =='PATIENT')
          {
            if (Patient::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
           return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
           else{
                $user =Patient::find($request->user_id); 
                $user->update(['firebase_token'=>$request->firebase_token]); 
                
            } }
          
           // chekc if  DOCTOR 
          elseif( $request->user_type =='DOCTOR')
          {
            if (Doctor::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
           return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
           else{
                $user =Doctor::find($request->user_id); 
                 $user->update(['firebase_token'=>$request->firebase_token]);
                
            } }
          
           // chekc if LAB 
          elseif( $request->user_type =='LAB')
          {
            if (LAB::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
           else{
                $user =LAB::find($request->user_id); 
                 $user->update(['firebase_token'=>$request->firebase_token]);
                
            }}
          
           // chekc if RADIOLOGY 
          elseif( $request->user_type =='RADIOLOGY')
          {
            if (Radiology::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
           return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
           else{
                $user =Radiology::find($request->user_id); 
                 $user->update(['firebase_token'=>$request->firebase_token]);
                
            }}
          
            // chekc if PHARMACY 
          elseif( $request->user_type =='PHARMACY')
          {
            if (Pharmacy::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN ,null , Message::$API_LOGIN_EN);
          else{
                $user =Pharmacy::find($request->user_id); 
                 $user->update(['firebase_token'=>$request->firebase_token]); 
                
            }}
            
            
             return Message::success(Message::$DONE,null ,Message::$DONE_EN);
             
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null ,Message::$ERROR_EN);
        }
    }
    
    public function updateDoctor(Request $request) {
        
        try {
            if ( $request->user_type =='PHARMACY') {
             $pharmacy = Pharmacy::find($request->user_id);
             $pharmacy->pharmacy_doctor_id = $request->doctor;
             $pharmacy->update();
            } else if ( $request->user_type =='LAB') {
                 $lab = Lab::find($request->user_id);
                 $lab->lab_doctor_id = $request->doctor;
                 $lab->update();
            } else if ( $request->user_type =='RADIOLOGY') {
                 $radiology = Radiology::find($request->user_id);
                 $radiology->radiology_doctor_id = $request->doctor;
                 $radiology->update();
            }
            return Message::success(Message::$DONE,null ,Message::$DONE_EN);
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR, null ,Message::$ERROR_EN);
        }
    }
    
    
    /**
     * cancel order
     * for patient
     */
    public function cancelOrderPatient(Request $request) { 
        $validator = validator()->make($request->all(), [
            'api_token' => 'required',
            'patient_id' => 'required',
            'order_id' => 'required', 
            'order_type' => 'required', 
            'message' => 'required', 
        ]);
         
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
        
        $patient = Patient::find($request->patient_id);
        
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);

        if ($request->order_type == "LAB") {
            $order = LabOrder::find($request->order_id); 
            
            if ($order) {
                $isOldDate = strtotime(date("Y-m-d")) > strtotime($order->created_at)? true : false;
                
                if (!$isOldDate)
                Lab::notify(
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "ar"), 
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "en"), 
                        $request->message, 
                        $request->message, 
                        "", 
                        $order->lab_id
                );
                
                $order->delete();
            }
        } else if ($request->order_type == "RADIOLOGY") {
            $order = RadiologyOrder::find($request->order_id); 
            
            if ($order) {
                $isOldDate = strtotime(date("Y-m-d")) > strtotime($order->created_at)? true : false;
                
                if (!$isOldDate)
                Radiology::notify(
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "ar"), 
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "en"), 
                        $request->message, 
                        $request->message, 
                        "", 
                        $order->radiology_id
                );
                
                $order->delete();
            }
        } else if ($request->order_type == "PHARMACY") {
            $order = PharmacyOrder::find($request->order_id); 
            
            if ($order) {
                $isOldDate = strtotime(date("Y-m-d")) > strtotime($order->created_at)? true : false;
                
                if (!$isOldDate)
                Pharmacy::notify(
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "ar"), 
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "en"), 
                        $request->message, 
                        $request->message, 
                        "", 
                        $order->pharmacy_id
                );
                
                $order->pharmacy_requests()->delete();
                //DB::statement("delete from pharmacy_requests where pharmacy_id=".$order->pharmacy_id." ");
                $order->delete();
            }
        } else if ($request->order_type == "DOCTOR") {
            $order = ClinicOrder::find($request->order_id); 
            
            if ($order) {
                $isOldDate = strtotime(date("Y-m-d")) > strtotime($order->created_at)? true : false;
                
                if (!$isOldDate)
                Doctor::notify(
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "ar"), 
                        trans("messages.order_cancel_from_patient", ["number" => $order->id], "en"), 
                        $request->message, 
                        $request->message, 
                        "", 
                        $order->clinic->doctor->id
                );
                
                $order->update([
                    "active" => 0
                ]);
            }
        }
        
        // add spam for patient
        if ($patient)
            $patient->spam("CANCEL_ORDER");
        
        return Message::success(Message::$DONE, null, Message::$DONE_EN);
    }
    
    
    /**
     * cancel order
     * for lab or radiology
     */
    public function cancelOrder(Request $request) { 
        $validator = validator()->make($request->all(), [
            'api_token' => 'required', 
            'order_id' => 'required', 
            'user_id' => 'required', 
            'user_type' => 'required', 
            'message' => 'required', 
        ]);
         
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
        

        if ($request->user_type == "LAB") {
            // chekc if patient login
            if (Lab::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
        
        
            $order = LabOrder::find($request->order_id); 
            
            if ($order) {
                Patient::notify(
                        trans("messages.model_cancel_order", ["number" => $order->id], "ar"), 
                        trans("messages.model_cancel_order", ["number" => $order->id], "en"), 
                        $request->message, 
                        $request->message, 
                        "", 
                        $order->patient_id
                );
                
                $order->delete();
            }
        } else if ($request->user_type == "RADIOLOGY") {
            // chekc if patient login
            if (Radiology::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
        
            
            $order = RadiologyOrder::find($request->order_id); 
            
            if ($order) {
                Patient::notify(
                        trans("messages.model_cancel_order", ["number" => $order->id], "ar"), 
                        trans("messages.model_cancel_order", ["number" => $order->id], "en"), 
                        $request->message, 
                        $request->message, 
                        "", 
                        $order->patient_id
                );
                
                $order->delete();
            }
        } 
        return Message::success(Message::$DONE, null, Message::$DONE_EN);
    }
    
    
    /**
     * accept order
     * for lab or radiology
     */
    public function acceptOrder(Request $request) { 
        $validator = validator()->make($request->all(), [
            'api_token' => 'required', 
            'order_id' => 'required', 
            'user_id' => 'required', 
            'user_type' => 'required',  
        ]);
         
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
        

        if ($request->user_type == "LAB") {
            // chekc if patient login
            if (Lab::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
        
        
            $order = LabOrder::find($request->order_id); 
            
            if ($order) {
                Patient::notify(
                        trans("messages.your_order", ["number" => $order->id], "ar"), 
                        trans("messages.your_order", ["number" => $order->id], "en"), 
                        trans("messages.order_accept", [], "ar"), 
                        trans("messages.order_accept", [], "en"),  
                        "", 
                        $order->patient_id,
                        $order->id,
                        "LAB"
                );
                
                $order->accept = 1;
                $order->update();
            }
        } else if ($request->user_type == "RADIOLOGY") {
            // chekc if patient login
            if (Radiology::where("api_token", $request->api_token)->where("id", $request->user_id)->count() <= 0)
                return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
        
            
            $order = RadiologyOrder::find($request->order_id); 
            
            if ($order) {
                Patient::notify(
                        trans("messages.your_order", ["number" => $order->id], "ar"), 
                        trans("messages.your_order", ["number" => $order->id], "en"), 
                        trans("messages.order_accept", [], "ar"), 
                        trans("messages.order_accept", [], "en"),  
                        "", 
                        $order->patient_id,
                        $order->id,
                        "RADIOLOGY"
                );
                
                $order->accept = 1;
                $order->update();
            }
        } 
        return Message::success(Message::$DONE, null, Message::$DONE_EN);
    }
    
    /**
     * 
     * 
     */
    public function getHelpVideos() {
        
        $videos = [
            [
                "url" => "https://www.youtube.com/watch?v=LxWC-PFKluo",
                "title_ar" => "تطبيق دكتورك",
                "title_en" => "doctoraak app",
            ]
                
        ];
        
        return Message::success(Message::$DONE, $videos, Message::$DONE_EN);
    }
    
    
    /**
     * remove notification
     * 
     */
    public function removeNotification(Request $request) { 
        $notification = Notification::find($request->notification_id);
        
        if ($notification) {
            $notification->delete();
        }
        
        return Message::success(Message::$DONE, null, Message::$DONE_EN);
    }
    
    
}
