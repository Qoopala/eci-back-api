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
            $user = AuthService::login($request);
            if($user)  return ApiResponse::ok(__('messages.user_logged_ok'), $user);
            else return ApiResponse::badRequest(__('messages.user_login_error'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function logout(){
        try {
            $logout = AuthService::logout();
            if($logout) return ApiResponse::ok(__('messages.user_logout'));
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
