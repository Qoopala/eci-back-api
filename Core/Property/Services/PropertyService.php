<?php

namespace Core\Property\Services;

use App\Models\Feature;
use App\Models\Image;
use App\ServiceResponse;
use App\Models\Property;
use Core\Image\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyService
{
    static function store(Request $request){

        DB::beginTransaction();
        try {
            $property = new Property();
            $property->title = $request->title;
            $property->address = $request->address;
            $property->reference = $request->reference;
            $property->price = $request->price;
            $property->information = $request->information;
            $property->number_room = $request->number_room;
            $property->number_bath = $request->number_bath;
            $property->square_meter = $request->square_meter;
            $property->energy_certification = $request->energy_certification;
            $property->map = $request->map;
            $property->status = $request->status;
            $property->office_id = $request->office_id;
            $property->locality_id = $request->locality_id;
            $property->save();

            $images = ImageService::store($request, 'property', $property->id);
            if($images['success']) {
                foreach ($images['data'] as $path) {
                    $image = new Image();
                    $image->path = $path;
                    $image->property_id = $property->id;
                    $image->save();
                }
            }
            
            $arrayFeatures = json_decode($request->features);
            foreach ($arrayFeatures as $value) {
                $feature = new Feature();
                $feature->name = $value;
                $feature->property_id = $property->id;
                $feature->save();
            }

            DB::commit();
            return ServiceResponse::created(__('messages.property_create_ok'), $property);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
