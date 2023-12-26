<?php

namespace App\Http\Controllers\Api\Review;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Core\Image\Validations\ImageValidation;
use Core\Review\Services\ReviewService;
use Core\Review\Validations\ReviewValidation;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        try {
            $review = Review::get();
            if($review) return ApiResponse::ok(__('messages.review_get_ok'), $review);
            else return ApiResponse::not_found(__('messages.review_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = ReviewValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = ReviewService::store($request);
         
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
            $review = Review::find($id);
            if($review) return ApiResponse::ok(__('messages.review_get_ok'), $review);
            else return ApiResponse::not_found(__('messages.review_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validate = ReviewValidation::validateStore($data, $id);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validate) return ApiResponse::badRequest($validateImage);

        try {
            $response = ReviewService::update($request, $id);
         
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
            $review =  Review::find($id);
            if ($review) {
                $review->delete();
                return ApiResponse::ok(__('messages.review_delete_ok'));
            } else {
                return ApiResponse::not_found(__('messages.review_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }
}
