<?php

namespace Core\Image\Services;

use App\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageService
{
    static function store(Request $request, $type, $id){
        try {
            $folderPath = "{$type}" . DIRECTORY_SEPARATOR . "{$id}";
            $arrayPath = [];
    
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0755, true);
            }
            $images = $request->file();
            if($type === 'metadata'){
                $image = $images['image_meta'];
                $imageName = $image->getClientOriginalName();
                $image->move(public_path($folderPath), $imageName);
                $arrayPath[] = "/{$type}/{$id}/{$imageName}";
            }else{
                foreach ($images as $key => $image) {
                    if($key !== 'image_meta'){
                        $imageName = $image->getClientOriginalName();
                        $image->move(public_path($folderPath), $imageName);
                        $arrayPath[] = "/{$type}/{$id}/{$imageName}";
                    }
                }
            }
            return ServiceResponse::ok('images created', $arrayPath);
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function delete($type, $id){
        try {
            $directory = public_path("{$type}/{$id}");
    
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
