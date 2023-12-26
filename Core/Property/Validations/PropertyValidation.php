<?php

namespace Core\Property\Validations;

use App\Models\Property;
use Illuminate\Support\Facades\Validator;

class PropertyValidation
{
    static function validateStore($data, $id = null){

        $rules = [
            'title'=>'required|string',
            'address'=>'nullable|string',
            'reference'=>'required|string',
            'price'=>'required|numeric',
            'information'=>'required|string',
            'number_room'=>'required|numeric',
            'number_bath'=>'required|numeric',
            'square_meter'=>'required|numeric',
            'energy_certification'=>'nullable|string',
            'map'=>'required|string',
            'status'=>'required',
            'office_id'=>'required',
            'locality_id'=>'required',
            'slug' => 'string|nullable'
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        if(!$id){
            $unique_slug = Property::where('slug', $data['slug'])->first();
            if($unique_slug) return 'slug already exist';
        }
        else return false;
    }
}
