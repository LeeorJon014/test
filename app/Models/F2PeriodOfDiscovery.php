<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F2PeriodOfDiscovery extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'f2_description_id',
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
        'f2_description_id',
    ];
}
