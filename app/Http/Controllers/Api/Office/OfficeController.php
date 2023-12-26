<?php

namespace App\Http\Controllers\Api\Office;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Office;
use Core\Image\Validations\ImageValidation;
use Core\Office\Services\OfficeService;
use Core\Office\Validations\OfficeValidation;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        try {
            $office = Office::with('officeImages', 'metadata', 'partners')->get();
            if($office) return ApiResponse::ok(__('messages.office_get_ok'), $office);
            else return ApiResponse::not_found(__('messages.office_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = OfficeValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = OfficeService::store($request);
         
            if($response['success']) return ApiResponse::created($response['message'], $response['data']);
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
            $office = Office::with('officeImages', 'metadata', 'partners')->find($id);
            if($office) return ApiResponse::ok(__('messages.office_get_ok'), $office);
            else return ApiResponse::not_found(__('messages.office_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validate = OfficeValidation::validateStore($data, $id);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = OfficeService::update($request, $id);
         
            if($response['success']) return ApiResponse::created($response['message'], $response['data']);
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

    public function destroy(string $id)
    {
        try {
            $office =  Office::find($id);
            if ($office) {
                $office->delete();
                return ApiResponse::ok(__('messages.office_delete_ok'));
            } else {
                return ApiResponse::not_found(__('messages.office_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function getBySlug($slug)
    {
        try {
            $office = Office::with('officeImages', 'metadata', 'partners')->where('slug', $slug)->first();
            if($office) return ApiResponse::ok(__('messages.office_get_ok'), $office);
            else return ApiResponse::not_found(__('messages.office_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }
}
