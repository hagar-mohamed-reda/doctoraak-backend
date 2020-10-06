<?php

namespace App\helper;

use App\Message;

class Helper {

    //// function for return response in json format ///////
    public static function responseJson($status, $message, $data = null, $message_en=null) {
        $response = [
            'status' => $status,
            'message' => $message,
            'message_en' => $message_en,
            'data' => $data
        ];
        return response()->json($response);
    }

    public static function removeFile($filename) {
        try {
            unlink($filename);
            return true;
        } catch (\Exception $exc) {
            return false;
        }
    }
    
    
     /**
     *  function to send  mobile sms to user 
     * @param type $message 
     * @param type $phone
     * @return type
     */
 public static function sendSms( $message,$phone)
{
    /*
    $url = 'https://smsmisr.com/api/webapi/?';
    $push_payload = array(
        "username" => "5EZNjMJPsc",
        "password" => "Y3q4PUuiLC",
        "language" => "2",
        "sender" => "Sphinx AT",
        "mobile" => '2' . $phone,
        "message" => $message,
    );

    $rest = curl_init();
    curl_setopt($rest, CURLOPT_URL, $url . http_build_query($push_payload));
    curl_setopt($rest, CURLOPT_POST, 1);
    curl_setopt($rest, CURLOPT_POSTFIELDS, $push_payload);
    curl_setopt($rest, CURLOPT_SSL_VERIFYPEER, true);  //disable ssl .. never do it online
    curl_setopt($rest, CURLOPT_HTTPHEADER,
        array(
            "Content-Type" => "application/x-www-form-urlencoded"
        ));
    curl_setopt($rest, CURLOPT_RETURNTRANSFER, 1); //by ibnfarouk to stop outputting result.
    $response = curl_exec($rest);
    curl_close($rest);
    return $response;
    */
}

///firebase notification
  public static function firebaseNotification($tokens, $data = [])
{

    $registrationIDs = $tokens;
            
    $fcmMsg = array(
        "title" => $data['title_ar'],   
        "title_en" => $data['title_en'] ,
        "body" =>   $data['body_ar'],
        "body_en" => $data['body_en'],  
        'click_action' => "",
        'sound' => "default",
        'color' => "#203E78"
    );
  $fcmFields = array(
        'registration_ids' => $registrationIDs,
        'priority' => 'high',
        'notification' => $fcmMsg,
        'data' => $data
    );

    $headers = array(
        'Authorization: key=AAAAw-FUe2s:APA91bHZzPyd2E7pPWftTjoANgqXu55uBZi8MFcvfFlDpdkiOvi1t1wps4nzVlPb5HUNvW307jtjpx9v44I7sqkcwowprKet0PtAvmVia3EiImkFdQvzb3vlXuST5NI9ZchN0bvp2PKA',
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
/// end

    /**
     * 
     * @param type $to 
     * @param type $from
     * @param type $waiting_time
     * @return type
     */
    public static function workingHours($to, $from, $waiting_time)
    {
        try{
            return abs((int) ((strtotime($to) - strtotime($from)) / 60) / $waiting_time);
        }catch(\Exception $e){
            return 0;
        }
    }
    
    public static function validateExtension($extension) {
        $exts = [
            "jpeg",
            "png",
            "jpg",
            "gif",
            "bmp",
        ];

        if (in_array($exts, $extension))
            return true;

        return false;
    }

    public static function randColor() {
        $colors = [
            "w3-red",
            "w3-pink",
            "w3-green",
            "w3-blue",
            "w3-purple",
            "w3-deep-purple",
            "w3-indigo", 
            "w3-aqua",
            "w3-teal",
            "w3-lime", 
            "w3-orange",
            "w3-blue-gray",
            "w3-brown",
        ];

        return $colors[array_rand($colors)];
    }

    //////// function for saving images and icons to public path /////////
    ///// run like this  save_file($request->image, '/path_name');
    public static function uploadImg($file, $folder = '/') {
        $extension = $file->getClientOriginalExtension(); // getting image extension
//
//        if (!self::validateExtension($extension))
//            return Message::$REQUIRED_IMAGE;

        $filename = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
        $dest = public_path('/image' . $folder);
        $file->move($dest, $filename);
        // return 'public/uploads' . $folder . '/' . $fileName;

        return $filename;
    }

    public static function uploadFile($file, $folder = '/') {
        $extension = $file->getClientOriginalExtension(); // getting image extension
//
//        if (!self::validateExtension($extension))
//            return Message::$REQUIRED_IMAGE;

        $filename = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
        $dest = public_path('/file' . $folder);
        $file->move($dest, $filename);
        // return 'public/uploads' . $folder . '/' . $fileName;

        return $filename;
    }

    public static function sendMail($to, $message, $subject) {
        Mail::raw($message, function ($message) use ($subject, $to) {
            $message->to($to)->subject($subject);
        });
    }
    
    /**
     * prepare objects based on getJson function in model
     * 
     * @param type $data
     * @return type
     */
    public static function jsonFilter($data) {
        $filterArray = [];
        foreach($data as $item)
            $filterArray[] = $item->getJson();
        
        return $filterArray;
    }
    
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    public static function latLangDistance($lat1, $lon1, $lat2, $lon2, $unit = "K") {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

}
