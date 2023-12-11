<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use Core\Auth\Services\AuthService;
use Core\Auth\Validations\AuthValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $validate = AuthValidation ::validateRegister($data);
        if($validate) return ApiResponse::badRequest($validate);
        try {
            $response = AuthService::register($request);
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

    public function login(Request $request)
    {
        $data = $request->all();
        $validate = AuthValidation::validateLogin($data);
        if($validate) return ApiResponse::badRequest($validate);
        try {
            $response = AuthService::login($request);
            if($response['success']) return ApiResponse::ok($response['message'], $response['data']);
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

    public function logout(){
        try {
            $response = AuthService::logout();
            if($response['success']) return ApiResponse::ok($response['message']);
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

    public function testUserLogged(){
        if(Auth::check()) {
            $user = Auth::user();
            return $user;
        } else {
            return 'user not found';
        }
    }
}
