<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

    
Route::get('/pharmacyorder/show/{pharmacyorder}', 'PharmacyOrderController@show');
Route::get('/laborder/show/{laborder}', 'LabOrderController@show');
Route::get('/radiologyorder/show/{radiologyorder}', 'RadiologyOrderController@show');

// check if user login
Route::group(["middleware" => "auth"], function() {

    Route::get('/', 'DashboardController@index');
    Route::get('/home', 'DashboardController@index');
    Route::get('dashboard', 'DashboardController@dashboard');

    Route::post('/notification/send-message', 'MessageSenderController@sendMessage'); 

    // check role of the user
// icu routes
    Route::group(["middleware" => "role:" . App\Role::$ICU], function() {
        Route::get('/icu/create', 'IcuController@create');
        Route::get('/icu/map', 'IcuController@map');
        Route::post('/icu/store', 'IcuController@store');
        Route::get('/icu/edit/{icu}', 'IcuController@edit')->name("icuUpdate");
        Route::post('/icu/update/{icu}', 'IcuController@update');
        Route::get('/icu/remove/{icu}', 'IcuController@destroy')->name("icuDelete");
        Route::get('/icu/show/{icu}', 'IcuController@show')->name("icuShow");
        Route::get('/icu', 'IcuController@index');
        Route::get('/icu/data', 'IcuController@getData')->name("icuData");
    });

    // incubation routes
    Route::group(["middleware" => "role:" . App\Role::$INCUBATION], function() {
        Route::get('/incubation/create', 'IncubationController@create');
        Route::get('/incubation/map', 'IncubationController@map');
        Route::post('/incubation/store', 'IncubationController@store');
        Route::get('/incubation/edit/{incubation}', 'IncubationController@edit')->name("incubationUpdate");
        Route::post('/incubation/update/{incubation}', 'IncubationController@update');
        Route::get('/incubation/remove/{incubation}', 'IncubationController@destroy')->name("incubationDelete");
        Route::get('/incubation/show/{incubation}', 'IncubationController@show')->name("incubationShow");
        Route::get('/incubation', 'IncubationController@index');
        Route::get('/incubation/data', 'IncubationController@getData')->name("incubationData");
    });

    // area routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/area/create', 'AreaController@create');
        Route::post('/area/store', 'AreaController@store');
        Route::get('/area/edit/{area}', 'AreaController@edit')->name("areaUpdate");
        Route::post('/area/update/{area}', 'AreaController@update');
        Route::get('/area/remove/{area}', 'AreaController@destroy')->name("areaDelete");
        Route::get('/area', 'AreaController@index');
        Route::get('/area/data', 'AreaController@getData')->name("areaData");
    });

    // city routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/city/create', 'CityController@create');
        Route::post('/city/store', 'CityController@store');
        Route::get('/city/edit/{city}', 'CityController@edit')->name("cityUpdate");
        Route::post('/city/update/{city}', 'CityController@update');
        Route::get('/city/remove/{city}', 'CityController@destroy')->name("cityDelete");
        Route::get('/city', 'CityController@index');
        Route::get('/city/data', 'CityController@getData')->name("cityData");
    });

// medicine routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/medicine/create', 'MedicineController@create');
        Route::post('/medicine/store', 'MedicineController@store');
        Route::get('/medicine/edit/{medicine}', 'MedicineController@edit')->name("medicineUpdate");
        Route::post('/medicine/update/{medicine}', 'MedicineController@update');
        Route::get('/medicine/remove/{medicine}', 'MedicineController@destroy')->name("medicineDelete");
        Route::get('/medicine', 'MedicineController@index');
        Route::get('/medicine/data', 'MedicineController@getData')->name("medicineData");
    });

// specialization routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/specialization/create', 'SpecializationController@create');
        Route::post('/specialization/store', 'SpecializationController@store');
        Route::get('/specialization/edit/{specialization}', 'SpecializationController@edit')->name("specializationUpdate");
        Route::post('/specialization/update/{specialization}', 'SpecializationController@update');
        Route::get('/specialization/remove/{specialization}', 'SpecializationController@destroy')->name("specializationDelete");
        Route::get('/specialization', 'SpecializationController@index');
        Route::get('/specialization/data', 'SpecializationController@getData')->name("specializationData");
    });

// degree routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/degree/create', 'DegreeController@create');
        Route::post('/degree/store', 'DegreeController@store');
        Route::get('/degree/edit/{degree}', 'DegreeController@edit')->name("degreeUpdate");
        Route::post('/degree/update/{degree}', 'DegreeController@update');
        Route::get('/degree/remove/{degree}', 'DegreeController@destroy')->name("degreeDelete");
        Route::get('/degree', 'DegreeController@index');
        Route::get('/degree/data', 'DegreeController@getData')->name("degreeData");
    });

// medicinetype routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/medicinetype/create', 'MedicineTypeController@create');
        Route::post('/medicinetype/store', 'MedicineTypeController@store');
        Route::get('/medicinetype/edit/{medicinetype}', 'MedicineTypeController@edit')->name("medicinetypeUpdate");
        Route::post('/medicinetype/update/{medicinetype}', 'MedicineTypeController@update');
        Route::get('/medicinetype/remove/{medicinetype}', 'MedicineTypeController@destroy')->name("medicinetypeDelete");
        Route::get('/medicinetype', 'MedicineTypeController@index');
        Route::get('/medicinetype/data', 'MedicineTypeController@getData')->name("medicinetypeData");
    });

// ray routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/ray/create', 'RayController@create');
        Route::post('/ray/store', 'RayController@store');
        Route::get('/ray/edit/{ray}', 'RayController@edit')->name("rayUpdate");
        Route::post('/ray/update/{ray}', 'RayController@update');
        Route::get('/ray/remove/{ray}', 'RayController@destroy')->name("rayDelete");
        Route::get('/ray', 'RayController@index');
        Route::get('/ray/data', 'RayController@getData')->name("rayData");
    });

// analysis routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/analysis/create', 'AnalysisController@create');
        Route::post('/analysis/store', 'AnalysisController@store');
        Route::get('/analysis/edit/{analysis}', 'AnalysisController@edit')->name("analysisUpdate");
        Route::post('/analysis/update/{analysis}', 'AnalysisController@update');
        Route::get('/analysis/remove/{analysis}', 'AnalysisController@destroy')->name("analysisDelete");
        Route::get('/analysis', 'AnalysisController@index');
        Route::get('/analysis/data', 'AnalysisController@getData')->name("analysisData");
    });

// insurance routes
    Route::group(["middleware" => "role:" . App\Role::$INSURANCE], function() { 
        Route::post('/insurance/store', 'InsuranceController@store'); 
        Route::post('/insurance/update/{insurance}', 'InsuranceController@update');
        Route::get('/insurance/edit/{insurance}', 'InsuranceController@edit')->name("insuranceEdit");
        Route::get('/insurance/remove/{insurance}', 'InsuranceController@destroy')->name("insuranceDelete");
        Route::get('/insurance/show/{insurance}', 'InsuranceController@show')->name("insuranceShow");
        Route::get('/insurance', 'InsuranceController@index');
        Route::get('/insurance/data', 'InsuranceController@getData')->name("insuranceData");
    });

// user insurance routes
    Route::group(["middleware" => "role:" . App\Role::$INSURANCE], function() { 
        Route::post('/userinsurance/store', 'UserInsuranceController@store');
        Route::get('/userinsurance/edit/{userinsurance}', 'UserInsuranceController@edit')->name("userinsuranceUpdate");
        Route::post('/userinsurance/update/{userinsurance}', 'UserInsuranceController@update');
        Route::get('/userinsurance/remove/{userinsurance}', 'UserInsuranceController@destroy')->name("userinsuranceDelete");
        Route::get('/userinsurance', 'UserInsuranceController@index');
        Route::get('/userinsurance/data', 'UserInsuranceController@getData')->name("userinsuranceData");
    });

// doctor routes
    Route::group(["middleware" => "role:" . App\Role::$DOCTOR], function() {
        Route::get('/doctor/create', 'DoctorController@create');
        Route::get('/doctor/show/{doctor}', 'DoctorController@show');
        Route::post('/doctor/store', 'DoctorController@store');
        Route::get('/doctor/edit/{doctor}', 'DoctorController@edit')->name("doctorUpdate");
        Route::post('/doctor/update/{doctor}', 'DoctorController@update');
        Route::get('/doctor/remove/{doctor}', 'DoctorController@destroy')->name("doctorDelete");
        Route::get('/doctor', 'DoctorController@index');
        Route::get('/doctor/data', 'DoctorController@getData')->name("doctorData");
    });

// clinic routes
    Route::group(["middleware" => "role:" . App\Role::$DOCTOR], function() {
        Route::get('/clinic/deactive/{clinic}', 'ClinicController@deactive');
        Route::get('/clinic/active/{clinic}', 'ClinicController@active');
        Route::get('/clinic', 'ClinicController@index');
        Route::get('/clinic/map', 'ClinicController@map');
        Route::get('/clinic/show/{clinic}', 'ClinicController@show');
        Route::get('/clinic/edit/{clinic}', 'ClinicController@edit');
        Route::post('/clinic/store', 'ClinicController@store');
        Route::post('/clinic/update/{clinic}', 'ClinicController@update');
        Route::get('/clinic/remove/{clinic}', 'ClinicController@destroy');
        Route::get('/clinic/data', 'ClinicController@getData')->name("clinicData");
    });

// clinic orders routes
// clinic routes
    Route::group(["middleware" => "role:" . App\Role::$DOCTOR], function() {
        Route::get('/clinicorder/deactive/{clinicorder}', 'ClinicOrderController@deactive');
        Route::get('/clinicorder/active/{clinicorder}', 'ClinicOrderController@active');
        Route::get('/clinicorder', 'ClinicOrderController@index');
        Route::get('/clinicorder/create', 'ClinicOrderController@create');
        Route::get('/clinicorder/data', 'ClinicOrderController@getData')->name("clinicorderData");
    });

// lab routes
    Route::group(["middleware" => "role:" . App\Role::$LAB], function() {
        Route::get('/lab/deactive/{lab}', 'LabController@deactive');
        Route::get('/lab/active/{lab}', 'LabController@active');
        Route::get('/lab/show/{lab}', 'LabController@show');
        Route::get('/lab/edit/{lab}', 'LabController@edit');
        Route::post('/lab/update/{lab}', 'LabController@update');
        Route::post('/lab/store', 'LabController@store');
        Route::get('/lab', 'LabController@index');
        Route::get('/lab/map', 'LabController@map');
        Route::get('/lab/data', 'LabController@getData')->name("labData");
    });

// lab orders routes
    Route::group(["middleware" => "role:" . App\Role::$LAB], function() {
        Route::get('/laborder', 'LabOrderController@index');
        Route::get('/laborder/remove/{laborder}', 'LabOrderController@destroy');
        Route::get('/laborder/show/{laborder}', 'LabOrderController@show');
        Route::get('/laborder/data', 'LabOrderController@getData')->name("laborderData");
    });

// labdoctor routes
    Route::group(["middleware" => "role:" . App\Role::$LAB], function() {
        Route::get('/labdoctor/create', 'LabDoctorController@create');
        Route::post('/labdoctor/store', 'LabDoctorController@store');
        Route::get('/labdoctor/edit/{labdoctor}', 'LabDoctorController@edit')->name("labdoctorUpdate");
        Route::post('/labdoctor/update/{labdoctor}', 'LabDoctorController@update');
        Route::get('/labdoctor/remove/{labdoctor}', 'LabDoctorController@destroy')->name("labdoctorDelete");
        Route::get('/labdoctor', 'LabDoctorController@index');
        Route::get('/labdoctor/data', 'LabDoctorController@getData')->name("labdoctorData");
    });

// radiology routes
    Route::group(["middleware" => "role:" . App\Role::$RADIOLOGY], function() {
        Route::get('/radiology/deactive/{radiology}', 'RadiologyController@deactive');
        Route::get('/radiology/active/{radiology}', 'RadiologyController@active');
        Route::get('/radiology/show/{radiology}', 'RadiologyController@show');
        Route::get('/radiology/edit/{radiology}', 'RadiologyController@edit');
        Route::post('/radiology/update/{radiology}', 'RadiologyController@update');
        Route::post('/radiology/store', 'RadiologyController@store');
        Route::get('/radiology', 'RadiologyController@index');
        Route::get('/radiology/map', 'RadiologyController@map');
        Route::get('/radiology/data', 'RadiologyController@getData')->name("radiologyData");
    });

// radiology orders routes
    Route::group(["middleware" => "role:" . App\Role::$RADIOLOGY], function() {
        Route::get('/radiologyorder', 'RadiologyOrderController@index');
        Route::get('/radiologyorder/remove/{radiologyorder}', 'RadiologyOrderController@destroy');
        Route::get('/radiologyorder/data', 'RadiologyOrderController@getData')->name("radiologyorderData");
    });

// radiologydoctor routes
    Route::group(["middleware" => "role:" . App\Role::$RADIOLOGY], function() {
        Route::get('/radiologydoctor/create', 'RadiologyDoctorController@create');
        Route::post('/radiologydoctor/store', 'RadiologyDoctorController@store');
        Route::get('/radiologydoctor/edit/{radiologydoctor}', 'RadiologyDoctorController@edit')->name("radiologydoctorUpdate");
        Route::post('/radiologydoctor/update/{radiologydoctor}', 'RadiologyDoctorController@update');
        Route::get('/radiologydoctor/remove/{radiologydoctor}', 'RadiologyDoctorController@destroy')->name("radiologydoctorDelete");
        Route::get('/radiologydoctor', 'RadiologyDoctorController@index');
        Route::get('/radiologydoctor/data', 'RadiologyDoctorController@getData')->name("radiologydoctorData");
    });


// pharmacy routes
    Route::group(["middleware" => "role:" . App\Role::$PHARMACY], function() {
        Route::get('/pharmacy/deactive/{pharmacy}', 'PharmacyController@deactive');
        Route::get('/pharmacy/active/{pharmacy}', 'PharmacyController@active');
        Route::get('/pharmacy', 'PharmacyController@index');
        Route::get('/pharmacy/map', 'PharmacyController@map');
        Route::get('/pharmacy/edit/{pharmacy}', 'PharmacyController@edit');
        Route::get('/pharmacy/show/{pharmacy}', 'PharmacyController@show');
        Route::get('/pharmacy/working_hours/edit/{pharmacy}', 'PharmacyController@workingHours');
        Route::post('/pharmacy/working_hours/update/{pharmacy}', 'PharmacyController@updateWorkingHours');
        Route::post('/pharmacy/store', 'PharmacyController@store');
        Route::post('/pharmacy/update/{pharmacy}', 'PharmacyController@update');
        Route::get('/pharmacy/data', 'PharmacyController@getData')->name("pharmacyData");
    });

// pharmacy orders routes
    Route::group(["middleware" => "role:" . App\Role::$PHARMACY], function() {
        Route::get('/pharmacyorder', 'PharmacyOrderController@index');
        Route::get('/pharmacyorder/remove/{pharmacyorder}', 'PharmacyOrderController@destroy'); 
        Route::get('/pharmacyorder/data', 'PharmacyOrderController@getData')->name("pharmacyorderData");
    });

// pharmacydoctor routes
    Route::group(["middleware" => "role:" . App\Role::$PHARMACY], function() {
        Route::get('/pharmacydoctor/create', 'PharmacyDoctorController@create');
        Route::post('/pharmacydoctor/store', 'PharmacyDoctorController@store');
        Route::get('/pharmacydoctor/edit/{pharmacydoctor}', 'PharmacyDoctorController@edit')->name("pharmacydoctorUpdate");
        Route::post('/pharmacydoctor/update/{pharmacydoctor}', 'PharmacyDoctorController@update');
        Route::get('/pharmacydoctor/remove/{pharmacydoctor}', 'PharmacyDoctorController@destroy')->name("pharmacydoctorDelete");
        Route::get('/pharmacydoctor', 'PharmacyDoctorController@index');
        Route::get('/pharmacydoctor/data', 'PharmacyDoctorController@getData')->name("pharmacydoctorData");
    });

// patient routes
    Route::group(["middleware" => "role:" . App\Role::$PATIENT], function() {
        Route::get('/patient/deactive/{patient}', 'PatientController@deactive');
        Route::get('/patient/active/{patient}', 'PatientController@active');
        Route::get('/patient', 'PatientController@index');
        Route::post('/patient/store', 'PatientController@store');
        Route::post('/patient/update/{patient}', 'PatientController@update');
        Route::get('/patient/edit/{patient}', 'PatientController@edit');
        Route::get('/patient/show/{patient}', 'PatientController@show');
        Route::get('/patient/data', 'PatientController@getData')->name("patientData");
    });

// user routes
    Route::group(["middleware" => "role:" . App\Role::$USER], function() {
        Route::get('/user/create', 'UserController@create');
        Route::post('/user/store', 'UserController@store');
        Route::get('/user/edit/{user}', 'UserController@edit')->name("userUpdate");
        Route::get('/user/role/{user}', 'RoleController@index');
        Route::get('/role/update', 'RoleController@addRole');
        Route::post('/user/update/{user}', 'UserController@update');
        Route::get('/user/remove/{user}', 'UserController@destroy')->name("userDelete");
        Route::get('/user', 'UserController@index');
        Route::get('/user/checkemail', 'UserController@checkemail');
        Route::get('/user/data', 'UserController@getData')->name("userData");
    });
    
    
// option routes
    Route::group(["middleware" => "role:" . App\Role::$OPTION], function() {
        Route::get('/setting/', 'SettingController@index'); 
        Route::get('/setting/translation', 'SettingController@translation'); 
        Route::post('/setting/translation/update', 'SettingController@updateTranslation'); 
        Route::post('/setting/send-message', 'SettingController@sendMessage'); 
    });

    ///////////////////////////////////////////////////////////
    // reports routes
    /////////////////////////////////////////////////////////// 

    Route::group(["middleware" => "role:" . App\Role::$REPORT], function() {
        Route::get('/report/places', 'report\PlacesReportController@index');
        Route::get('/report/clinicorder', 'report\ClinicOrderReportController@index');
        Route::get('/report/laborder', 'report\LabOrderReportController@index');
        Route::get('/report/radiologyorder', 'report\RadiologyOrderReportController@index');
        Route::get('/report/pharmacyorder', 'report\PharmacyOrderReportController@index');
        Route::get('/report/modelorder', 'report\ModelOrderReportController@index');
        Route::get('/report/userview', 'report\UserViewReportController@index');
        Route::get('/report/insurance', 'report\InsuranceReportController@index');
        Route::get('/report/payment', 'report\PaymentReportController@index');
        Route::get('/report/doctor', 'report\DoctorReportController@index');
    });


// profile routes
    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile/update', 'ProfileController@update');
});

// login routes
Route::get('/login', 'LoginController@index');
Route::get('/logout', 'LoginController@logout');
Route::post('/signin', 'LoginController@login');

///////////////////////////////////////////////////////////
// pharmacy doctor dashboard routes
///////////////////////////////////////////////////////////
Route::get('/pharmacydoctordashboard/login', 'pharmacyDashboard\PharmacyDoctorController@login');
Route::post('/pharmacydoctordashboard/sign', 'pharmacyDashboard\PharmacyDoctorController@sign');

Route::group(["middleware" => "pharmacyDoctor"], function() {
    Route::get('/pharmacydoctordashboard', 'pharmacyDashboard\PharmacyDoctorController@index');
    Route::get('/pharmacydoctordashboard/show/{pharmacyorder}', 'pharmacyDashboard\PharmacyDoctorController@show');
    Route::get('/pharmacydoctordashboard/logout', 'pharmacyDashboard\PharmacyDoctorController@logout');
    Route::get('/pharmacydoctordashboard/data', 'pharmacyDashboard\PharmacyDoctorController@getData')->name("pharmacydoctordashboardorderData");
});

///////////////////////////////////////////////////////////
// radiology doctor dashboard routes
///////////////////////////////////////////////////////////
Route::get('/radiologydoctordashboard/login', 'radiologyDashboard\RadiologyDoctorController@login');
Route::post('/radiologydoctordashboard/sign', 'radiologyDashboard\RadiologyDoctorController@sign');
Route::group(["middleware" => "radiologyDoctor"], function() {
    Route::get('/radiologydoctordashboard', 'radiologyDashboard\RadiologyDoctorController@index');
    Route::get('/radiologydoctordashboard/logout', 'radiologyDashboard\RadiologyDoctorController@logout');
    Route::get('/radiologydoctordashboard/show/{radiologyorder}', 'radiologyDashboard\RadiologyDoctorController@show');
    Route::get('/radiologydoctordashboard/data', 'radiologyDashboard\RadiologyDoctorController@getData')->name("radiologydoctordashboardorderData");
});

///////////////////////////////////////////////////////////
// lab doctor dashboard routes
///////////////////////////////////////////////////////////
Route::get('/labdoctordashboard/login', 'labDashboard\LabDoctorController@login');
Route::post('/labdoctordashboard/sign', 'labDashboard\LabDoctorController@sign');
Route::group(["middleware" => "labDoctor"], function() {
    Route::get('/labdoctordashboard', 'labDashboard\LabDoctorController@index');
    Route::get('/labdoctordashboard/logout', 'labDashboard\LabDoctorController@logout');
    Route::get('/labdoctordashboard/show/{laborder}', 'labDashboard\LabDoctorController@show');
    Route::get('/labdoctordashboard/data', 'labDashboard\LabDoctorController@getData')->name("labdoctordashboardorderData");
});

///////////////////////////////////////////////////////////
// insurance user dashboard routes
///////////////////////////////////////////////////////////
Route::get('/insuranceuserdashboard/login', 'insuranceDashboard\InsuranceUserController@login');
Route::post('/insuranceuserdashboard/sign', 'insuranceDashboard\InsuranceUserController@sign');
Route::group(["middleware" => "insurance"], function() {
    Route::get('/insuranceuserdashboard', 'insuranceDashboard\InsuranceUserController@index');
    Route::get('/insuranceuserdashboard/logout', 'insuranceDashboard\InsuranceUserController@logout');
    // pharmacy order
    Route::get('/insuranceuserdashboard/pharmacy/show/{pharmacyOrder}', 'insuranceDashboard\PharmacyOrderController@show');
    Route::get('/insuranceuserdashboard/pharmacy/accept/{order}', 'insuranceDashboard\PharmacyOrderController@acceptOrder');
    Route::get('/insuranceuserdashboard/pharmacy/refuse/{order}', 'insuranceDashboard\PharmacyOrderController@refuseOrder');
    Route::get('/insuranceuserdashboard/pharmacy/data', 'insuranceDashboard\PharmacyOrderController@getData')->name("insuranceuserdashboardpharmacyData");
    // lab order
    Route::get('/insuranceuserdashboard/lab/show/{labOrder}', 'insuranceDashboard\LabOrderController@show');
    Route::get('/insuranceuserdashboard/lab/accept/{order}', 'insuranceDashboard\LabOrderController@acceptOrder');
    Route::get('/insuranceuserdashboard/lab/refuse/{order}', 'insuranceDashboard\LabOrderController@refuseOrder');
    Route::get('/insuranceuserdashboard/lab/data', 'insuranceDashboard\LabOrderController@getData')->name("insuranceuserdashboardlabData");
    // radiology order
    Route::get('/insuranceuserdashboard/radiology/show/{radiologyOrder}', 'insuranceDashboard\RadiologyOrderController@show');
    Route::get('/insuranceuserdashboard/radiology/accept/{order}', 'insuranceDashboard\RadiologyOrderController@acceptOrder');
    Route::get('/insuranceuserdashboard/radiology/refuse/{order}', 'insuranceDashboard\RadiologyOrderController@refuseOrder');
    Route::get('/insuranceuserdashboard/radiology/data', 'insuranceDashboard\RadiologyOrderController@getData')->name("insuranceuserdashboardradiologyData");

    // report of insurance 
    Route::get('/report/insurance/laborder', 'report\insurance\LabOrderReportController@index');
    Route::get('/report/insurance/radiologyorder', 'report\insurance\RadiologyOrderReportController@index');
    Route::get('/report/insurance/pharmacyorder', 'report\insurance\PharmacyOrderReportController@index');
    Route::get('/report/insurance/clinicorder', 'report\insurance\ClinicOrderReportController@index');
});
  
Route::get('/mobile/doctor', function () {   
    return view("mobile.doctor");
});

Route::get('/mobile/lab', function () {   
    return view("mobile.lab");
});

Route::get('/mobile/radiology', function () {   
    return view("mobile.radiology");
});

Route::get('/mobile/pharmacy', function () {   
    return view("mobile.pharmacy");
});

Route::get('/mobile/patient', function () {   
    return view("mobile.patient");
});

Route::get('/mobile/test', function () {   
    return view("mobile.test");
});
Route::get('/test', function () {  
    //App\Translation::create([ "key" => "fadf" ]);
    //echo __('ali');
});


 