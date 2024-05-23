<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'form_id',
        'previous_threats_encountered',
        'unlisted_potential_threat',
        'statement_potential_threat',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var Array
     */
    protected $casts = [
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 
     * @var Array
     */
    protected $hidden = [
        'form_id',
    ];

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function culturalProperty()
    {
        return $this->hasOne(CulturalProperty::class);
    }

    /**
     * Retrieve records from child tables based on the description_id foreign key
     * 
     * @return Collection Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function f12ConditionResponses()
    {
        return $this->hasMany(F12ConditionResponse::class, 'description_id');
    }

    public function generalThreatLevels()
    {
        return $this->hasMany(GeneralThreatLevel::class, 'description_id');
    }

    public function potentialThreatLevels()
    {
        return $this->hasMany(PotentialThreatLevel::class, 'description_id');
    }
}
