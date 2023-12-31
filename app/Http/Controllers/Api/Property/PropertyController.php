<?php

namespace App\Http\Controllers\Api\Property;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Locality;
use App\Models\Property;
use Core\Image\Validations\ImageValidation;
use Core\Property\Services\PropertyService;
use Core\Property\Validations\PropertyValidation;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        try {
            $query = Property::query();
            $properties = $query->with('office', 'locality', 'images', 'metadata', 'sublocality')->paginate($perPage);
            if($properties) return ApiResponse::ok(__('messages.property_get_ok'), $properties);
            else return ApiResponse::not_found(__('messages.property_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = PropertyValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = PropertyService::store($request);
         
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

    public function show($id)
    {
        try {
            $property = Property::with('office', 'locality', 'images', 'metadata', 'sublocality')->find($id);
            if($property) return ApiResponse::ok(__('messages.property_get_ok'), $property);
            else return ApiResponse::not_found(__('messages.property_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validate = PropertyValidation::validateStore($data, $id);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = PropertyService::update($request, $id);
         
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
            $property = Property::find($id);
            if ($property) {
                $property->delete();
                return ApiResponse::ok(__('messages.property_delete_ok'));
            } else {
                return ApiResponse::not_found(__('messages.property_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function getBySlug($slug)
    {
        try {
            $property = Property::with('office', 'locality', 'images', 'metadata', 'sublocality')->where('slug', $slug)->first();
            if($property) return ApiResponse::ok(__('messages.property_get_ok'), $property);
            else return ApiResponse::not_found(__('messages.property_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function getLocalities()
    {
        try {
            $locality = Locality::with('sublocalities')->get();
            if($locality) return ApiResponse::ok(__('messages.locality_get_ok'), $locality);
            else return ApiResponse::not_found(__('messages.locality_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

}
