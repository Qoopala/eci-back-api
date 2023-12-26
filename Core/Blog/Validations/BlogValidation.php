<?php

namespace Core\Blog\Validations;

use App\Models\Blog;
use Illuminate\Support\Facades\Validator;

class BlogValidation
{
    static function validateStore($data, $id = null){

        $rules = [
            'title'=>'required|string',
            'down'=>'required|string',
            'author'=>'required|string',
            'body'=>'required|string',
            'date'=>'date',
            'category_id'=>'required',
            'slug' => 'string|nullable'

        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $validator->errors();
        if(!$id){
            $unique_slug = Blog::where('slug', $data['slug'])->first();
            if($unique_slug) return 'slug already exist';
        }
        
        else return false;
    }
}