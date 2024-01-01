<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SoftCascadeTrait;

    public $table = 'offices';

    protected $softCascade = ['metadata', 'officeImages'];

    protected $fillable = [
        'name',
        'cif',
        'address',
        'map',
        'email',
        'phone',
        'feature',
        'metadata_id',
        'slug',
        'locality_id'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'name'=>'string',
        'cif'=>'string',
        'address'=>'string',
        'map'=>'string',
        'email'=>'string',
        'phone'=>'string',
        'feature'=>'string',
        'metadata_id'=>'integer',
        'locality_id'=>'integer',
        'slug' => 'string'
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'property_id');
    }

    public function metadata(): BelongsTo
    {
        return $this->belongsTo(Metadata::class, 'metadata_id');
    }

    public function officeImages(): HasMany
    {
        return $this->hasMany(OfficeImages::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'locality_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

}
