<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submitter extends Model
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
        'sex',
        'designation',
        'date',
        'organization',
        'address_of_organization',
        'facebook_page',
        'website_page',
        'encoder_name',
        'year_of_submission',
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
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function culturalProperty()
    {
        return $this->hasOne(CulturalProperty::class);
    }
}
