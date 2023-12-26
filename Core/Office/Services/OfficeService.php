<?php

namespace Core\Office\Services;

use App\Models\Office;
use App\Models\OfficeImages;
use App\ServiceResponse;
use Core\Image\Services\ImageService;
use Core\Metadata\Services\MetadataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficeService
{
    static function store(Request $request){
        DB::beginTransaction();
        try {
            $office = new  Office();
            $office->name = $request->name;
            $office->cif = $request->cif;
            $office->address = $request->address;
            $office->map = $request->map;
            $office->email = $request->email;
            $office->phone = $request->phone;
            $office->feature = $request->feature;
            $office->slug = $request->slug;
            $office->save();

            $images = ImageService::store($request, 'office', $office->id);
            if($images['success']) {
                foreach ($images['data'] as $path) {
                    $image = new OfficeImages();
                    $image->path = $path;
                    $image->office_id = $office->id;
                    $image->save();
                }
            }
            $metadataId = MetadataService::store($request);
            if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            $office->metadata_id = $metadataId;
            $office->save();
            DB::commit();

            $response = Office::with('officeImages', 'metadata', 'partners')->find($office->id);
            return ServiceResponse::created(__('messages.office_create_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function update(Request $request, $id){
        $data = $request->all();
        $office =  Office::find($id);
        if(!$office) return ServiceResponse::not_found(__('messages.office_not_found'));

        DB::beginTransaction();
        try {
            $office->update($data);
            
            if(count($request->file())){
                $old_images =  OfficeImages::where('office_id', $id)->delete();
                $delete_old_images = ImageService::delete('office', $office->id);
                if(!$delete_old_images) return ServiceResponse::badRequest(__('messages.image_update_badrequest'));
    
                $images = ImageService::store($request, 'office', $office->id);
                if($images['success']) {
                    foreach ($images['data'] as $path) {
                        $image = new OfficeImages();
                        $image->path = $path;
                        $image->office_id = $office->id;
                        $image->save();
                    }
                }
            }
            $metadataId = MetadataService::update($request, $office->metadata_id);
            if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            DB::commit();
            $response = office::with('officeImages', 'metadata', 'partners')->find($id);
            return ServiceResponse::created(__('messages.office_update_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
