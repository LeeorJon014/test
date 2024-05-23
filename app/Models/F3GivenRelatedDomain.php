<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F3GivenRelatedDomain extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'f3_related_domain_id',
        'name',
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
        // 'f3_related_domain_id',
    ];

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function f3RelatedDomain()
    {
        return $this->belongsTo(F3RelatedDomain::class);
    }
}
