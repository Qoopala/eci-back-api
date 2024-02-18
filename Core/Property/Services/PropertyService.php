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
            $requestData = $request->all();
            self::checkRequest($requestData);
            // dd($requestData);
            $property = new Property();

            $attributes = [
                'title', 'address', 'price', 'information', 'type', 'number_room', 'hall_area', 'area',
                'number_bath', 'terrace','terrace_area', 'balcony','balcony_area', 'map', 'status', 'office_id', 'locality_id',
                'sublocality_id', 'heating', 'airconditioning', 'year_construction', 'floor_type', 'gas',
                'energy_certification', 'energy_consumption', 'elevator', 'shared_terrace', 'parking',
                'storage_room', 'pool', 'garden', 'public_transport', 'shopping', 'market', 'education_center',
                'health_center', 'recreation_area', 'slug'
            ];

            foreach ($attributes as $attribute) {
                $property->$attribute = isset($requestData[$attribute]) ? $requestData[$attribute] : null;
            }
            
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
            if($requestData["metaTitle"]){
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
        // dd($data);
        self::checkRequest($data);
        $property = Property::find($id);
        if(!$property) return ServiceResponse::not_found(__('messages.property_not_found'));
        
        DB::beginTransaction();
        try {
            $property->update($data);
            
            $paths_to_delete = self::selectDeleteImages($data['persist_images'], $id);
            if(count($paths_to_delete)){
                foreach ($paths_to_delete as $path) {
                    Image::where('path', $path)->delete();
                    $delete_image = ImageService::delete($path);
                    if(!$delete_image) return ServiceResponse::badRequest(__('messages.image_update_badrequest'));
                }
            }
            if(count($request->file())){
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
            if(isset($data["metaTitle"]) || isset($data["metaDescription"])){
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

    static function checkRequest(&$data){
        foreach ($data as &$value) {
            if ($value === "null") $value = null;
            if ($value === "false") $value = false;
            if ($value === "true") $value = true;
            if ($value === "undefined") $value = null;
            if ($value === "NaN") $value = null;
        }
    }

    static function selectDeleteImages($array_request, $id){

        $array_db = Image::select('path')->where('property_id', $id)->pluck('path')->toArray();
        if(!$array_request) $paths_to_delete = $array_db;
        else $paths_to_delete = array_diff($array_db, $array_request);

        return $paths_to_delete;
    }

}
