<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F1Category extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'f1_category_name',
        'unlisted_classification_name',
        'historical_site',
        'cultural_landscape',
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
        
    ];
}
