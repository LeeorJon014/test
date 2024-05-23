<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalDeclaration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'declaration_id',
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
        'declaration_id',
    ];

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}
