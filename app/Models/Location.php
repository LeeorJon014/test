<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Array<int, string>
     */
    protected $fillable = [
        'form_id',
        'street_address',
        'barangay',
        'city_municipality',
        'province',
        'region',
        'cluster',
        'longitude',
        'latitude',
        'neighbouring_places',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var Array
     */
    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var Array
     */
    protected $hidden = [
        'form_id',
    ];
}
