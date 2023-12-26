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
            'number_room' => 'required|numeric',
            'hall_area' => 'nullable|numeric', // Agregado
            'area' => 'nullable|numeric', // Agregado
            'number_bath' => 'required|numeric',
            'terrace_area' => 'nullable|numeric', // Agregado
            'balcony_area' => 'nullable|numeric', // Agregado
            'map' => 'required|string',
            'status' => 'required',
            'office_id' => 'required|integer',
            'locality_id' => 'required|integer',
            'heating' => 'nullable|boolean', // Agregado
            'airconditioning' => 'nullable|boolean', // Agregado
            'year_construction' => 'nullable|integer', // Agregado
            'floor_type' => 'nullable|string', // Agregado
            'gas' => 'nullable|boolean', // Agregado
            'energy_certification' => 'nullable|string',
            'elevator' => 'nullable|boolean', // Agregado
            'shared_terrace' => 'nullable|boolean', // Agregado
            'parking' => 'nullable|boolean', // Agregado
            'storage_room' => 'nullable|boolean', // Agregado
            'pool' => 'nullable|boolean', // Agregado
            'garden' => 'nullable|boolean', // Agregado
            'public_transport' => 'nullable|boolean', // Agregado
            'shopping' => 'nullable|boolean', // Agregado
            'market' => 'nullable|boolean', // Agregado
            'education_center' => 'nullable|boolean', // Agregado
            'health_center' => 'nullable|boolean', // Agregado
            'recreation_area' => 'nullable|boolean', // Agregado
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
