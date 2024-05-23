<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F1MultipleAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'f12_ownership_id',
        'stress_address',
        'city_municipality',
        'province',
        'region',
        'is_Public',
    ];

    /**
     * @var Array
     */
    protected $casts = [
        'is_Public' => 'boolean',
    ];

    /**
     * @var Array
     */
    protected $hidden = [
        'f12_ownership_id',
    ];
}
