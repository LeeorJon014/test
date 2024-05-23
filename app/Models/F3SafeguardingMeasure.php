<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F3SafeguardingMeasure extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'cultural_property_id',
        'measures',
        'unlisted_kinds_measures_name',
    ];

    /**
     * @var Array
     */
    protected $casts = [
    ];

    /**
     * @var Array
     */
    protected $hidden = [
        'cultural_property_id',
    ];

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function culturalProperty()
    {
        return $this->hasOne(CulturalProperty::class);
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function f3GivenKindOfMeasure()
    {
        return $this->hasMany(F3GivenKindOfMeasure::class, 'f3_safeguarding_measure_id');
    }
}
