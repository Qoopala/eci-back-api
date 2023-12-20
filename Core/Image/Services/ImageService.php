<?php

namespace Core\Image\Services;

use App\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    static function store(Request $request, $type, $id){
        try {
            $folderPath = "public". DIRECTORY_SEPARATOR ."{$type}" . DIRECTORY_SEPARATOR . "{$id}";
            $arrayPath = [];
            Storage::makeDirectory($folderPath);    
            $images = $request->file();
    
            foreach ($images as $image) {
                $imageName = $image->getClientOriginalName();
                $image->storeAs($folderPath, $imageName);
                $arrayPath[] = "storage/{$type}/{$id}/{$imageName}";
            }

            return ServiceResponse::ok('images created', $arrayPath);
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function delete($type, $id){
        try {
            $directory = storage_path("app/{$type}/{$id}");
    
            if (File::exists($directory)) {
                File::deleteDirectory($directory);
                return true; 
            }
            return false; 
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }
}
