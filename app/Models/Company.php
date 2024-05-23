<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'name',
        'office_address_id',
    ];

    protected $hidden = [
        'office_address_id',
    ];

    public function culturalProperty()
    {
        return $this->hasOne(CulturalProperty::class);
    }

    public function officeAddress()
    {
        return $this->belongsTo(OfficeAddress::class);
    }
}
