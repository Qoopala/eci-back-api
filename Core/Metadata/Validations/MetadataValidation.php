<?php

namespace Core\Metadata\Validations;

use Illuminate\Support\Facades\Validator;

class MetadataValidation
{
    static function validateStore($data){

        $rules = [
            'metaTitle'=>'required|string',
            'metaDescription'=>'nullable|string',
            'metaStatusindex'=>'nullable|string',
            'metaSection'=>'nullable|string',
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        else return false;
    }
}
