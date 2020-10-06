<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/////////////////Patient Routes//////////////////////////////////////
Route::post('patient_register', 'Api\Patient\AuthController@register');
Route::post('patient_login', 'Api\Patient\AuthController@login');
Route::post('patient_verify_account', 'Api\Patient\AuthController@verify_account');
Route::post('patient_forget_password', 'Api\Patient\AuthController@forget_password');
Route::post('patient_update_password', 'Api\Patient\AuthController@update_password');
Route::post('patient_update_profile', 'Api\Patient\AuthController@update_profile');
Route::post('patient_resend', 'Api\Patient\AuthController@resend');
Route::post('patient_resend_second', 'Api\Patient\AuthController@resend_two');



//////////////////////////Patient Main function///////////////////
Route::get('incubation/get', 'Api\Patient\PatientIncubationController@incubationFilter');
Route::get('icu/get', 'Api\Patient\PatientIcuController@icuFilter');
Route::get('lab/get', 'Api\Patient\PatientLabController@labFilter');
Route::get('radiology/get', 'Api\Patient\PatientRadiologyController@radiologyFilter');
Route::get('clinic/get', 'Api\Patient\PatienDoctorController@searchClinic');
Route::post('clinic/order/create', 'Api\Patient\PatienDoctorController@createClinicOrder');
Route::post('pharmacy/order/create', 'Api\Patient\PatientPharmacyController@createPharmacyOrder');
Route::post('radiology/order/create', 'Api\Patient\PatientRadiologyController@createRadiologyOrder');
Route::post('lab/order/create', 'Api\Patient\PatientLabController@createLabOrder');
Route::post('patient/rate/doctor', 'Api\Patient\PatienDoctorController@rateDoctor');
Route::post('patient/favourite_doctor', 'Api\Patient\PatienDoctorController@toggleFavourite');
Route::get('patient/favourite/list', 'Api\Patient\PatienDoctorController@getFavouriteList');  
Route::get('get/resrevation', 'Api\Patient\PatienDoctorController@getReservations');


/////////////////Doctor Routes//////////////////////////////////////
////////////////////////Auth Function//////////////////////////////
Route::post('doctor_register', 'Api\Doctor\AuthController@register');
Route::post('doctor_login', 'Api\Doctor\AuthController@login');
Route::post('doctor_verify_account', 'Api\Doctor\AuthController@verify_account');
Route::post('doctor_forget_password', 'Api\Doctor\AuthController@forget_password');
Route::post('doctor_update_password', 'Api\Doctor\AuthController@update_password');
Route::post('doctor_update_profile', 'Api\Doctor\AuthController@update_profile');
Route::post('doctor_resend', 'Api\Doctor\AuthController@resend');
Route::post('doctor_resend_second', 'Api\Doctor\AuthController@resend_two');

//////////////////////Main Function/////////////////////////////////////
Route::post('doctor_create_clinic', 'Api\Doctor\MainController@createClinic');
Route::post('doctor_update_clinic', 'Api\Doctor\MainController@updateClinicWorkingHours');
Route::post('update_clinic', 'Api\Doctor\MainController@updateClinic');

Route::get('clinic/order/get', 'Api\Doctor\MainController@getOrder');
Route::post('clinic/order/reject', 'Api\Doctor\MainController@rejectOrder'); 

Route::get('clinic/get/doctor', 'Api\Doctor\MainController@getClinic');

Route::get('clinic/availability', 'Api\Doctor\MainController@updateAvailability');



/////////////////Pharmacy Routes//////////////////////////////////////
Route::post('pharmacy_register', 'Api\Pharmacy\AuthController@register');
Route::post('pharmacy_login', 'Api\Pharmacy\AuthController@login');
Route::post('pharmacy_verify_account', 'Api\Pharmacy\AuthController@verify_account');
Route::post('pharmacy_forget_password', 'Api\Pharmacy\AuthController@forget_password');
Route::post('pharmacy_update_password', 'Api\Pharmacy\AuthController@update_password');
Route::post('pharmacy_update_profile', 'Api\Pharmacy\AuthController@update_profile');
Route::post('pharmacy_resend', 'Api\Pharmacy\AuthController@resend');
Route::post('pharmacy_resend_second', 'Api\Pharmacy\AuthController@resend_two');


Route::get('pharmacy/order/get', 'Api\Pharmacy\MainController@getOrder');
Route::post('pharmacy/accept/order', 'Api\Pharmacy\MainController@acceptOrder');
Route::post('pharmacy/reject/order', 'Api\Pharmacy\MainController@rejectOrder');





/////////////////Radiology Routes//////////////////////////////////////
Route::post('radiology_register', 'Api\Radiology\AuthController@register');
Route::post('radiology_login', 'Api\Radiology\AuthController@login');
Route::post('radiology_verify_account', 'Api\Radiology\AuthController@verify_account');
Route::post('radiology_forget_password', 'Api\Radiology\AuthController@forget_password');
Route::post('radiology_update_password', 'Api\Radiology\AuthController@update_password');
Route::post('radiology_update_profile', 'Api\Radiology\AuthController@update_profile');
Route::get('radiology/order/get', 'Api\Radiology\MainController@getOrder');
Route::post('radiology_resend', 'Api\Radiology\AuthController@resend');
Route::post('radiology_resend_second', 'Api\Radiology\AuthController@resend_two');




/////////////////Lab Routes//////////////////////////////////////
Route::post('lab_register', 'Api\Lab\AuthController@register');
Route::post('lab_login', 'Api\Lab\AuthController@login');
Route::post('lab_verify_account', 'Api\Lab\AuthController@verify_account');
Route::post('lab_forget_password', 'Api\Lab\AuthController@forget_password');
Route::post('lab_update_password', 'Api\Lab\AuthController@update_password');
Route::post('lab_update_profile', 'Api\Lab\AuthController@update_profile');
Route::get('lab/order/get', 'Api\Lab\MainController@getOrder');
Route::post('lab_resend', 'Api\Lab\AuthController@resend');
Route::post('lab_resend_second', 'Api\Lab\AuthController@resend_two');



//////////////////////////////////  Global Main function      ///////////////////////////////////
Route::get('show_specialization', 'Api\MainController@showSpecialization');
Route::get('show_degree', 'Api\MainController@showDegree');
Route::get('show_city', 'Api\MainController@showCity');
Route::get('show_area', 'Api\MainController@showArea');
Route::get('show_insurance', 'Api\MainController@showInsurance');
Route::get('show_icu', 'Api\MainController@showIcu');
Route::get('show_incubation', 'Api\MainController@showIncubation');
Route::get('show_anlysis', 'Api\MainController@showAnalysis');
Route::get('show_rays', 'Api\MainController@showRays');
Route::get('show_medicines', 'Api\MainController@showMedicines');
Route::get('show_phone', 'Api\MainController@showPhone');
Route::get('show_medicines_type', 'Api\MainController@medicineType');
Route::get('show/radiology', 'Api\MainController@showRadiology');
Route::get('show/lab', 'Api\MainController@showLab');
Route::get('get/notification', 'Api\MainController@getNotification');
Route::post('token/update', 'Api\MainController@updateToken');

Route::get('contact', 'Api\MainController@facebook');
Route::post('doctor/set', 'Api\MainController@updateDoctor');
Route::post('patient/cancel-order', 'Api\MainController@cancelOrderPatient');
Route::post('cancel-order', 'Api\MainController@cancelOrder');
Route::post('accept-order', 'Api\MainController@acceptOrder');
