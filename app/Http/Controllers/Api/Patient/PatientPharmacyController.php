<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pharmacy;
use App\PharmacyRequest;
use App\PharmacyOrder;
use App\PharmacyOrderDetails;
use App\Message;
use App\helper\Helper;
use App\Settings;
use App\Patient;
use App\PharmacyWorkingHours;
use App\WorkingHours;
use DB;

class PatientPharmacyController extends Controller
{

    // return all pharmacy 
    public function showPharmacy()
    {
        $data = Pharmacy::select('*')->where('active','1')->get();
        return Message::success(Message::$DONE,  Helper::jsonFilter( $data)   ,Message::$DONE_EN);
    }



   // filter pharmacy to make order 
    public function pharmacyFilter(Request $request)
    {

        $validator = validator()->make(
            $request->all(),
            [
             //   'patient_id' => 'required',
         //       'api_token' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }

/*
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
             return Message::error(Message::$API_LOGIN ,null ,Message::$API_LOGIN_EN);
*/

        try {
            $patient = Patient::find($request->patient_id);
            $resault = [];
            ////search by using lab id
            if ($request->has("pharmacy_id") && $request->pharmacy_id != null) {
                $resault[] = Pharmacy::find($request->pharmacy_id);
            } else if ($request->has("city") && $request->has("area")) {
                $resault = Pharmacy::where("city", $request->city)
                    ->where("area", $request->area)->get();
            } else if ($request->has("latt") && $request->has("lang")) {
                $resault = $this->searchNearstPharmacys($request->lang, $request->latt);
            } 
         // fitler the resault with insurance id
         if ($request->delivery == 1 ) {
            $resault = $this->deliveryPharmacyFilter($resault, $request->delivery);
        }



            // fitler the resault with insurance id
            if ($request->insurance == 1 && $patient->insurance_id) {
                $resault = $this->insurancePharmacyFilter($resault, $patient->insurance_id);
            }
            // build return message
            $messsage = str_replace("n", count($resault), Message::$PHARMACY_SEARCH);
            $messsage_en = str_replace("x", count($resault), Message::$PHARMACY_SEARCH_EN);

              return Message::success($messsage, Helper::jsonFilter($resault),$messsage_en);
         } catch (Exception $ex) {
            return Message::error(Message::$ERROR .$ex , null,Message::$ERROR_EN);
        }
    }



   public function insurancePharmacyFilter($pharmacys, $insurance)
    {
        $filteredPharmacys = [];
        foreach ($pharmacys as $pharmacy) {
            if ($pharmacy->pharmacy_insurances->where("insurance_id", $insurance)->count() > 0) {
                 $filteredPharmacys[] = $pharmacy;
            }
        }

        return  $filteredPharmacys;
    }


    
   public function deliveryPharmacyFilter($pharmacys, $delivery)
   {
       $filteredPharmacys = [];
       foreach ($pharmacys as $pharmacy) {
           if ($pharmacy->delivery== 1 ) {
                $filteredPharmacys[] = $pharmacy;
           }
       }

       return  $filteredPharmacys;
   }

    public function searchNearstPharmacys($lng, $lat, $newkm = null, $searchNumber = 0)
    {
        $km = Settings::find(1)->value;
        if ($newkm)
            $km = $newkm;

        $nearestPharmacys = [];
        $pharmacys = Pharmacy::all();

        foreach ($pharmacys as $pharmacy) {
            // calculate distance between current lng lat and lab lng lat
            $distance = Helper::latLangDistance($lat, $lng, $pharmacy->latt, $pharmacy->lang);
            $pharmacy->distance = $distance;

            if ($distance <= $km)
                $nearestPharmacys[] = $pharmacy;
        }

        if ($searchNumber > 2)
            return $nearestPharmacys;

        if (count($nearestPharmacys) <= 0)
            $this->searchNearstPharmacys($lng, $lat, 2 * $km, $searchNumber++);

        return $nearestPharmacys;
    }




    /**
     *  create pharmacy order by using latt and lang  in circle .
     *
                User::query()
               ->where('name', 'LIKE', "%{$searchTerm}%") 
               ->orWhere('email', 'LIKE', "%{$searchTerm}%") 
               ->get();
     */



    public function createPharmacyOrder(Request $request)
    {   
        
          $orderdetails = json_decode($request->orderDetails);
       
        $validator = validator()->make(
            $request->all(),
            [
               ///   'date' => 'required|date',
                'patient_id' => 'required|numeric',
                'pharmacy_id' => 'required|numeric',
                'api_token' => 'required'
            ]
        );


        if ($validator->fails()) {
            return Message::error($validator->errors()->first(), null);
        }
        // chekc if patient login
        if (Patient::where("api_token", $request->api_token)->where("id", $request->patient_id)->count() <= 0)
            return Message::error(Message::$API_LOGIN, null ,Message::$API_LOGIN_EN);
               
         
        // check on patient pharmacy order number
        $messsage = str_replace("n", Settings::find(4)->value, Message::$MAX_ORDER_NUMBER);
        $messsage_en = str_replace("x", Settings::find(4)->value, Message::$MAX_ORDER_NUMBER_EN);
     
         $date = date("Y-m-d");
        $query = DB::select("SELECT count(id) as count FROM `pharmacy_orders` WHERE patient_id =" . $request->patient_id . " and DATE_FORMAT(created_at, '%Y-%m-%d') = '$date'");
        $patientOrderNumber = $query[0]->count;

        if ($patientOrderNumber >= Settings::find(4)->value)
            return Message::error($messsage, null ,$messsage_en);





        if ($request->has("orderDetails")) {
            $messsage = str_replace("n", Settings::find(5)->value, Message::$MAX_ORDER__DETAILS_NUMBER);
            $messsage_en = str_replace("x", Settings::find(5)->value, Message::$MAX_ORDER__DETAILS_NUMBER_EN);
         
            if (count($orderdetails) > Settings::find(5)->value)
                return Message::success($messsage, null ,$messsage_en);
        }
       


        try {

            $pharmacy = Pharmacy::find($request->pharmacy_id);
            $workingHours = PharmacyWorkingHours::where("day", workingHours::getDay($request->date))
                ->where("pharmacy_id", $pharmacy->id)->where('active', '1')->first();

            if (!$workingHours)
                return Message::error(Message::$DAY_OFF, null,Message::$DAY_OFF_EN);
            
            if ($workingHours->active != 1) {
                return Message::error(Message::$DAY_OFF, null,Message::$DAY_OFF_EN);
            }


            $pharmacyOrder = new PharmacyOrder;
            $pharmacyOrder->pharmacy_id = $request->pharmacy_id;
            $pharmacyOrder->patient_id = $request->patient_id;
            $pharmacyOrder->notes = $request->notes;
            $pharmacyOrder->created_at = date("Y-m-d H:i:s", strtotime($request->date));
           if ($request->hasFile('photo')) {
                $pharmacyOrder->photo = Helper::uploadImg($request->file("photo"), "/pharmacyorder/");
            }

            $pharmacyOrder->save();


            

              if ($request->has("orderDetails")) {
                foreach ($orderdetails as $detail) {
                    $d = new PharmacyOrderDetails;
                    $d->medicine_id = $detail->medicine_id;
                    $d->medicine_type_id = $detail->medicine_type_id;
                    $d->amount = $detail->amount;
                    $d->pharmacy_order =  $pharmacyOrder->id;

                    $d->save();
                }
            }

         $orderNumber = PharmacyOrder::whereBetween("created_at", [$request->date, $request->date])->where('pharmacy_id', $request->pharmacy_id)->count();
                     // check on patient reservation number





  
            $message = str_replace("patient", $pharmacyOrder->patient->name, Message::$PHARMACY_NEW__ORDER);
            $message = str_replace("number", $orderNumber, $message);
            $message = str_replace("name", $pharmacyOrder->pharmacy->name, $message);
            $message = str_replace("phone", $pharmacyOrder->pharmacy->phone, $message);
            
            $message_en = str_replace("patient", $pharmacyOrder->patient->name, Message::$PHARMACY_NEW__ORDER_EN);
            $message_en = str_replace("numbers", $orderNumber, $message_en);
            $message_en = str_replace("name", $pharmacyOrder->pharmacy->name, $message_en);
            $message_en = str_replace("phones", $pharmacyOrder->pharmacy->phone, $message_en);
            
            /////notification to patients /////////////////////////
            $title_ar = "   بيانات طلب الصيدليه الخاص بك  ".Patient::find($request->patient_id)->name;
            $title_en = "your order details ".Patient::find($request->patient_id)->name;
            Patient::notify($title_ar,  $title_en,$message,  $message_en, $icon='icon.png',$request->patient_id, $pharmacyOrder->id, "PHARMACY");

            ///////////////notification for pharmacy //////
            
            $message_ar_l ="لقد قام   ".Patient::find($request->patient_id)->name." بالحجز لديك و رقم هانفه " .Patient::find($request->patient_id)->phone;
            $message_en_l = "you have new book from ". Patient::find($request->patient_id)->name . "has phone number is" . Patient::find($request->patient_id)->phone ;
            $title_ar_l = "بيانات الطلب ";
            $title_en_l = "Order details";
           Pharmacy::notify($title_ar_l,  $title_en_l,$message_ar_l,  $message_en_l, $icon='icon.png',$request->pharmacy_id, $pharmacyOrder->id);
         
          
            return Message::success($message, null,  $message_en);
        } catch (Exception $ex) {
            return Message::error(Message::$ERROR  . $ex , null ,Message::$ERROR_EN);
        }
    }

}