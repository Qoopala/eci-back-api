<?php

namespace App\Http\Controllers\Api\Metadata;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Metadata;
use Core\Image\Validations\ImageValidation;
use Core\Metadata\Services\MetadataService;
use Core\Metadata\Validations\MetadataValidation;
use Illuminate\Http\Request;

class MetadataController extends Controller
{
    public function index(Request $request)
    {
        try {
            $section = $request->input('section');
    
            $metadataQuery = Metadata::when($section, function ($query) use ($section) {
                return $query->where('section', $section);
            });
            $metadata = $metadataQuery->get();
    
            if ($metadata->isNotEmpty()) {
                return ApiResponse::ok(__('messages.metadata_get_ok'), $metadata);
            } else {
                return ApiResponse::not_found(__('messages.metadata_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $validate = MetadataValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validateImage) return ApiResponse::badRequest($validateImage);

        try {
            $metadataId = MetadataService::store($request);
            $response = Metadata::find($metadataId);
            if($metadataId) return ApiResponse::created(__('messages.metadata_create_ok'), $response);
            else {
                switch ($response['code']) {
                    case 500:
                        return ApiResponse::serverError($response['message']); break;
                }
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function show(string $id)
    {
        try {
            $metadata = Metadata::find($id);
            if($metadata) return ApiResponse::ok(__('messages.metadata_get_ok'), $metadata);
            else return ApiResponse::not_found(__('messages.metadata_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();

        $validateImage = ImageValidation::validateImage($request);
        if($validateImage) return ApiResponse::badRequest($validateImage);

        try {
            $metadataId = MetadataService::update($request, $id);
            $response = Metadata::find($metadataId);
            if($metadataId) return ApiResponse::created(__('messages.metadata_update_ok'), $response);
            else {
                switch ($response['code']) {
                    case 400:
                        return ApiResponse::badRequest($response['message']); break;
                    case 404:
                        return ApiResponse::not_found($response['message']); break;
                    case 500:
                        return ApiResponse::serverError($response['message']); break;
                }
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }
}
