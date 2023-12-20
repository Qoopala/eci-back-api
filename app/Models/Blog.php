<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'blogs';

    protected $fillable = [
        'title',
        'down',
        'author',
        'body',
        'date',
        'category_id',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'title'=>'string',
        'down'=>'string',
        'author'=>'string',
        'body'=>'string',
        'date'=>'datetime',
        'category_id'=>'integer',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function blogImages(): HasMany
    {
        return $this->hasMany(BlogImage::class);
    }

}
