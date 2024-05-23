<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloraFauna extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'form_id',
        'name',
        'quantity',
        'use',
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
        'form_id',
    ];
}
