<?php

namespace Core\Office\Validations;

use Illuminate\Support\Facades\Validator;

class PartnerValidation
{
    static function validateStore($data){

        $rules = [
            'name'=>'required|string',
            'role'=>'required|string',
            'office_id'=>'required|integer'
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        
        else return false;
    }
}