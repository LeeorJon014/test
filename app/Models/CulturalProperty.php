<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CulturalProperty extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    public function getDeletedAtAttribute($value)
    {
        return \Carbon\Carbon::now();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var Array <int, string>
     */
    protected $fillable = [
        'uuid',
        'form_id',
        'property_name_id',
        'location_id',
        'declaration_id',
        'description_id',
        'primary_criteria_id',
        'comparative_criteria_id',
        'significance_id',
        'submitter_id',
        'company_id',
        'type_id',
        'status_id',
        'is_Validated',
        'validation_started_at',
        'deletion_reason',
    ];

    const STATUS = [
        'PENDING' => 0,
        'COMPLIANT' => 1,
        'NONCOMPLIANT' => 2,
        'REVIEW' => 3,
        'ACCESSIONING' => 4,
        'PUBLIC VALIDATION' => 5,
        'PUBLISHED IN SITE' => 6,
        'PROCESSING FOR MAPAMANA' => 7,
        'PUBLISHED IN MAPAMANA' => 8,
        'COMPLIANT' => 1,
        'NONCOMPLIANT' => 2,
        'REVIEW' => 3,
        'ACCESSIONING' => 4,
        'PUBLIC VALIDATION' => 5,
        'PUBLISHED IN SITE' => 6,
        'PROCESSING FOR MAPAMANA' => 7,
        'PUBLISHED IN MAPAMANA' => 8,
    ];

    /**
     * @var Array
     */
    protected $casts = [
        'is_Validated' => 'integer',
    ];

    /**
     * @var Array
     */
    protected $hidden = [
        'primary_criteria_id',
        'f12_classification_response_id',
        'significance_id',
        'submitter_id',
        'location_id',
        'description_id',
        'declaration_id',
        'company_id',
        'deleted_at'
    ];

    public function getFormId()
    {
        return $this->form_id;
    }

    public function getPropertyNameId()
    {
        return $this->property_name_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propertyName()
    {
        return $this->belongsTo(PropertyName::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function description()
    {
        return $this->belongsTo(Description::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function significance()
    {
        return $this->belongsTo(Significance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submitter()
    {
        return $this->belongsTo(Submitter::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f12ClassificationResponse()
    {
        return $this->hasOne(F12ClassificationResponse::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f1LegalDescription()
    {
        return $this->hasOne(F1LegalDescription::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f1CurrentUse()
    {
        return $this->hasOne(F1CurrentUse::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f12Ownership()
    {
        return $this->hasOne(F12Ownership::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f2ReferenceAndInformant()
    {
        return $this->hasOne(F2ReferenceAndInformant::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f2StoryAndHeritage()
    {
        return $this->hasOne(F2StoryAndHeritage::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f3CulturalBearer()
    {
        return $this->hasOne(F3CulturalBearer::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f3RelatedDomain()
    {
        return $this->hasOne(F3RelatedDomain::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f3Description()
    {
        return $this->hasOne(F3Description::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function f3SafeguardingMeasures()
    {
        return $this->hasOne(F3SafeguardingMeasure::class, 'cultural_property_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
