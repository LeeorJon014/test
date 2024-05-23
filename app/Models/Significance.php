<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Significance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'primary_criteria_id',
        'comparative_criteria_id',
        'unlisted_area',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 
     * @var Array
     */
    protected $hidden = [
        'form_id',
        'primary_criteria_id',
        'comparative_criteria_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var Array
     */
    protected $casts = [
    ];

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function culturalProperty()
    {
        return $this->hasOne(CulturalProperty::class);
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function primaryCriteria()
    {
        return $this->BelongsTo(PrimaryCriteria::class);
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comparativeCriteria()
    {
        return $this->BelongsTo(ComparativeCriteria::class);
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areaOfSignificance()
    {
        return $this->hasMany(AreaOfSignificance::class, 'significance_id');
    }
}
