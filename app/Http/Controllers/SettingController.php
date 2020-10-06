<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Message;
use App\helper\Helper; 
use App\Doctor;
use App\Patient;
use App\Lab;
use App\Radiology;
use App\Pharmacy;
use App\Translation;

class SettingController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("option.index");
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function translation() {
        return view("setting.translation");
    }
    /**
     * send message to all users
     */
    public function sendMessage(Request $request) {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $pharmacies = Pharmacy::all();
        $labs = Lab::all();
        $radiologys = Radiology::all();
        
        foreach ($doctors as $doctor) {
            Doctor::notify($request->title_ar, $request->title_en, $request->message_ar, $request->message_en, null, $doctor->id);
        }
        foreach ($patients as $patient) {
            Patient::notify($request->title_ar, $request->title_en, $request->message_ar, $request->message_en, null, $patient->id);
        }
        foreach ($pharmacies as $pharmacy) {
            Pharmacy::notify($request->title_ar, $request->title_en, $request->message_ar, $request->message_en, null, $pharmacy->id);
        }
        foreach ($labs as $lab) {
            Lab::notify($request->title_ar, $request->title_en, $request->message_ar, $request->message_en, null, $lab->id);
        }
        foreach ($radiologys as $radiology) {
            Radiology::notify($request->title_ar, $request->title_en, $request->message_ar, $request->message_en, null, $radiology->id);
        }
        
        return Helper::responseJson(1, "تم ارسال الرساله");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTranslation(Request $request) {
        $translations = json_decode($request->translations);

        foreach ($translations as $item) {
            $translation = Translation::find($item->id);

            if ($translation)
                $translation->update([
                    "word_en" => $item->word_en,
                    "word_ar" => $item->word_ar,
                ]);
        }


        return Message::success(Message::$DONE);
    }
   

}
