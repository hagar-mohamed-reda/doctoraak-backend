<?php

namespace App;

use App\helper\Helper;


class Message
{ 
    
    /////start ar messages ////////
    public static $DONE = "تم اضافة البيانات";
    public static $REMOVE = "تم حذف البيانات";
    public static $ERROR = "هناك خطا ما من فضلك تاكد من صحة البيانات";
    public static $EDIT = "تم تعديل البيانات";
    public static $DELETE_ERROR = "لا يمكن حذف بيانات تعتمد على بيانات اخرى";
    public static $LOGIN_ERROR = "من فضلك تاكد من كلمة السر او اسم المستخدم";
    public static $USERNAME_UNIQUE = "اسم المستخدم الذى ادخلته موجود بالفعل";
    public static $EMAIL_UNIQUE = "البريد الالكترونى الذى ادخلته موجود بالفعل";
    public static $WELCOME = "مرحبا بك سيدى "; 
    public static $DAY_OFF = "يبدوا ان هذا اليوم اجازه"; 
    public static $DAY_BUSY = "الحجزات ممتلئه فى هذا اليوم من فضلك قم بختيار يوم اخر"; 
    public static $ORDER_DONE = "تم ارسال الطلب بنجاح"; 
    public static $DATA_NOT_FIND = "لا يوجد بيانات"; 
    public static $PHARMACY_ORDER_MESSAGE = "تم ارسال طلبك الى n صيدليات مجاوره لك يرجى الانتظار حتى يتم استقبال طلبك من احدى الطيدليات"; 
    public static $NO_PHARMACY = "عذرا لا يوجد صيدليات بجوارك";
    public static $USER_ROLE_ERROR = "لا يمكنك تعديل صلحية المستخدمين لان هذه الصلاحيه اساسيه";
    public static $REQUIRED_IMAGE = "image extension not valid";
    public static $SUCCESS_REGISTER = " تم التسجيل بنجاح ";
    public static $SUCCESS_LOGIN = " تم التسجيل بنجاح ";
    public static $PHONE_ERROR_LOGIN = "من فضلك تاكد من الهاتف او كلمة المرور";
    public static $PHONE_UNIQUE = " الهاتف الذى ادخلته موجود بالفعل";
    public static $VERIFIED = "تم التفعيل بنجاح";
    public static $SEND_NEW_PASSWORD = "تم ارسال كلمه سر جديدة ";
    public static $UPDATE_SUCCESS = "تم التحديث بنجاح  ";
    public static $PASSWORD_NOT_CORRECT = "كلمه السر غير صحيحه  ";
    public static $ACTIVE_NULL = "من فضلك قم بتفعيل حسابك";
    public static $PHONE_ERROR = "هذا الهاتف غير موجود";
    public static $NEW_PASSWORD = "رمز التفعيل الخاص بالحساب هى password";
    public static $PASSWORD_SENT = "تم ارسال كلمة المرور الجديده بنجاح الى رقم الهاتف الخاص بك فى رساله";
    public static $SMS_CODE_ERROR = "كود التفعيل الذى ادخلته غير صحيح";
    public static $LAB_ORDER = " تم تاكيد حجزك يا patient في معمل name ورقم حجزك هو numberوهاتف المعمل هو phone والعنوان مدينه city منطقه area ";
    public static $API_LOGIN = "من فضلك قم بتسجيل الدخول اولا";
    public static $CLINIC_SEARCH = "تم العثور على n من العيادات";
    public static $CANT_ORDER_LAST_DATE = "لا يمكنك الحجز فى يوم سابق";
    public static $DOCTOR_RATE = "تم تقيم الدكتور";
    public static $DOCTOR_RATE_ERROR = "تم تقيم هذا الدكتور بالفعل";
    public static $ADD_FAVOURITE = "تم الاضافة الى المفضله";
    public static $REMOVE_FAVOURITE = "تم الحذف من المفضله";
    public static $REMOVE_DOCTOR_FAVOURITE = "تم تقيم هذا الدكتور بالفعل";
    public static $CLINIC_WORKIING_HOURS_LIMIT = "عذا يجب ادخال مواعد الاسبوع كامله";
    public static $MAX_RESERVATION_NUMBER = "عفوا لا يمكنك عمل اكثر من n حجز لهذه العياده فى اليوم الواحد";
    public static $CLINIC_RESERVATION_PER_WEEK = "عدد حجوزات هذا الاسبوع هو n";
    public static $REJECT_ORDER = "تم الغاء هذا الحجز وسيتم اغلاء هذا الحجز من حجزات اليوم";
    public static $LAB_SEARCH = "تم العثور علي عدد n من معامل التحاليل ";
    public static $RADIOLOGY_SEARCH = "تم العثور علي عدد n من معامل الاشعه  ";
    public static $RADIOLOGY_ORDER =" تم تاكيد حجزك يا patient في معمل name ورقم حجزك هو numberوهاتف المعمل هو phone والعنوان مدينه city منطقه area";
    public static $INCUBATION_SEARCH = "تم العثور علي عدد n من الحضانات  ";
    public static $ICU_SEARCH = "تم العثور علي عدد n من العنايه المركزه   ";
    public static $RESERVATION_FOR_PATIENT = "عدد حجوزات هو n";
    public static $RATE= "تم التقيم بنجاح ";
    public static $CLINIC_RESERVATION = '
         تم تاكيد حجزك يا  patient, 
         مع الدكتور  doctor, 
        فى تمام الساعه  time, 
        ورقم الحجز  number, 
        ورقم الشفت  part, 
        هاتف العياده  phone, 
        العنوان city, area, ';
    public static $MAX_ORDER_NUMBER = "لا يمكن عمل اكثر من n من الطلبات خلال اليوم الواحد ";
    public static $MAX_ORDER__DETAILS_NUMBER = "لا يمكن اضافه اكثر من  n من الاصناف في الطلب الواحد  ";
    /////////////end ar messages /////////////////////////
 
    ///////////////start en messages ///////////////////////   
    
    public static $DONE_EN ="Done";
    public static $REMOVE_EN = "Successful deleted";
    public static $ERROR_EN = "There is an erreor please check data";
    public static $EDIT_EN = "Information Update   ";
    public static $DELETE_ERROR_EN = "You Can't delete data depended on others data   ";
    public static $LOGIN_ERROR_EN = "Please check your username or your password.     ";
    public static $USERNAME_UNIQUE_EN = "Username is already taken      ";
    public static $EMAIL_UNIQUE_EN = "Email is aleardy taken ";
    public static $WELCOME_EN = "Welcome Sir.   "; 
    public static $DAY_OFF_EN = " Today is off please try another day  "; 
    public static $DAY_BUSY_EN = "Today is busy please try another day "; 
    public static $ORDER_DONE_EN = "Order send succesfully."; 
    public static $DATA_NOT_FIND_EN = "Data not found.  "; 
     
     
    public static $NO_PHARMACY_EN = " Sorry no pharmacies here. ";
    public static $REQUIRED_IMAGE_EN = "image extension not valid";
    public static $SUCCESS_REGISTER_EN = " Registered Succesfully. ";
    public static $SUCCESS_LOGIN_EN = " Login Succesfully.   ";
    public static $PHONE_ERROR_LOGIN_EN = "   Please check your phone number or your password .    ";
    public static $PHONE_UNIQUE_EN = " Phone is already taken .";
    public static $VERIFIED_EN = "Active Succesfully.  ";
    public static $SEND_NEW_PASSWORD_EN = "Send  password to your account .     ";
    public static $UPDATE_SUCCESS_EN = "Updated Succesfully.    ";
    public static $PASSWORD_NOT_CORRECT_EN = " Password Error     ";
    public static $ACTIVE_NULL_EN = "Please Active Your Account.    ";
    public static $PHONE_ERROR_EN = "  Phone Not Found . ";
    public static $NEW_PASSWORD_EN = "Active Code is password ";
    public static $PASSWORD_SENT_EN = "New Passwoed is sent to your phone .";
    public static $SMS_CODE_ERROR_EN = "Code Not True .     ";
   
   
    public static $LAB_ORDER_EN = "your Order Is Done patient in Lab name and your number is numbers and lab phone is phones and address is city citys .. area areas  ";
    public static $API_LOGIN_EN = "Please Login firest.  ";
    public static $CLINIC_SEARCH_EN = "Find Number x Of Clinics   ";
    public static $CANT_ORDER_LAST_DATE_EN = " You Can't Book in brevious day. ";
    public static $DOCTOR_RATE_EN = "Doctor Rated.  ";
    public static $DOCTOR_RATE_ERROR_EN = "Doctor already rated.    ";
    public static $ADD_FAVOURITE_EN = "Add  To Favourites.   ";
    public static $REMOVE_FAVOURITE_EN = "  Remove From Favourites ";
    public static $REMOVE_DOCTOR_FAVOURITE_EN = "Doctor already added to favourite. ";
    public static $CLINIC_WORKIING_HOURS_LIMIT_EN = "Sorry you must enter your weekly dates ";
    public static $MAX_RESERVATION_NUMBER_EN = "Sorry, you cannot book more than x reservations for that same day clinic";
    public static $CLINIC_RESERVATION_PER_WEEK_EN = "This week's number of bookings is x";
    public static $REJECT_ORDER_EN = "This reservation has been canceled and will be canceled from the reservation list  ";
    public static $LAB_SEARCH_EN = "Number x of Labs  your location was found   ";
    public static $RADIOLOGY_SEARCH_EN = "Number x Radiology of your location was found    ";
    public static $RADIOLOGY_ORDER_EN = "your Order Is Done patient in Radiology name and your number is numbers  and radiology phone is phones and address is city citys .. area areas  ";
    public static $INCUBATION_SEARCH_EN = " Find x Incubation  ";
    public static $ICU_SEARCH_EN = "Find x Icu    ";
    public static $RESERVATION_FOR_PATIENT_EN = " Reservation number is x";
    public static $RATE_EN= "Succesful rated   ";
  // public static $CLINIC_RESERVATION_EN = "تم تاكيد حجزك يا patient مع الدكتور doctor فى تمام الساعه time ورقم حجزك هو number فى شفت رقم part";
    public static $MAX_ORDER_NUMBER_EN = "You Can't make more then x order      ";
    public static $MAX_ORDER__DETAILS_NUMBER_EN = "You Can't make more then x order details ";

   
   
   
    public static $PHARMACY_ORDER_MESSAGE_EN = "Your request has been sent to x pharmacies Please wait for your order to be accepted"; 
    public static $CLINIC_RESERVATION_EN = '
         you order has been confirmed {patient}, 
         with  {doctor}, 
         in the time {time}, 
         reservation number {number},  
         part number {part}, 
         clinic phone {phones}, 
         clinic address {citys}, {areas}, 
    ';

     public static $PHARMACY_SEARCH = "تم العثور علي عدد n من الصيدليات "  ;
     public static $PHARMACY_SEARCH_EN = "Number x of Pharmacy  your location was found   ";

    public static $PHARMACY_NEW__ORDER = " تم تاكيد طلبك  يا patient في صيدليه name ورقم طلبك هو  هو numberوهاتف الصيدليه هو phone ";
    
    public static $PHARMACY_NEW__ORDER_EN = "your Order Is Done patient in Pharmacy name and your number is numbers and Pharmacy phone is phones   ";
   
 // public static $RADIOLOGY_ORDER_EN = "Your reservation has been confirmed, patient in the name radiology and your reservation number is numbers";
  // public static $LAB_ORDER_EN = " Your reservation has been confirmed, patient in the name lab and your reservation number is numbers ";
   
    public static function success($message = null, $data = null, $message_en = null)
    {
        return Helper::responseJson(1, $message,  $data, $message_en);
    }

    public static function error($message = null, $data = null, $message_en = null)
    {
        return Helper::responseJson(0, $message, $data,$message_en);
    }

    public static function redirect($route, $message, $status) {
        $response = [
            "status" => $status,
            "message" => $message,
            "data" => null,
        ];

        return $response;

        //return redirect($route . "?msg=" . $message . "&status=". $status);
    }

    public static function redirectTo($route, $message, $status, $img=null, $title=null) {
        return redirect($route . "?msg=" . $message . "&status=". $status . "&img=". $img . "&title=". $title);
    }
}
