<?php

namespace App\Http\Controllers\Api;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use Core\Image\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GeneralDataController extends Controller
{
    public function index()
    {
        $rutaArchivo = database_path('generalData.json');
        
        if (File::exists($rutaArchivo)) {
            $contenidoJSON = file_get_contents($rutaArchivo);
            $datosJSON = json_decode($contenidoJSON, true);

            return ApiResponse::ok('datos generales obtenidos correctamente', $datosJSON);
        } else {
           return ApiResponse::badRequest('data not found');
        }
    }
    
    public function store(Request $request)
    {
        $rutaArchivo = database_path('generalData.json');

        if (File::exists($rutaArchivo)) {
            $contenidoJSON = file_get_contents($rutaArchivo);
            $datosJSON = json_decode($contenidoJSON, true);

            $images = ImageService::store($request, 'general', 1);
            if($images['success']) {
                foreach ($images['data'] as $path_image) {
                    $datosJSON['image'] = $path_image;
                }
            }
            $datosJSON['sitename'] = $request->input('sitename');
            $datosJSON['description'] = $request->input('description');
            $datosJSON['phone'] = $request->input('phone');
            $datosJSON['email'] = $request->input('email');
            $datosJSON['instagram'] = $request->input('instagram');
            $datosJSON['linkedin'] = $request->input('linkedin');
            $datosJSON['facebook'] = $request->input('facebook');

            file_put_contents($rutaArchivo, json_encode($datosJSON, JSON_PRETTY_PRINT));

            return ApiResponse::ok('data actualizada correctamente', $datosJSON);
        } else {
           return ApiResponse::badRequest('data not found');
        }
    }
}
