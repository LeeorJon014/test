<?php

namespace App\Imports;

use App\Models\PropertyName;
use App\Models\CulturalProperty;
use App\Models\Location;
use App\Models\Description;
use App\Models\PotentialThreatLevel;
use App\Models\Declaration;
use App\Models\NationalDeclaration;
use App\Models\LocalDeclaration;
use App\Models\PrimaryCriteria;
use App\Models\Significance;
use App\Models\AreaOfSignificance;      // No designated column inside responses file
use App\Models\Submitter;
use App\Models\Validator;
use App\Models\F3IntangibleCulturalHeritage;
use App\Models\F3CulturalBearer;
use App\Models\F3RelatedDomain;
use App\Models\F3GivenRelatedDomain;
use App\Models\F3GivenSupportingDocu;
use App\Models\F3GivenKindOfMeasure;
use App\Models\F3Description;
use App\Models\TangibleImmovable;
use App\Models\FloraFauna;
use App\Models\Movable;
use App\Models\F3SafeguardingMeasure;
use App\Helpers\Common;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CulturalPropertiesType3Import implements ToCollection, WithCalculatedFormulas
{
    const FIRST_REQUIRED_FIELD = 'Lungsod/Bayan';
    const FORM_ID = 3;
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Process a collection of rows during the import.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $formattedField = str_replace('_', '', self::FIRST_REQUIRED_FIELD);

        $columnMap = [
            'Timestamp' => 0,
            'Pangalan ng Buhay na Dunong' => 1,
            '(Mga) Komunidad o Grupong Etnolinggwistik na Nagtataglay ng Kasanayan' => 2,
            '(Mga) Pangalan ng Tagapagtaglay ng Kasanayan' => 3,
            'Barangay (Kasama mga Baryo, Sitio, o Purok kung Mayroon)' => 4,
            'Lungsod/Bayan' => 5,
            'Lalawigan' => 6,
            'Rehiyon' => 7,
            'Mga Karatig Lugar na Kinakikitaan ng Nasabing Pagsasapraktika ng Elemento, kung angkop' => 8, //neighbouring_places
            'Related_Domain' => 9, //checkbox; related domains
            'Supporting_Docu' => 10, //checkbox; supporting docu
            'Pakilarawan ang kasaysayan ng kasanayan, okasyon, o panahon, mga prosesong kasali, mga pamamaraan, mga kaugnay na paniniwala, mga konteksto, mga layunin, at iba pang mahalagang impormasyon tungkol sa buhay na dunong. Maaaring gumamit ng hiwalay na pahina para sa karagdagang impormasyon.' => 12, //describ_history_of_practice
            'Paraan ng Pagsalin' => 13, //mode_of_transmission
            'Ilarawan kung paano ipinamamana o isasalin ang kasanayan ng buhay na dunong. Maaaring gumamit ng hiwalay na pahina para sa karagdagang impormasyon.' => 14, //describe_intangible_practice

            //TO BE REVISED
            //'Ilista Dito (List Here)' => 16, //tangible_immovables table
            //'Ilista Dito (List Here)' => 18, //flora_faunas table
            // 'Ilista Dito (List Here)' => 20, //movables table

            'Pambansa/Pandaigdig na Deklarasyon' => 21,
            'Lokal na Deklarasyon' => 22,
            'Numero at Pamagat ng Deklarasyon, Ordinansa o Resolusyon' => 23,
            'Makasaysayang Kabuluhan' => 25,
            'Panlipunang Kabuluhan' => 26,
            'Politikal na Kabuluhan' => 27,
            'Pang_ekonomiyang Kabuluhan' => 28,
            'Espirituwal na Kabuluhan' => 29,
            'Pansining na Kabuluhan' => 30,
            'Pang_agham na Kabuluhan' => 31,
            'Potential Threats' => 34, //potential_threats table
            'Pahayag ng Potensyal na Panganib at Batayan Nito' => 35, //statement_potential_threats
            'Mga Sinuong na Panganib' => 36, //previous_threats_encountered
            'Kinds_of_Measure' => 37,
            'Mga Hakbang ng Pangangalaga' => 38, //f3_safeguarding_measures table
            'Mga Pangalan ng mga Nagdaan at Kasalukyang May-ari' => 39,
            'Major Bibliographic References and Key Informants' => 40, //references_and_informants
            'Submit_Pangalan' => 44, //submitters table
            'Submit_Sex' => 45,
            'Submit_Katungkulan' => 46,
            'Submit_Petsa' => 47,
            'Submit_Organisasyon' => 48,
            'Submit_Adres_ng_Organisasyon' => 49,
            'Submit_Pahinang_Facebook' => 50,
            'Submit_Website_URL' => 51,
            'Valid_Petsa' => 52, //validator
            'Valid_Pangalan' => 53,
            'Valid_Sex' => 54,
            'Valid_Katungkulan' => 55,
            'Valid_Organisasyon' => 56,
            'Valid_Adres_ng_Organisasyon' => 57,
            'Maaring_paki_upload_ang_scanned_copy_ng_PRECUP_Form_at_mga_sumusuportang_dokumento' => 58,
            'Encoded by (PRECUP/CPPRD) (For LGU: indicate N/A)' => 59, //encoder_name
            'Year of Submission' => 60,
            'Level of Submission' => 61,
            'Updated Entry from Previous Years' => 62,
            'Google Drink Link' => 63,
        ];


        // Read data from the excel file.
        foreach ($rows as $row) {
            $cleanColumnName = Str::replace(' ', '', $row[$columnMap[self::FIRST_REQUIRED_FIELD]]);

            if (!empty($row[$columnMap[self::FIRST_REQUIRED_FIELD]]) && $cleanColumnName !== $formattedField) {
                $propertyName = PropertyName::create([
                    'form_id' => self::FORM_ID,
                    'official_name' => $row[$columnMap['Pangalan ng Buhay na Dunong']],
                ]);

                $propertyNameID = $propertyName->id;
                $propertyName->save();

                $location = Location::create([
                    'form_id' => self::FORM_ID,
                    'barangay' => $row[$columnMap['Barangay (Kasama mga Baryo, Sitio, o Purok kung Mayroon)']],
                    'city_municipality' => $row[$columnMap['Lungsod/Bayan']],
                    'province' => $row[$columnMap['Lalawigan']],
                    'region' => $row[$columnMap['Rehiyon']],
                    'neighbouring_places' => $row[$columnMap['Mga Karatig Lugar na Kinakikitaan ng Nasabing Pagsasapraktika ng Elemento, kung angkop']],
                ]);

                $locationID = $location->id;
                $location->save();

                $description = Description::create([
                    'form_id' => self::FORM_ID,
                    'previous_threats_encountered' => $row[$columnMap['Mga Sinuong na Panganib']],
                    'statement_potential_threat' => $row[$columnMap['Pahayag ng Potensyal na Panganib at Batayan Nito']],
                ]);

                $descriptionID = $description->id;
                $description->save();

                if ($row[$columnMap['Potential Threats']]) {
                    PotentialThreatLevel::create([
                        'form_id' => self::FORM_ID,
                        'description_id' => $descriptionID,
                        'name' => $row[$columnMap['Potential Threats']],
                    ]);
                }

                $declaration = Declaration::create([
                    'form_id' => self::FORM_ID,
                    'number_and_title' => $row[$columnMap['Numero at Pamagat ng Deklarasyon, Ordinansa o Resolusyon']],
                ]);

                $declarationID = $declaration->id;
                $declaration->save();

                NationalDeclaration::create([
                    'declaration_id' => $declarationID,
                    'name' => $row[$columnMap['Pambansa/Pandaigdig na Deklarasyon']],
                ]);

                LocalDeclaration::create([
                    'declaration_id' => $declarationID,
                    'name' => $row[$columnMap['Lokal na Deklarasyon']],
                ]);

                $primaryCriteria = PrimaryCriteria::create([
                    'historical' => $row[$columnMap['Makasaysayang Kabuluhan']],
                    'social' => $row[$columnMap['Panlipunang Kabuluhan']],
                    'political' => $row[$columnMap['Politikal na Kabuluhan']],
                    'economic' => $row[$columnMap['Pang_ekonomiyang Kabuluhan']],
                    'spiritual' => $row[$columnMap['Espirituwal na Kabuluhan']],
                    'scientific' => $row[$columnMap['Pang_agham na Kabuluhan']],
                    'aesthetic' => $row[$columnMap['Pansining na Kabuluhan']],
                ]);

                $primaryCriteriaID = $primaryCriteria->id;
                $primaryCriteria->save();

                $significance = Significance::create([
                    'form_id' => self::FORM_ID,
                    'primary_criteria_id' => $primaryCriteriaID,
                ]);

                $significanceID = $significance->id;
                $significance->save();

                $submitter = Submitter::create([
                    'name' => $row[$columnMap['Submit_Pangalan']],
                    'sex' => $row[$columnMap['Submit_Sex']],
                    'designation' => $row[$columnMap['Submit_Katungkulan']],
                    'date' => Common::parseDate($row[$columnMap['Submit_Petsa']]),
                    'organization' => $row[$columnMap['Submit_Organisasyon']],
                    'address_of_organization' => $row[$columnMap['Submit_Adres_ng_Organisasyon']],
                    'facebook_page' => $row[$columnMap['Submit_Pahinang_Facebook']],
                    'website_page' => $row[$columnMap['Submit_Website_URL']],
                ]);

                $submitterID = $submitter->id;
                $submitter->save();

                $uuid = Str::uuid()->toString();

                $culturalProperty = CulturalProperty::create([
                    'uuid' => $uuid,
                    'form_id' => self::FORM_ID,
                    'property_name_id' => $propertyNameID,
                    'location_id' => $locationID,
                    'description_id' => $descriptionID,
                    'declaration_id' => $descriptionID,
                    'significance_id' => $significanceID,
                    'submitter_id' => $submitterID,
                    'company_id' => $this->companyId,
                ]);

                $culturalPropertyID = $culturalProperty->id;

                if ($row[$columnMap['Valid_Pangalan']] || $row[$columnMap['Encoded by (PRECUP/CPPRD) (For LGU: indicate N/A)']]) {
                    $culturalProperty->is_Validated = TRUE;
                    Validator::create([
                        'form_id' => self::FORM_ID,
                        'name' => $row[$columnMap['Valid_Pangalan']],
                        'cultural_property_id' => $culturalPropertyID,
                        'sex' => $row[$columnMap['Valid_Sex']],
                        'designation' => $row[$columnMap['Valid_Katungkulan']],
                        'date' => Common::parseDate($row[$columnMap['Valid_Petsa']]),
                        'organization' => $row[$columnMap['Valid_Organisasyon']],
                        'address_of_organization' => $row[$columnMap['Valid_Adres_ng_Organisasyon']],
                        'encoder_name' => $row[$columnMap['Encoded by (PRECUP/CPPRD) (For LGU: indicate N/A)']],
                        'year_of_submission' => $row[$columnMap['Year of Submission']],
                        'level_of_LGU_of_origin' => $row[$columnMap['Level of Submission']],
                    ]);
                }

                $culturalProperty->save();

                F3CulturalBearer::create([
                    'cultural_property_id' => $culturalPropertyID,
                    'ethnolinguistic_group' => $row[$columnMap['(Mga) Komunidad o Grupong Etnolinggwistik na Nagtataglay ng Kasanayan']],
                    'name' => $row[$columnMap['(Mga) Pangalan ng Tagapagtaglay ng Kasanayan']],
                ]);

                $f3RelatedDomain = F3RelatedDomain::create([
                    'cultural_property_id' => $culturalPropertyID,
                ]);

                $f3RelatedDomainID = $f3RelatedDomain->id;

                if ($row[$columnMap['Related_Domain']]) {
                    F3GivenRelatedDomain::create([
                        'f3_related_domain_id' => $f3RelatedDomainID,
                        'name' => $row[$columnMap['Related_Domain']],
                    ]);
                }

                if ($row[$columnMap['Supporting_Docu']]) {
                    F3GivenSupportingDocu::create([
                        'f3_related_domain_id' => $f3RelatedDomainID,
                        'name' => $row[$columnMap['Supporting_Docu']],
                    ]);
                }

                $f3RelatedDomain->save();

                F3Description::create([
                    'cultural_property_id' => $culturalPropertyID,
                    'describe_history_of_practice' => $row[$columnMap['Pakilarawan ang kasaysayan ng kasanayan, okasyon, o panahon, mga prosesong kasali, mga pamamaraan, mga kaugnay na paniniwala, mga konteksto, mga layunin, at iba pang mahalagang impormasyon tungkol sa buhay na dunong. Maaaring gumamit ng hiwalay na pahina para sa karagdagang impormasyon.']],
                    'mode_of_transmission' => $row[$columnMap['Paraan ng Pagsalin']],
                    'describe_intangible_practices' => $row[$columnMap['Ilarawan kung paano ipinamamana o isasalin ang kasanayan ng buhay na dunong. Maaaring gumamit ng hiwalay na pahina para sa karagdagang impormasyon.']],
                ]);

                $f3SafeguardingMeasure = F3SafeguardingMeasure::create([
                    // 'unlisted_kinds_measures_name' => $row[$columnMap['']],
                    'cultural_property_id' => $culturalPropertyID,
                    'measures' => $row[$columnMap['Mga Hakbang ng Pangangalaga']],
                ]);

                $f3SafeguardingMeasureId = $f3SafeguardingMeasure->id;
                $f3SafeguardingMeasure->save();

                if ($row[$columnMap['Kinds_of_Measure']]) {
                    F3GivenKindofMeasure::create([
                        'f3_safeguarding_measure_id' => $f3SafeguardingMeasureId,
                        'name' => $row[$columnMap['Kinds_of_Measure']],
                    ]);
                }
            }
        }
    }
}
