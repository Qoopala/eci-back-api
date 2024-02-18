<?php

namespace Core\Image\Services;

use App\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageService
{
    static function store(Request $request, $type, $id){
        try {
            // dd($request->file());
            $folderPath = "{$type}" . DIRECTORY_SEPARATOR . "{$id}";
            $arrayPath = [];
    
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0755, true);
            }
            $images = $request->file();
            if($type === 'metadata'){
                $image = $images['meta_image'];
                $imageName = $image->getClientOriginalName();
                $image->move(public_path($folderPath), $imageName);
                $arrayPath[] = "/{$type}/{$id}/{$imageName}";
            }else{
                foreach ($images as $key => $image) {
                    if($key !== 'meta_image'){
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

    static function delete($path){
        try {
            $file_path = public_path($path);

            if (File::exists($file_path)) {
                File::delete($file_path);
                return true; 
            }
            return false; 
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }
}
