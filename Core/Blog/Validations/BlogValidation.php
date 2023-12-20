<?php

namespace Core\Blog\Validations;

use Illuminate\Support\Facades\Validator;

class BlogValidation
{
    static function validateStore($data){

        $rules = [
            'title'=>'required|string',
            'down'=>'required|string',
            'author'=>'required|string',
            'body'=>'required|string',
            'date'=>'date',
            'category_id'=>'required',
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        else return false;
    }
}