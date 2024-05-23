<?php

namespace App\Http\Controllers\Traits;

use App\Models\Declaration;
use App\Models\Description;
use App\Models\GeneralThreatLevel;
use App\Models\F12ConditionResponse;
use App\Models\LocalDeclaration;
use App\Models\Location;
use App\Models\NationalDeclaration;
use App\Models\RecognitionsNonculturalBody;
use App\Models\PropertyName;
use App\Models\PotentialThreatLevel;
use App\Models\Significance;
use App\Models\AreaOfSignificance;
use App\Models\ComparativeCriteria;
use App\Models\CulturalProperty;
use App\Models\PrimaryCriteria;
use App\Models\Submitter;
use App\Models\F1LegalDescription;
use App\Models\F1CurrentUse;
use App\Models\F1CurrentUseResponse;
use App\Models\F12Ownership;
use App\Models\F12ClassificationResponse;
use App\Models\F2ReferenceAndInformant;
use App\Models\F2StoryAndHeritage;
use App\Models\F3CulturalBearer;
use App\Models\F3GivenRelatedDomain;
use App\Models\F3GivenSupportingDocu;
use App\Models\F3RelatedDomain;
use App\Models\F3Description;
use App\Models\F3GivenKindOfMeasure;
use App\Models\F3SafeguardingMeasure;

trait CulturalPropertyUpdateTrait
{
    public function getExclusiveFields($form_id)
    {
        switch ($form_id) {
            case 1:
                return [
                    'declaration' => ['f1RecognitionsNonCulturalBody'],
                    'description' => ['f12ConditionResponses'],
                    'significance' => ['f12ComparativeCriteria'],
                ];
            case 2:
                return [
                    'description' => ['f12ConditionResponses'],
                    'significance' => ['f12ComparativeCriteria'],
                ];
            case 3:
                return [];
        }
    }
    public function getExclusiveForms($form_id)
    {
        switch ($form_id) {
            case 1:
                return [
                    'f1LegalDescription',
                    'f1CurrentUse',
                    'f12ClassificationResponse',
                    'f12Ownership',
                ];
            case 2:
                return [
                    'f12ClassificationResponse',
                    'f12Ownership',
                    'f2ReferenceAndInformant',
                    'f2StoryAndHeritage',
                ];
            case 3:
                return [
                    'f3CulturalBearer',
                    'f3RelatedDomain',
                    'f3Description',
                    'f3SafeguardingMeasures',
                ];
        }
    }

    /**
     * Delete model records based on the provided criteria.
     *
     * @param string $modelClass
     * @param array $foreignKey The foreign key information ('title' and 'value').
     * @param array $request The request data ('field_name' and 'data').
     * @param int|null $formId Optional form ID for additional filtering.
     * @return void
     * @note Used when updating a form that may not include all database options,
     *       leading to the deletion of unselected records.
     */
    public function deleteRecordsNotInRequest($modelClass, $foreignKey, $request, $formId = null)
    {
        $modelClass::where($foreignKey['title'], $foreignKey['value'])
            ->when($formId, function ($query) use ($formId) {
                $query->where('form_id', $formId);
            })
            ->whereNotIn($request['field_name'], $request['data'])
            ->delete();
    }

    public function belongsToFormType($formId, $formName, $fieldName)
    {
        return isset($this->getExclusiveFields($formId)[$formName]) && in_array($fieldName, $this->getExclusiveFields($formId)[$formName]);
    }

    public function updatePropertyName($request, $id)
    {
        PropertyName::updateOrCreate(['id' => $id], [
            'official_name' => $request->data['official_name'],
            'common_name' => $request->data['common_name'],
            'filipino_name' => $request->data['filipino_name'],
        ]);

        CulturalProperty::where('property_name_id', $id)->update(['company_id' => $request->data['companyId']]);

        return response()->json(['message' => 'Property Names updated successfully']);
    }

    public function updateLocationName($request, $id)
    {
        Location::updateOrCreate(['id' => $id], [
            'street_address' => $request->data['street_address'],
            'barangay' => $request->data['barangay'],
            'city_municipality' => $request->data['city_municipality'],
            'province' => $request->data['province'],
            'region' => $request->data['region'],
            'neighbouring_places' => $request->data['neighbouring_places'],
        ]);

        return response()->json(['message' => 'Location updated successfully']);
    }

    public function updateDescription($request, $id)
    {
        if ($this->belongsToFormType($request->form_id, 'description', 'f12ConditionResponses')) {
            foreach ($request->data['f12_condition_responses_name'] as $name) {
                F12ConditionResponse::updateOrCreate([
                    'form_id' => $request->form_id,
                    'description_id' => $id,
                    'name' => $name,
                ]);
            }

            $this->deleteRecordsNotInRequest(
                formId: $request->form_id,
                modelClass: F12ConditionResponse::class,
                foreignKey: ['title' => 'description_id', 'value' => $id],
                request: ['field_name' => 'name', 'data' => $request->data['f12_condition_responses_name']],
            );
        }

        foreach ($request->data['general_threat_levels_name'] as $name) {
            GeneralThreatLevel::updateOrCreate([
                'form_id' => $request->form_id,
                'description_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            formId: $request->form_id,
            modelClass: GeneralThreatLevel::class,
            foreignKey: ['title' => 'description_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['general_threat_levels_name']]
        );

        foreach ($request->data['potential_threat_levels_name'] as $name) {
            PotentialThreatLevel::updateOrCreate([
                'form_id' => $request->form_id,
                'description_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: PotentialThreatLevel::class,
            foreignKey: ['title' => 'description_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['potential_threat_levels_name']],
            formId: $request->form_id,
        );

        Description::updateOrCreate(['id' => $id], [
            'previous_threats_encountered' => $request->data['previous_threats_encountered'],
            'statement_potential_threat' => $request->data['statement_potential_threat'],
            'unlisted_potential_threat' => $request->data['unlisted_potential_threat'],
        ]);

        return response()->json(['message' => 'Description updated successfully']);
    }

    public function updateDeclaration($request, $id)
    {
        if ($this->belongsToFormType($request->form_id, 'declaration', 'f1RecognitionsNonCulturalBody')) {
            foreach ($request->data['recognitions_noncultural_body_name'] as $name) {
                RecognitionsNonculturalBody::updateOrCreate([
                    'declaration_id' => $id,
                    'name' => $name,
                ]);
            }

            $this->deleteRecordsNotInRequest(
                modelClass: RecognitionsNonculturalBody::class,
                foreignKey: ['title' => 'declaration_id', 'value' => $id],
                request: ['field_name' => 'name', 'data' => $request->data['recognitions_noncultural_body_name']]
            );
        }

        foreach ($request->data['national_declaration_name'] as $name) {
            NationalDeclaration::updateOrCreate([
                'declaration_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: NationalDeclaration::class,
            foreignKey: ['title' => 'declaration_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['national_declaration_name']]
        );

        foreach ($request->data['local_declaration_name'] as $name) {
            LocalDeclaration::updateOrCreate([
                'declaration_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: LocalDeclaration::class,
            foreignKey: ['title' => 'declaration_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['local_declaration_name']]
        );

        Declaration::updateOrCreate(['id' => $id], [
            'unlisted_local_name' => $request->data['unlisted_local_name'],
            'unlisted_national_name' => $request->data['unlisted_national_name'],
            'unlisted_recognition_name' => $request->data['unlisted_recognition_name'],
            'number_and_title' => $request->data['number_and_title'],
        ]);

        return response()->json(['message' => 'Declaration updated successfully']);
    }

    public function updateSignificance($request, $id)
    {
        if ($this->belongsToFormType($request->form_id, 'significance', 'f12ComparativeCriteria')) {
            ComparativeCriteria::updateOrCreate(['id' => $request->child_id['comparative_criteria']], [
                'interpretive_potential' => $request->data['interpretive_potential'],
                'rarity' => $request->data['rarity'],
                'representativeness' => $request->data['representativeness'],
            ]);
        }

        foreach ($request->data['area_of_significance_name'] as $name) {
            AreaOfSignificance::updateOrCreate([
                'significance_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: AreaOfSignificance::class,
            foreignKey: ['title' => 'significance_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['area_of_significance_name']]
        );

        PrimaryCriteria::updateOrCreate(['id' => $request->child_id['primary_criteria']], [
            'aesthetic' => $request->data['aesthetic'],
            'economic' => $request->data['economic'],
            'historical' => $request->data['historical'],
            'political' => $request->data['political'],
            'scientific' => $request->data['scientific'],
            'social' => $request->data['social'],
            'spiritual' => $request->data['spiritual'],
        ]);

        Significance::updateOrCreate(['id' => $id], [
            'unlisted_area' => $request->data['unlisted_area'],
        ]);

        return response()->json(['message' => 'Significance updated successfully']);
    }

    public function updateSubmitter($request, $id)
    {
        Submitter::updateOrCreate(['id' => $id], [
            'address_of_organization' => $request->data['address_of_organization'],
            'date' => $request->data['date'],
            'designation' => $request->data['designation'],
            'encoder_name' => $request->data['encoder_name'],
            'facebook_page' => $request->data['facebook_page'],
            'name' => $request->data['name'],
            'organization' => $request->data['organization'],
            'sex' => $request->data['sex'],
            'website_page' => $request->data['website_page'],
            'year_of_submission' => $request->data['year_of_submission'],
        ]);

        return response()->json(['message' => 'Submitter updated successfully']);
    }

    public function updateF1LegalDescription($request, $id)
    {
        F1LegalDescription::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'registry_of_deeds' => $request->data['registry_of_deeds'],
            'legal_barangay' => $request->data['legal_barangay'],
            'legal_city' => $request->data['legal_city'],
            'legal_province' => $request->data['legal_province'],
            'approximate_area' => $request->data['approximate_area'],
        ]);

        return response()->json(['message' => 'F1LegalDescription updated successfully']);
    }

    public function updateF1CurrentUse($request, $id)
    {
        foreach ($request->data['current_use_name'] as $name) {
            F1CurrentUseResponse::updateOrCreate([
                'f1_current_use_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: F1CurrentUseResponse::class,
            foreignKey: ['title' => 'f1_current_use_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['current_use_name']]
        );

        F1CurrentUse::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'unlisted_name' => $request->data['unlisted_name'],
        ]);

        return response()->json(['message' => 'F1CurrentUseData updated successfully']);
    }

    public function updateF12Ownership($request, $id)
    {
        F12Ownership::updateOrCreate(['id' => $id], [
            'owner_name' => $request->data['owner_name'],
            'owner_sex' => $request->data['owner_sex'],
            'street_address' => $request->data['street_address'],
            'city_municipality' => $request->data['city_municipality'],
            'province' => $request->data['province'],
            'administrator' => $request->data['administrator'],
            'region' => $request->data['region'],
            'kind_of_ownership' => $request->data['kind_of_ownership'],
            'public_accessibility' => $request->data['public_accessibility'],
        ]);

        return response()->json(['message' => 'F12Ownership updated successfully']);
    }

    public function updateF2Reference($request, $id)
    {
        F2ReferenceAndInformant::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'name' => $request->data['name'],
        ]);

        return response()->json(['message' => 'F2ReferenceAndInformant updated successfully']);
    }

    public function updateF2StoryHeritage($request, $id)
    {
        F2StoryAndHeritage::updateOrCreate(['id' => $id], [
            'name' => $request->data['name'],
        ]);

        return response()->json(['message' => 'F2StoryAndHeritage updated successfully']);
    }

    public function updateF3CulturalBearer($request, $id)
    {
        F3CulturalBearer::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'ethnolinguistic_group' => $request->data['ethnolinguistic_group'],
            'name' => $request->data['name'],
        ]);

        return response()->json(['message' => 'F3CulturalBearer updated successfully']);
    }


    public function updateF3RelatedDomain($request, $id)
    {
        foreach ($request->data['related_domains_name'] as $name) {
            F3GivenRelatedDomain::updateOrCreate([
                'f3_related_domain_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: F3GivenRelatedDomain::class,
            foreignKey: ['title' => 'f3_related_domain_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['related_domains_name']]
        );

        foreach ($request->data['supporting_document_name'] as $name) {
            F3GivenSupportingDocu::updateOrCreate([
                'f3_related_domain_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: F3GivenSupportingDocu::class,
            foreignKey: ['title' => 'f3_related_domain_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['supporting_document_name']]
        );

        F3RelatedDomain::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'unlisted_related_domains' => $request->data['unlisted_related_domains'],
            'unlisted_supporting_docu' => $request->data['unlisted_supporting_docu'],
        ]);

        return response()->json(['message' => 'F3RelatedDomain updated successfully']);
    }

    public function updateF3Description($request, $id)
    {
        F3Description::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'describe_history_of_practice' => $request->data['describe_history_of_practice'],
            'mode_of_transmission' => $request->data['mode_of_transmission'],
            'describe_intangible_practices' => $request->data['describe_intangible_practices'],
        ]);

        return response()->json(['message' => 'F3Description updated successfully']);
    }

    public function updateF3SafeguardingMeasure($request, $id)
    {
        foreach ($request->data['kind_of_measure_name'] as $name) {
            F3GivenKindOfMeasure::updateOrCreate([
                'f3_safeguarding_measure_id' => $id,
                'name' => $name,
            ]);
        }

        $this->deleteRecordsNotInRequest(
            modelClass: F3GivenKindOfMeasure::class,
            foreignKey: ['title' => 'f3_safeguarding_measure_id', 'value' => $id],
            request: ['field_name' => 'name', 'data' => $request->data['kind_of_measure_name']]
        );

        F3SafeguardingMeasure::updateOrCreate(['id' => $id], [
            'cultural_property_id' => $request->child_id['cultural_property'],
            'unlisted_kinds_measures_name' => $request->data['unlisted_kinds_measures_name'],
            'measures' => $request->data['measures'],
        ]);

        return response()->json(['message' => 'F3SafeguardingMeasure updated successfully']);
    }

    public function updateF12Classification($request, $id)
    {
        F12ClassificationResponse::updateOrCreate(['id' => $id], [
            'form_id' => $request->form_id,
            'cultural_property_id' => $request->child_id['cultural_property'],
            'category' => $request->data['category'],
            'unlisted_category' => $request->data['unlisted_category'],
            'subcategory' => $request->data['subcategory'],
            'classification' => $request->data['classification'],
            'historical_site' => $request->data['historical_site'],
            'cultural_landscape' => $request->data['cultural_landscape'],
        ]);

        return response()->json(['message' => 'F12ClassificationResponse updated successfully']);
    }
}
