<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Blog\BlogController;
use App\Http\Controllers\Api\Property\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('unauthorized', function () {
    return response(['message' => 'Unauthenticated.'], 401);
})->name('api.unauthorized');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('status', function (){ return response('service in operation'); });

//AUTH
Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('test-token', function (){ return response('service in operation with token'); });
    Route::get('test-user', [AuthController::class, 'testUserLogged']);
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('edit-property/{id}', [PropertyController::class, 'update']);
    Route::resource('property', PropertyController::class)->except([
        'index', 'show'
    ]);

    Route::post('edit-blog/{id}', [BlogController::class, 'update']);
    Route::resource('blog', BlogController::class)->except([
        'index', 'show'
    ]);
});

Route::resource('property', PropertyController::class)->only([
    'index', 'show'
]);

Route::resource('blog', BlogController::class)->only([
    'index', 'show'
]);