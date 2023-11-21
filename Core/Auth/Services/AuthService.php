<?php

namespace Core\Auth\Services;

use App\ApiResponse;
use App\Models\User;
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
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::serverError($th->getMessage());
        }
    }

    static function login(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();
            if (!empty($user)) {
                if (Hash::check($request->password, $user->password)) {
                    $auth_token = $user->createToken("auth_token")->plainTextToken;
                    $user->auth_token = $auth_token;

                    return $user;
                } else {
                    return ApiResponse::badRequest(__('messages.user_login_badrequest'));
                }
            } else {
                return ApiResponse::badRequest(__('messages.user_not_found'));
            }
        } catch (\Throwable $th) {
            return ApiResponse::serverError($th->getMessage());
        }
    }

    static function logout()
    {
        try {
            $user = auth()->user();
            if ($user) {
                $user->tokens()->delete();
            }
            return true;
        } catch (\Exception $th) {
            ApiResponse::serverError($th->getMessage());
        }
    }

}
