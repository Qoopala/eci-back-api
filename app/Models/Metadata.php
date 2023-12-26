<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Metadata extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'metadata';

    protected $fillable = [
        'title',
        'description',
        'path_image',
        'status_index',
        'section',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'title'=>'string',
        'description'=>'string',
        'path_image'=>'string',
        'status_index'=>'string',
        'section'=>'string',
    ];


    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    // public function partners(): HasMany
    // {
    //     return $this->hasMany(Partner::class);
    // }
}
