<?php

namespace App\Http\Controllers\Api\Lab;

use App\Lab;
use App\LabInsurance;
use App\LabWorkingHours;

use App\Message;
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;

use App\View;



class AuthController extends Controller
{

    /**
     * resgister Lab api
     *
     * @param Request $request
     * @return void
     */

    public function register(Request $request)
    {


         $insurances = json_decode($request->insurance);
         $working_hours = json_decode($request->working_hours);

        $validator = validator()->make($request->all(), [
            'name' => 'required',
         //   'email' => 'required',
            'phone' => 'required|max:11',

            'password' => 'required|min:8',

        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }


        // check phone
        if (Lab::where("phone", $request->phone)->count() > 0)
             return Message::error(Message::$PHONE_UNIQUE,  null ,Message::$PHONE_UNIQUE_EN);

        try {


            $user= new Lab();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->phone2 = $request->phone2;
            $user->delivery = $request->delivery;
            $user->password =  bcrypt($request->password);
            $user->sms_code = rand(11111,99999);
            $user->active = 0;
            $user->lng = $request->lng;
            $user->lat = $request->lat;
            $user->city_id = $request->city_id;
            $user->area_id = $request->area_id;
            $user->save();



            if ($request->hasFile('photo')) {
                $user->photo = Helper::uploadImg($request->file("photo"), "/lab/");
            }



            if ($insurances != null) {
                foreach ($insurances as $insurance) {
                    $d = new LabInsurance;
                    $d->lab_id = $user->id;
                    $d->insurance_id = $insurance;
                    $d->save();
                }
            }


           foreach ($working_hours as $working_hour) {
                $d = new LabWorkingHours;
                $d->lab_id = $user->id;
                $d->part_from =  $working_hour->part_from;
                $d->part_to =  $working_hour->part_to;
                $d->day = $working_hour->day;
                $d->active = $working_hour->active;
                $d->save();
            }



            $user->update(); // send sms message
            Helper::sendSms("Your Code is ".$user->sms_code, $user->phone);
            return Message::success(Message::$SUCCESS_REGISTER,$user->getJson(),Message::$SUCCESS_REGISTER_EN);
        } catch (\Exception $e) {
            return Message::error($e->getMessage(), null,Message::$ERROR_EN);
        }
    }
    /**
     * verfiy lab account with sms code
     *
     * @param Request $request
     * @return void
     */

    public function verify_account(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'user_id' => 'required|numeric',
            'sms_code' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
        try {
            $user = Lab::find($request->user_id);

            if ($user->sms_code != $request->sms_code) {
                 return Message::error(Message::$SMS_CODE_ERROR,  null,Message::$SMS_CODE_ERROR_EN);
            } else {
                $user->api_token = str_random(rand(20, 100));
                $user->active = 1;
                $user->update();
                 return Message::success(Message::$VERIFIED, $user->getJson(),Message::$VERIFIED_EN);
            }
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
    }

       public function resend(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'user_id' => 'required|numeric',

        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        try {
            $user = Lab::find($request->user_id);
             Helper::sendSms("Your Code is ".$user->sms_code, $user->phone);
              return Message::success(Message::$VERIFIED, $user->getJson(),Message::$VERIFIED_EN);


        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
    }


     public function resend_two(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|numeric',


        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        try {
             $user = Lab::where('phone', $request->phone)->first();

             Helper::sendSms("Your Code is ".$user->sms_code, $user->phone);
              return Message::success(Message::$VERIFIED, $user->getJson(),Message::$VERIFIED_EN);


        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
    }


    /**
     * login Lab api
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        try {
            $user = Lab::where('phone', $request->phone)->first();

            if (!$user)
                return Message::error(Message::$PHONE_ERROR_LOGIN,null,Message::$PHONE_ERROR_LOGIN_EN);

            if (Hash::check($request->password, $user->password)) {

                if ($user->active != 1) {
                return Message::error(Message::$ACTIVE_NULL,null,Message::$ACTIVE_NULL_EN);
               }

                $user->api_token = str_random(rand(20, 100));
                $user->update();

                return Message::success(Message::$SUCCESS_LOGIN, $user->getJson(),Message::$SUCCESS_LOGIN_EN);
            } else {
                return Message::error(Message::$PHONE_ERROR_LOGIN,null,Message::$PHONE_ERROR_LOGIN_EN);
            }
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
    }
    /**
     *  send new password in sms to lab phone
     *
     * @param Request $request
     * @return void
     */
    public function forget_password(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|max:11',
        ], []);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        try {
            $user = Lab::where('phone', $request->phone)->first();
            if (!$user) {
            return Message::error(Message::$PHONE_ERROR,null,Message::$PHONE_ERROR_EN);
             }

            $newPassword =  rand(11111, 99999);
            $user->update(['password' => bcrypt($newPassword)]);


            // send newPassword
            $smsMessage = str_replace("password", $newPassword, Message::$NEW_PASSWORD);
            Helper::sendSms($smsMessage, $user->phone);

           return Message::success(Message::$PASSWORD_SENT,null,Message::$PASSWORD_SENT_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
    }



    /**
     * udpate Lab password based on sms password
     *
     * @param Request $request
     * @return void
     */
    public function update_password(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|max:11',
            'old_password' => 'required',
            'new_password' => 'required|min:8',
        ], []);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        try {
            $user = Lab::where('phone', $request->phone)->first();
            if (!$user) {
               return Message::error(Message::$PHONE_ERROR,null,Message::$PHONE_ERROR_EN);
            }

            if (Hash::check($request->old_password, $user->password)) {
                // set new password
                $user->password = bcrypt($request->new_password);
                $user->update();
               return Message::success(Message::$EDIT,null,Message::$EDIT_EN);
            } else
                    return Message::error(Message::$PASSWORD_NOT_CORRECT,null,Message::$PASSWORD_NOT_CORRECT_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,null,Message::$ERROR_EN);
        }
    }




    /**
     * update Lab profile info
     *
     * @param Request $request
     * @return void
     */
    public function update_profile(Request $request)
    {

         $insurances = json_decode($request->insurance);
        $working_hours = json_decode($request->working_hours);

        $validator = validator()->make($request->all(), [
            'user_id' => 'required',
            'api_token' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        if (Lab::where("api_token", $request->api_token)->count() <= 0)
             return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);

        try {

            $user = Lab::find($request->user_id);
            $user->name = $request->name;
            $user->phone2 = $request->phone2;
            $user->lng = $request->lng;
            $user->lat = $request->lat;
            $user->city_id =$request->city_id;
            $user->area_id =$request->area_id;
            $user->delivery = $request->delivery;

            if ($request->hasFile('photo')) {
                // delete old image
                try{
                    unlink(public_path("image/lab") . "/" .  $user->photo);
                   } catch (\Exception $e) {
              }
                  $user->photo = Helper::uploadImg($request->file("photo"), "/lab/");
            }
            DB::statement("delete from lab_insurances where lab_id='$user->id' ");

            if ($insurances != null) {
                foreach ($insurances as $insurance) {
                    $d = new LabInsurance;
                    $d->lab_id = $user->id;
                    $d->insurance_id = $insurance;
                    $d->save();
                }
            }


              /////

            DB::statement("delete from lab_working_hours where lab_id='$user->id' ");
            if ($working_hours != null) {
                 foreach ($working_hours as $working_hour) {
                $d = new LabWorkingHours;
                $d->lab_id = $user->id;
                $d->part_from =  $working_hour->part_from;
                $d->part_to =  $working_hour->part_to;
                $d->day = $working_hour->day;
                $d->active = $working_hour->active;
                $d->save();
            }
            }
            $user->update();
            return Message::success(Message::$EDIT,$user->getJson(),Message::$EDIT_EN);
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,null,Message::$ERROR_EN);
        }
    }
}
