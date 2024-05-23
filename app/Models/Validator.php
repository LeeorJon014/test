<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validator extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'form_id',
        'cultural_property_id',
        'name',
        'sex',
        'designation',
        'organization',
        'address_of_organization',
        'encoder_name',
        'year_of_submission',
        'level_of_LGU_of_origin',
        'date',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var Array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 
     * @var Array
     */
    protected $hidden = [
        'form_id',
        'cultural_property_id',
    ];
}
