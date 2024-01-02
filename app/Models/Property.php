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
        'office_id',
        'locality_id',
        'sublocality_id',
        'map',
        'status',
        'number_room',
        'hall_area',
        'area',
        'number_bath',
        'terrace_area',
        'balcony_area',
        'heating',
        'airconditioning',
        'year_construction',
        'floor_type',
        'gas',
        'energy_certification',
        'elevator',
        'shared_terrace',
        'parking',
        'storage_room',
        'pool',
        'garden',
        'public_transport',
        'shopping',
        'market',
        'education_center',
        'health_center',
        'recreation_area',
        'deleted_at',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'title' => 'string',
        'address' => 'string',
        'reference' => 'string',
        'price' => 'float',
        'information' => 'string',
        'number_room' => 'integer',
        'number_bath' => 'integer',
        'square_meter' => 'float',
        'energy_certification' => 'string',
        'map' => 'string',
        'status' => 'string',
        'office_id' => 'integer',
        'locality_id' => 'integer',
        'sublocality_id' => 'integer',
        'slug' => 'string',
        'hall_area' => 'float',
        'area' => 'float',
        'terrace_area' => 'float',
        'balcony_area' => 'float',
        'heating' => 'boolean',
        'airconditioning' => 'boolean',
        'year_construction' => 'integer',
        'gas' => 'boolean',
        'elevator' => 'boolean',
        'shared_terrace' => 'boolean',
        'parking' => 'boolean',
        'storage_room' => 'boolean',
        'pool' => 'boolean',
        'garden' => 'boolean',
        'public_transport' => 'boolean',
        'shopping' => 'boolean',
        'market' => 'boolean',
        'education_center' => 'boolean',
        'health_center' => 'boolean',
        'recreation_area' => 'boolean',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'locality_id');
    }

    public function sublocality(): BelongsTo
    {
        return $this->belongsTo(Sublocality::class, 'sublocality_id');
    }

    public function metadata(): BelongsTo
    {
        return $this->belongsTo(Metadata::class, 'metadata_id');
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
