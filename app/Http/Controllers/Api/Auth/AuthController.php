<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use Core\Auth\Services\AuthService;
use Core\Auth\Validations\AuthValidation;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $validate = AuthValidation ::validateRegister($data);
        if($validate) return ApiResponse::badRequest($validate);

        try {
            $user = AuthService::register($request);
            return ApiResponse::created(__('messages.user_create_ok'), $user);
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $validate = AuthValidation::validateLogin($data);
        if($validate) return ApiResponse::badRequest($validate);
        try {

        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }
}
