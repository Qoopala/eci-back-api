<?php

namespace Core\Auth\Validations;

use Illuminate\Support\Facades\Validator;

class AuthValidation
{
    static function validateRegister($data){
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        else return false;
    }

    static function validateLogin($data){
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        else return false;
    }
}
