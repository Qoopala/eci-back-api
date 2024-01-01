<?php

namespace Core\Review\Validations;

use Illuminate\Support\Facades\Validator;

class ReviewValidation
{
    static function validateStore($data){

        $rules = [
            'name'=>'required|string',
            'quote'=>'required|string',
            'office_id'=>'required',
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        
        else return false;
    }
}