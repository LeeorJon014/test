<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F3RelatedDomain extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'cultural_property_id',
        'unlisted_related_domains',
        'unlisted_supporting_docu',
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
    public function f3GivenRelatedDomain()
    {
        return $this->hasMany(F3GivenRelatedDomain::class, 'f3_related_domain_id');
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function f3GivenSupportingDocu()
    {
        return $this->belongsTo(F3GivenSupportingDocu::class);
    }
}
