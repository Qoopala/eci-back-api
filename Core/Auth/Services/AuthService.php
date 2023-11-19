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
}
