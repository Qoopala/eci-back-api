<?php

namespace Core\Image\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageValidation
{
    static function validateImage(Request $request){
        if(!count($request->file())) return false;
        $data = $request->all();
         $rules = [];
         for ($i = 1; $i <= 10; $i++) {
             $rules["image_{$i}"] = "image|mimes:jpeg,png,jpg,gif,webp|max:2048";
         }
 
         $validator = Validator::make($data, $rules);
         if($validator->fails()) return $validator->errors();
         else return false;
    }
}
