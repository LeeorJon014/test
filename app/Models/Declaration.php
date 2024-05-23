<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'form_id',
        'unlisted_national_name',
        'unlisted_local_name',
        'number_and_title',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var Array
     */
    protected $casts = [];

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
     * @return Collection Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nationalDeclaration()
    {
        return $this->hasMany(NationalDeclaration::class, 'declaration_id');
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function localDeclaration()
    {
        return $this->hasMany(LocalDeclaration::class, 'declaration_id');
    }

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recognitionsNonculturalBody()
    {
        return $this->hasMany(RecognitionsNonculturalBody::class, 'declaration_id');
    }
}
