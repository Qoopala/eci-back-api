<?php

namespace Core\Auth\Services;

use App\ApiResponse;
use App\Models\User;
use App\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    static function register(Request $request){
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            DB::commit();
            return ServiceResponse::created(__('messages.user_create_ok'), $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError() ;
        }
    }

    static function login(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();
            if (!empty($user)) {
                if (Hash::check($request->password, $user->password)) {
                    $auth_token = $user->createToken("auth_token")->plainTextToken;
                    $user->auth_token = $auth_token;

                    return ServiceResponse::ok(__('messages.user_logged_ok'), $user);
                } else {
                    return ServiceResponse::badRequest(__('messages.user_login_badrequest'));
                }
            } else {
                return ServiceResponse::not_found(__('messages.user_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError() ;
        }
    }

    static function logout()
    {
        try {
            $user = auth()->user();
            if ($user) {
                $user->tokens()->delete();
            }
            return ServiceResponse::ok(__('messages.user_logout'));
        } catch (\Exception $th) {
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError() ;
        }
    }

}
