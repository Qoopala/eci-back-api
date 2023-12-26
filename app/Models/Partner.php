<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'partners';

    protected $fillable = [
        'name',
        'role',
        'path_image',
        'office_id',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'name'=>'string',
        'role'=>'string',
        'path_image'=>'string',
        'office_id'=>'integer',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

}
