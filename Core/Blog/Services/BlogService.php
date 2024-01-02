<?php

namespace Core\Blog\Services;

use App\Models\Blog;
use App\Models\BlogImage;
use App\Models\Image;
use App\ServiceResponse;
use Core\Image\Services\ImageService;
use Core\Metadata\Services\MetadataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class BlogService
{
    static function store(Request $request){
        DB::beginTransaction();
        try {
            $blog = new Blog();
            $blog->title = $request->title;
            $blog->down = $request->down;
            $blog->author = $request->author;
            $blog->body = $request->body;
            $blog->date = $request->date;
            $blog->category_id = $request->category_id;
            $blog->slug = $request->slug;
            $blog->save();

            $images = ImageService::store($request, 'blog', $blog->id);
            if($images['success']) {
                foreach ($images['data'] as $path) {
                    $image = new BlogImage();
                    $image->path = $path;
                    $image->blog_id = $blog->id;
                    $image->save();
                }
            }
            $metadataId = MetadataService::store($request);
            if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            $blog->metadata_id = $metadataId;
            $blog->save();
            DB::commit();
            $response = Blog::select(
                'id',
                'title',
                'down',
                'author',
                'body',
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as formatted_date"),
                'category_id',
                'metadata_id',
                'slug'
            )->with('category','blogImages', 'metadata')->find($blog->id);
            return ServiceResponse::created(__('messages.blog_create_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

    static function update(Request $request, $id){
        // dd($request->file());
        $data = $request->all();
        $blog = Blog::find($id);
        if(!$blog) return ServiceResponse::not_found(__('messages.blog_not_found'));

        DB::beginTransaction();
        try {
            $blog->update($data);
            
            if(count($request->file())){
                $old_images =  BlogImage::where('blog_id', $id)->delete();
                $delete_old_images = ImageService::delete('blog', $blog->id);
                if(!$delete_old_images) return ServiceResponse::badRequest(__('messages.image_update_badrequest'));
    
                $images = ImageService::store($request, 'blog', $blog->id);
                if($images['success']) {
                    foreach ($images['data'] as $path) {
                        $image = new BlogImage();
                        $image->path = $path;
                        $image->blog_id = $blog->id;
                        $image->save();
                    }
                }
            }
            $metadataId = MetadataService::update($request, $blog->metadata_id);
            if(!$metadataId) return ServiceResponse::badRequest('Error updated metadata');
            DB::commit();
            $response = Blog::select(
                'id',
                'title',
                'down',
                'author',
                'body',
                DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as formatted_date"),
                'category_id',
                'metadata_id',
                'slug'
            )->with('category','blogImages', 'metadata')->find($id);
            return ServiceResponse::created(__('messages.blog_update_ok'), $response);
        } catch (\Throwable $th) {
            DB::rollBack();
            return (config('app.debug')) ? ServiceResponse::serverError($th->getMessage()) : ServiceResponse::serverError();
        }
    }

}
