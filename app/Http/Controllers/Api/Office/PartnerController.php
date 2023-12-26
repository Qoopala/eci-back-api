<?php

namespace App\Http\Controllers\Api\Office;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Core\Image\Validations\ImageValidation;
use Core\Office\Services\PartnerService;
use Core\Office\Validations\PartnerValidation;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        try {
            $partner = Partner::with('office')->get();
            if($partner) return ApiResponse::ok(__('messages.partner_get_ok'), $partner);
            else return ApiResponse::not_found(__('messages.partner_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = PartnerValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = PartnerService::store($request);
         
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
            $partner = Partner::with('office')->find($id);
            if($partner) return ApiResponse::ok(__('messages.partner_get_ok'), $partner);
            else return ApiResponse::not_found(__('messages.partner_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validate = PartnerValidation::validateStore($data, $id);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = PartnerService::update($request, $id);
         
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
            $partner =  Partner::find($id);
            if ($partner) {
                $partner->delete();
                return ApiResponse::ok(__('messages.partner_delete_ok'));
            } else {
                return ApiResponse::not_found(__('messages.partner_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

}
