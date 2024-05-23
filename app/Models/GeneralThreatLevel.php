<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralThreatLevel extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'form_id',
        'description_id',
        'name',
    ];

    /**
     * @var Array
     */
    protected $casts = [
        
    ];

    /**
     * @var Array
     */
    protected $hidden = [
        'form_id',
        'description_id',
    ];
}

