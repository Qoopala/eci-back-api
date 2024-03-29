<?php

namespace Core\Review\Services;

use App\Models\Review;
use App\ServiceResponse;
use Core\Image\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    static function store(Request $request){
        DB::beginTransaction();
        try {
            $review = new  Review();
            $review->name = $request->name;
            $review->quote = $request->quote;
            $review->office_id = $request->office_id;
            $review->save();

            $images = ImageService::store($request, 'review', $review->id);
            if($images['success']) {
                foreach ($images['data'] as $path_image) {
                    $review->thumbnail = $path_image;
                }
            }

            $review->save();
            DB::commit();

            $response = Review::with('office')->find($review->id);
            return ServiceResponse::created(__('messages.review_create_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function update(Request $request, $id){
        $data = $request->all();
        $review =  Review::find($id);
        if(!$review) return ServiceResponse::not_found(__('messages.review_not_found'));

        DB::beginTransaction();
        try {
            $review->update($data);
            
            if(count($request->file())){

                $images = ImageService::store($request, 'review', $review->id);
                if($images['success']) {
                    foreach ($images['data'] as $path_image) {
                    $review->thumbnail = $path_image;
                    }
                }
            }
        
            DB::commit();
            $response = Review::with('office')->find($id);
            return ServiceResponse::created(__('messages.review_update_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
