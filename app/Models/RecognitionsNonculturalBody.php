<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecognitionsNonculturalBody extends Model
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
     * @var Array
     */
    protected $casts = [];

    /**
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
