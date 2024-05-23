<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F1Description extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'cultural_property_id',
        'occupany_status',
        'current_physical_appearance',
        'original_physical_appearance',
        'period_of_construction',
        'specific_date',
        'other_background_info',
        'preservation_work_in_progress',
        'preservation_work_in_progress_desc',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var Array
     */
    protected $casts = [
        'preservation_work_in_progress' => 'boolean',
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
