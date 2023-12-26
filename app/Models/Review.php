<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'reviews';

    protected $fillable = [
        'name',
        'quote',
        'thumbnail',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'name'=>'string',
        'quote'=>'string',
        'thumbnail'=>'string',
    ];
}
