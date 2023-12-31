<?php

namespace Core\Property\Services;

use App\Models\Feature;
use App\Models\Image;
use App\ServiceResponse;
use App\Models\Property;
use Core\Image\Services\ImageService;
use Core\Metadata\Services\MetadataService;
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
            $property->hall_area = $request->hall_area; // Agregado
            $property->area = $request->area; // Agregado
            $property->number_bath = $request->number_bath;
            $property->terrace_area = $request->terrace_area; // Agregado
            $property->balcony_area = $request->balcony_area; // Agregado
            $property->map = $request->map;
            $property->status = $request->status;
            $property->office_id = $request->office_id;
            $property->locality_id = $request->locality_id;
            $property->sublocality_id = $request->sublocality_id;
            $property->heating = $request->heating; // Agregado
            $property->airconditioning = $request->airconditioning; // Agregado
            $property->year_construction = $request->year_construction; // Agregado
            $property->floor_type = $request->floor_type; // Agregado
            $property->gas = $request->gas; // Agregado
            $property->energy_certification = $request->energy_certification;
            $property->elevator = $request->elevator; // Agregado
            $property->shared_terrace = $request->shared_terrace; // Agregado
            $property->parking = $request->parking; // Agregado
            $property->storage_room = $request->storage_room; // Agregado
            $property->pool = $request->pool; // Agregado
            $property->garden = $request->garden; // Agregado
            $property->public_transport = $request->public_transport; // Agregado
            $property->shopping = $request->shopping; // Agregado
            $property->market = $request->market; // Agregado
            $property->education_center = $request->education_center; // Agregado
            $property->health_center = $request->health_center; // Agregado
            $property->recreation_area = $request->recreation_area; // Agregado
            $property->slug = $request->slug;
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
        
            $metadataId = MetadataService::store($request);
            if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            $property->metadata_id = $metadataId;
            $property->save();
            DB::commit();
            $response = Property::with('office', 'locality', 'images', 'metadata', 'sublocality')->find($property->id);
            return ServiceResponse::created(__('messages.property_create_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function update(Request $request, $id){
        $data = $request->all();
        $property = Property::find($id);
        if(!$property) return ServiceResponse::not_found(__('messages.property_not_found'));
        
        DB::beginTransaction();
        try {
            $property->update($data);
            
            if(count($request->file())){
                $old_images = Image::where('property_id', $id)->delete();
                $delete_old_images = ImageService::delete('property', $property->id);
                if(!$delete_old_images) return ServiceResponse::badRequest(__('messages.image_update_badrequest'));
    
                $images = ImageService::store($request, 'property', $property->id);
                if($images['success']) {
                    foreach ($images['data'] as $path) {
                        $image = new Image();
                        $image->path = $path;
                        $image->property_id = $property->id;
                        $image->save();
                    }
                }
            }

            $metadataId = MetadataService::update($request, $property->metadata_id);
            if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            DB::commit();
            $response = Property::with('office', 'locality', 'images', 'metadata', 'sublocality')->find($id);
            return ServiceResponse::created(__('messages.property_update_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
