<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F1CurrentUseResponse extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'f1_current_use_id',
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
        // 'f1_current_use_id',
    ];

    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function f1CurrentUse()
    {
        return $this->belongsTo(F1CurrentUse::class);
    }
}
