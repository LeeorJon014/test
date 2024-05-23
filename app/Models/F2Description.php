<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F2Description extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'cultural_property_id',
        'function',
        'measurement',
        'composition',
        'creator_name',
        'exact_date_of_creation',
        'place_of_creation',
        'associated_region_country_or_culture',
        'exact_date_of_discovery',
        'place_of_discovery',
        'name_of_previous_owners',
        'history_of_acquisition',
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
        'cultural_property_id',
    ];
}
