<?php

namespace Core\Metadata\Services;

use App\ApiResponse;
use App\Models\Metadata;
use App\ServiceResponse;
use Core\Image\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetadataService
{
    static function store(Request $request){
        DB::beginTransaction();
        try {
            
            $metadata = new Metadata();
            $metadata->title = $request->metaTitle;
            $metadata->description = $request->metaDescription;
            $metadata->status_index = $request->metaStatusindex;
            if(isset($request->metaSection)) $metadata->section = $request->metaSection;
            $metadata->save();
            
            $images = ImageService::store($request, 'metadata', $metadata->id);
            if($images['success']) {
                foreach ($images['data'] as $path_image) {
                    $metadata->path_image = $path_image;
                }
            }
            
            $metadata->save();
            DB::commit();
            return $metadata->id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    static function update(Request $request, $id){
        $metadata = Metadata::find($id);
        if(!$metadata) return ServiceResponse::not_found(__('messages.metadata_not_found'));

        DB::beginTransaction();
        try {
            $metadata->title = $request->metaTitle;
            $metadata->description = $request->metaDescription;
            $metadata->status_index = $request->metaStatusindex;
            if(isset($request->metaSection)) $metadata->section = $request->metaSection;
            
            if(count($request->file())){
                $delete_old_images = ImageService::delete('metadata', $metadata->id);
                if(!$delete_old_images) return ServiceResponse::badRequest(__('messages.image_update_badrequest'));
    
                $images = ImageService::store($request, 'metadata', $metadata->id);
                if($images['success']) {
                    foreach ($images['data'] as $path_image) {
                    $metadata->path_image = $path_image;
                    }
                }
            }
            $metadata->save();
            DB::commit();
            return $metadata->id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
