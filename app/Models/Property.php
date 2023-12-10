<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SoftCascadeTrait;

    public $table = 'properties';

    protected $softCascade = ['images', 'features'];

    protected $fillable = [
        'title',
        'address',
        'reference',
        'price',
        'information',
        'number_room',
        'number_bath',
        'square_meter',
        'energy_certification',
        'map',
        'status',
        'office_id',
        'locality_id'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'title'=>'string',
        'address'=>'string',
        'reference'=>'string',
        'price'=>'float',
        'information'=>'string',
        'number_room'=>'integer',
        'number_bath'=>'integer',
        'square_meter'=>'float',
        'energy_certification'=>'string',
        'map'=>'string',
        'status'=>'boolean',
        'office_id'=>'integer',
        'locality_id'=>'integer'
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'locality_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'property_id');
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class, 'property_id');
    }
}
