<?php

/*
 * my functions
 * 
 */

use App\Translation;



if (!function_exists("__")) {

    function __($key) {
        $word = $key;
        // prepare key
        $key = strtolower($key);
        $key = str_replace(" ", "_", $key);

        // my code for translation
        try {
            $translation = Translation::where('key', $key)->first();

            if ($translation) {
                $translate = (App\Lang::getLang() == 'ar') ? $translation->word_ar : $translation->word_en;

                if ($translate)
                    return $translate;
            } else {
                Translation::create([
                    "key" => $key,
                    "word_en" => $word
                ]);
            }
        } catch (\Exception $exc) {
            //
        }



        return $word;
    }

}


