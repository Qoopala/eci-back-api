<?php

namespace Core\Property\Validations;

use App\Models\Property;
use Illuminate\Support\Facades\Validator;

class PropertyValidation
{
    static function validateStore($data, $id = null){

        $rules = [
            'title' => 'required|string',
            'address' => 'nullable|string',
            'reference' => 'required|string',
            'price' => 'required|numeric',
            'information' => 'required|string',
            'type' => 'nullable|string',
            'number_room' => 'required|numeric',
            'hall_area' => 'nullable|numeric', 
            'area' => 'nullable|numeric', 
            'number_bath' => 'required|numeric',
            'terrace_area' => 'nullable|numeric', 
            'balcony_area' => 'nullable|numeric', 
            'map' => 'required|string',
            'status' => 'required|string',
            'office_id' => 'required|integer',
            'locality_id' => 'required|integer',
            'sublocality_id' => 'required|integer',
            'heating' => 'nullable|boolean', 
            'airconditioning' => 'nullable|boolean', 
            'year_construction' => 'nullable|integer', 
            'floor_type' => 'nullable|string', 
            'gas' => 'nullable|boolean', 
            'energy_certification' => 'nullable|string',
            'energy_consumption' => 'nullable|string',
            'elevator' => 'nullable|boolean', 
            'shared_terrace' => 'nullable|boolean', 
            'parking' => 'nullable|boolean', 
            'storage_room' => 'nullable|boolean', 
            'pool' => 'nullable|boolean', 
            'garden' => 'nullable|boolean', 
            'public_transport' => 'nullable|boolean', 
            'shopping' => 'nullable|boolean', 
            'market' => 'nullable|boolean', 
            'education_center' => 'nullable|boolean', 
            'health_center' => 'nullable|boolean', 
            'recreation_area' => 'nullable|boolean', 
            'slug' => 'nullable|string',
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
