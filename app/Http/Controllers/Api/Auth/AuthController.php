<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Core\Auth\Services\AuthService;
use Core\Auth\Validations\AuthValidation;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Notifications\CustomResetPasswordNotification;

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

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->notify(new CustomResetPasswordNotification($token));
            }
        );
        return $status === Password::RESET_LINK_SENT
                    ? ApiResponse::ok('Email enviado',  __($status))
                    : ApiResponse::badRequest(__($status));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? ApiResponse::ok('Contraseña actuaizada con exito',  __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    protected function broker()
    {
        return Password::broker();
    }
}
