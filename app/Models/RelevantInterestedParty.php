<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelevantInterestedParty extends Model
{
    use HasFactory;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'office_address_id',
        'mobile_num',
        'telephone_num',
    ];


    /**
     * @return Collection Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OfficeAddress()
    {
        return $this->belongsTo(OfficeAddress::class);
    }
}
