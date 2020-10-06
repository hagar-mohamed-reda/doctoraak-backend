<?php

namespace App\Http\Controllers\Api\Patient;

use App\Patient;
use App\Message; 
use App\helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\View;



class AuthController extends Controller
{
     /**
     * buy careiense card patient api
     *
     * @param Request $request
     * @return void
     */
     public function buyCarienseCard(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'user_id' => 'required',
           
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }
        
         

       try {
            $user = Patient::find($request->user_id);
            $user->insurance_id = 1;
            $user->update();
           return Message::success(Message::$SUCCESS_REGISTER,$user->getJson(),Message::$SUCCESS_REGISTER_EN);
          
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
      
    }
    /**
     * resgister patient api
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required',
         //   'email' => 'required',
            'phone' => 'required|max:11',
            'password' => 'required|min:8',
            'birthdate' => 'required|date|before_or_equal:'.\Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
            'gender' => 'required',
        ]);
        if ($validator->fails()) {
            return Message::error($validator->errors()->first(),null,$validator->errors()->first());
        }

        // check email
        if (Patient::where("email", $request->email)->count() > 0)
           return Message::error(Message::$EMAIL_UNIQUE,  null,Message::$EMAIL_UNIQUE_EN);

        // check phone
        if (Patient::where("phone", $request->phone)->count() > 0)
                   return Message::error(Message::$PHONE_UNIQUE,  null ,Message::$PHONE_UNIQUE_EN);

        try {


            $user = Patient::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
                'insurance_id' => $request->insurance_id,
                'address' => $request->address,
                'password' => bcrypt($request->password),
                'sms_code' => rand(11111,99999),
                'active' => 0,
                "insurance_code_id" => $request->insurance_code_id
            ]);

            if ($request->hasFile('photo')) {
                $user->photo = Helper::uploadImg($request->file("photo"), "/patient/");
            }
            $user->save();


            // send sms message
           Helper::sendSms("Your Code is ".$user->sms_code, $user->phone);
            return Message::success(Message::$SUCCESS_REGISTER,$user->getJson(),Message::$SUCCESS_REGISTER_EN);
        } catch (\Exception $e) {


            return Message::error(Message::$ERROR, null,Message::$ERROR_EN);
        }
    }


    /**
     * verfiy patient account with sms code
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
            $user = Patient::find($request->user_id);

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
            $user = Patient::find($request->user_id);
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
             $user = Patient::where('phone', $request->phone)->first();
           
             Helper::sendSms("Your Code is ".$user->sms_code, $user->phone);
              return Message::success(Message::$VERIFIED, $user->getJson(),Message::$VERIFIED_EN);
            
           
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR, $e->getMessage() ,  null,Message::$ERROR_EN);
        }
    }
    /**
     * login patient api
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
            $user = Patient::where('phone', $request->phone)->first();
            
            if (!$user)
                return Message::error(Message::$PHONE_ERROR_LOGIN,null,Message::$PHONE_ERROR_LOGIN_EN);
            
            
            $user->unblock();

            if (Hash::check($request->password, $user->password)) {

                if ($user->active != 1) {
                    return Message::error(Message::$ACTIVE_NULL,null,Message::$ACTIVE_NULL_EN);
                }

                $user->api_token = str_random(rand(20, 100));
                $user->update();
                 
                // add view for application
                View::create([ 
                    'date' => date("y-m-d"),
                    'ip' => $user->api_token 
                ]);
 
                return Message::success(Message::$SUCCESS_LOGIN, $user->getJson(),Message::$SUCCESS_LOGIN_EN);
            } else {
                return Message::error(Message::$PHONE_ERROR_LOGIN,null,Message::$PHONE_ERROR_LOGIN_EN);
            }
        } catch (\Exception $e) {
            return Message::error(Message::$ERROR,  null,Message::$ERROR_EN);
        }
    }

    /**
     *  send new password in sms to patient phone
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
            $user = Patient::where('phone', $request->phone)->first();
            if (!$user) {
                return Message::error(Message::$PHONE_ERROR,null,Message::$PHONE_ERROR_EN);
            }

            $newPassword = rand(11111, 99999);
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
     * update patient password based on sms password
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
            $user = Patient::where('phone', $request->phone)->first();
            if (!$user) {
               return Message::error(Message::$PHONE_ERROR,null,Message::$PHONE_ERROR_EN); }

                
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
     * update patient profile info
     *
     * @param Request $request
     * @return void
     */
    public function update_profile(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'api_token' => 'required',
        ]);

        if ($validator->fails()) {
            return Message::error($validator->errors()->first());
        }

        if (Patient::where("api_token", $request->api_token)->count() <= 0)
          return Message::error(Message::$API_LOGIN,null,Message::$API_LOGIN_EN);
      
        try {
            $user = Patient::find($request->user_id);
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->birthdate = $request->birthdate;
            $user->address = $request->address;


            if ($request->hasFile('photo')) {
                // delete old image
                try{
                    unlink(public_path("image/patient") . "/" .  $user->photo);
               }
               
               catch (\Exception $exc) { }
                 // upload new image
                $user->photo = Helper::uploadImg($request->file("photo"), "/patient/");
            }

            $user->update();

             return Message::success(Message::$EDIT,$user->getJson(),Message::$EDIT_EN);
        } catch (\Exception $exc) {
            return Message::error(Message::$ERROR . $exc->getMessage(), null);
        }
    }
}

