<?php

namespace Core\Office\Validations;

use App\Models\Office;
use Illuminate\Support\Facades\Validator;

class OfficeValidation
{
    static function validateStore($data, $id = null){

        $rules = [
            'name'=>'required|string',
            'cif'=>'required|string',
            'address'=>'required|string',
            'map'=>'required|string',
            'email'=>'required|string',
            'phone' => 'string|nullable',
            'feature' => 'string|nullable',
            'slug' => 'string|nullable'

        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        if(!$id){
            $unique_slug = Office::where('slug', $data['slug'])->first();
            if($unique_slug) return 'slug already exist';
        }
        
        else return false;
    }
}