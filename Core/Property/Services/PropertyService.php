<?php

namespace Core\Property\Services;

use App\Models\Image;
use App\ServiceResponse;
use App\Models\Property;
use App\Models\Sublocality;
use Core\Image\Services\ImageService;
use Core\Metadata\Services\MetadataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyService
{
    static function index(Request $request){
        try {
            $perPage = $request->query('per_page', 20);
            $query = Property::query();
            $numberBath = $request->query('bath');
            if ($numberBath) {
                $query->where('number_bath', $numberBath);
            }
    
            $numberRoom = $request->query('room');
            if ($numberRoom) {
                $query->where('number_room', $numberRoom);
            }
    
            $sublocalities = $request->query('sublocality');
            if ($sublocalities) {
                $sublocalities = explode(',', $sublocalities);
                $sublocalityIds = Sublocality::whereIn('name', $sublocalities)->pluck('id')->toArray();
    
                if (!empty($sublocalityIds)) {
                    $query->whereIn('sublocality_id', $sublocalityIds);
                } else {
                    return ServiceResponse::not_found(__('messages.sublocalities_not_found'));
                }
            }

            $priceFrom = $request->query('price_from');
            $priceTo = $request->query('price_to');
            if ($priceFrom !== null && $priceTo !== null) {
                $query->whereBetween('price', [$priceFrom, $priceTo]);
            } elseif ($priceFrom !== null) {
                $query->where('price', '>=', $priceFrom);
            } elseif ($priceTo !== null) {
                $query->where('price', '<=', $priceTo);
            }
    
            $properties = $query->with('office', 'locality', 'images', 'metadata', 'sublocality')->paginate($perPage);
            return ServiceResponse::ok(__('messages.property_get_ok'), $properties);

        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }


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
            $property->hall_area = $request->hall_area; 
            $property->area = $request->area; 
            $property->number_bath = $request->number_bath;
            $property->terrace_area = $request->terrace_area; 
            $property->balcony_area = $request->balcony_area; 
            $property->map = $request->map;
            $property->status = $request->status;
            $property->office_id = $request->office_id;
            $property->locality_id = $request->locality_id;
            $property->sublocality_id = $request->sublocality_id;
            $property->heating = $request->heating; 
            $property->airconditioning = $request->airconditioning; 
            $property->year_construction = $request->year_construction; 
            $property->floor_type = $request->floor_type; 
            $property->gas = $request->gas; 
            $property->energy_certification = $request->energy_certification;
            $property->energy_consumption = $request->energy_consumption;
            $property->elevator = $request->elevator; 
            $property->shared_terrace = $request->shared_terrace; 
            $property->parking = $request->parking; 
            $property->storage_room = $request->storage_room; 
            $property->pool = $request->pool; 
            $property->garden = $request->garden; 
            $property->public_transport = $request->public_transport; 
            $property->shopping = $request->shopping; 
            $property->market = $request->market; 
            $property->education_center = $request->education_center; 
            $property->health_center = $request->health_center; 
            $property->recreation_area = $request->recreation_area; 
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
            if(isset($request->metaTitle)){
                $metadataId = MetadataService::store($request);
                if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
                $property->metadata_id = $metadataId;
            }
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
            if(isset($request->metaTitle)){
                $metadataId = MetadataService::update($request, $property->metadata_id);
                if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            }
            DB::commit();
            $response = Property::with('office', 'locality', 'images', 'metadata', 'sublocality')->find($id);
            return ServiceResponse::created(__('messages.property_update_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
