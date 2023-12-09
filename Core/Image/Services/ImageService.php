<?php

namespace Core\Image\Services;

use App\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    static function store(Request $request, $type, $id){
        try {
            $folderPath = "{$type}" . DIRECTORY_SEPARATOR . "{$id}";
            $arrayPath = [];
            Storage::makeDirectory($folderPath);    
            $images = $request->file();
    
            foreach ($images as $image) {
                $imageName = $image->getClientOriginalName();
                $image->storeAs($folderPath, $imageName);
                $arrayPath[] = storage_path("app/{$folderPath}/{$imageName}");
            }

            return ServiceResponse::ok('images created', $arrayPath);
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }
}
