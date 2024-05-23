<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F2ReferenceAndInformant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cultural_property_id',
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 
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
}
