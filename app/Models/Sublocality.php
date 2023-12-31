<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sublocality extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
   
    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }
}
