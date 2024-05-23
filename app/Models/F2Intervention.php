<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F2Intervention extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'name',
        'f2_description_id',
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
        'f2_description_id',
    ];
}
