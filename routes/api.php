<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Blog\BlogController;
use App\Http\Controllers\Api\GeneralDataController;
use App\Http\Controllers\Api\Metadata\MetadataController;
use App\Http\Controllers\Api\Office\OfficeController;
use App\Http\Controllers\Api\Office\PartnerController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Review\ReviewController;
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
Route::post('password/email', [AuthController::class , 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [AuthController::class , 'reset'])->name('password.reset');;


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('test-token', function (){ return response('service in operation with token'); });
    Route::get('test-user', [AuthController::class, 'testUserLogged']);
    Route::post('logout',  [AuthController::class, 'logout']);

    // PROPERTY PROTECTED
    Route::post('edit-property/{id}', [PropertyController::class, 'update']);
    Route::resource('property', PropertyController::class)->except([
        'index', 'show'
    ]);

    //BLOG PROTECTED
    Route::post('edit-blog/{id}', [BlogController::class, 'update']);
    Route::resource('blog', BlogController::class)->except([
        'index', 'show'
    ]);

    //METADATA PROTECTED
    Route::post('edit-metadata/{id}', [MetadataController::class, 'update']);
    Route::resource('metadata', MetadataController::class)->except([
        'index', 'show'
    ]);
    
    //OFFICE PROTECTED
    Route::post('edit-office/{id}', [OfficeController::class, 'update']);
    Route::resource('office', OfficeController::class)->except([
        'index', 'show'
    ]);

    //PARTNERS PROTECTED
    Route::post('edit-partner/{id}', [PartnerController::class, 'update']);
    Route::resource('partner', PartnerController::class)->except([
        'index', 'show'
    ]);
    
    //REVIEW PROTECTED
    Route::post('edit-review/{id}', [ReviewController::class, 'update']);
    Route::resource('review', ReviewController::class)->except([
        'index', 'show'
    ]);

    // GENERAL DATA PROTECTED
    Route::post('general', [GeneralDataController::class, 'store']);
});

// PROPERTY
Route::get('get-property/{slug}', [PropertyController::class, 'getBySlug']);
Route::resource('property', PropertyController::class)->only([
    'index', 'show'
]);

// BLOG
Route::get('get-blog/{slug}', [BlogController::class, 'getBySlug']);
Route::resource('blog', BlogController::class)->only([
    'index', 'show'
]);

//METADATA
Route::resource('metadata', MetadataController::class)->only([
    'index', 'show'
]);

// OFFICE   
Route::get('get-office/{slug}', [OfficeController::class, 'getBySlug']);
Route::resource('office', OfficeController::class)->only([
    'index', 'show'
]);

// PARTNERS
Route::resource('partner', PartnerController::class)->only([
    'index', 'show'
]);

// REVIEW
Route::resource('review', ReviewController::class)->only([
    'index', 'show'
]);

//LOCALITIES
Route::get('localities', [PropertyController::class, 'getLocalities']);

// GENERAL DATA
Route::get('general', [GeneralDataController::class, 'index']);