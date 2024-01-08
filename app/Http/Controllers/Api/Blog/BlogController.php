<?php

namespace App\Http\Controllers\Api\Blog;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Core\Blog\Services\BlogService;
use Core\Blog\Validations\BlogValidation;
use Core\Image\Validations\ImageValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        try {
            $query = Blog::query();
            $blogs = $query->select(
                'id',
                'title',
                'down',
                'author',
                'body',
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as formatted_date"),
                'category_id',
                'metadata_id',
                'slug'
            )
            ->with('category', 'blogImages', 'metadata')
            ->orderBy('date', 'desc')
            ->paginate($perPage);
            
            if($blogs) return ApiResponse::ok(__('messages.blog_get_ok'), $blogs);
            else return ApiResponse::not_found(__('messages.blog_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->all();
        $validate = BlogValidation::validateStore($data);
        if($validate) return ApiResponse::badRequest($validate);

        $validateImage = ImageValidation::validateImage($request);
        if($validateImage) return ApiResponse::badRequest($validateImage);

        try {
            $response = BlogService::store($request);
         
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

    public function show($id)
    {
        try {
            $blog = Blog::select(
                'id',
                'title',
                'down',
                'author',
                'body',
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as formatted_date"),
                'category_id',
                'metadata_id',
                'slug'
            )->with('category', 'blogImages', 'metadata')->find($id);
            if($blog) return ApiResponse::ok(__('messages.blog_get_ok'), $blog);
            else return ApiResponse::not_found(__('messages.blog_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();

        $validateImage = ImageValidation::validateImage($request);
        if($validateImage) return ApiResponse::badRequest($validateImage);

        try {
            $response = BlogService::update($request, $id);
         
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
            $blog =  Blog::find($id);
            if ($blog) {
                $blog->delete();
                return ApiResponse::ok(__('messages.blog_delete_ok'));
            } else {
                return ApiResponse::not_found(__('messages.blog_not_found'));
            }
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function getBySlug($slug)
    {
        try {
            $blog = Blog::select(
                'id',
                'title',
                'down',
                'author',
                'body',
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as formatted_date"),
                'category_id',
                'metadata_id',
                'slug'
            )->with('category', 'blogImages', 'metadata')->where('slug', $slug)->first();
            if($blog) return ApiResponse::ok(__('messages.blog_get_ok'), $blog);
            else return ApiResponse::not_found(__('messages.blog_not_found'));
        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }
}
