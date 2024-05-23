<?php

namespace App\Imports;

use App\Models\PropertyName;
use App\Models\Location;
use App\Models\F12ClassificationResponse;
use App\Models\Description;
use App\Models\F12ConditionResponse;
use App\Models\GeneralThreatLevel;
use App\Models\PotentialThreatLevel;
use App\Models\F2Intervention;
use App\Models\F2PeriodOfCreation;
use App\Models\F2PeriodOfDiscovery;
use App\Models\Declaration;
use App\Models\NationalDeclaration;
use App\Models\LocalDeclaration;
use App\Models\PrimaryCriteria;
use App\Models\ComparativeCriteria;
use App\Models\Significance;
use App\Models\Submitter;
use App\Models\CulturalProperty;
use App\Models\F2Description;
use App\Models\F12Ownership; // Does not have region
use App\Models\Validator;
use App\Models\AreaOfSignificance;
use App\Models\F2StoryAndHeritage;
use App\Models\F2ReferenceAndInformant;

use App\Helpers\Common;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CulturalPropertiesType2Import implements ToCollection, WithCalculatedFormulas
{
    const FIRST_REQUIRED_FIELD = 'Opisyal_na_Pangalan';
    const FORM_ID = 2;
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
            'Opisyal_na_Pangalan' => 1,
            'Karaniwang_Pangalan' => 2,
            'Lokal_na_Pangalan' => 3,

            'Numero_at_Adres' => 4,
            'Barangay' => 5,
            'Location_Lungsod' => 6,
            'Location_Lalawigan' => 7,
            'Rehiyon' => 8,
            'Longitud' => 9,
            'Latitud' => 10,

            'Kategorya' => 11,
            'Kategorya_Iba_pa' => 12,

            // 'Pangalawahing_Kategorya_ng_Kagamitang_Arkeolohikal' => 13,   
            'Artepakto' => 14,
            'Artepakto_Iba_pa' => 15,
            'Ekopakto' => 16,
            'Ekopakto_Iba_pa' => 17,
            'Uri_ng_Kagamitang_Etnograpikal' => 18,
            'Etnograpikal_Iba_pa' => 19,
            'Uri_ng_Kagamitang_Panrelihiyon' => 20,
            'Panrelihiyon_Iba_pa' => 21,
            'Uri_ng_Likha_ng_Sining_Komersyal_Industriyal' => 22,
            'Komersyal_Iba_pa' => 23,
            'Uri_ng_Likha_ng_Sining' => 24,
            'Sining_Iba_pa' => 25,
            'Uri_ng_Artsibo' => 26,
            'Artsibo_Iba_pa' => 27,
            'Uri_ng_Espesimen' => 28,
            'Espesimen_Iba_pa' => 29,

            'May_ari' => 30,
            'Biyolohikal_na_Pagkakakilanlan' => 31,
            'Numero_Adres_Barangay_o_Distrito' => 32,
            'Lungsod' => 33,
            'Lalawigan' => 34,
            'Tagapangasiwa' => 35,
            'Klase_ng_Pagmamay_ari' => 36,
            'Napupuntahan_ng_Publiko' => 37,

            // 'Maaring_paki_upload_ang_mga_larawan_o_media_dito' => 38,

            'Gamit' => 39,
            'Sukat_(sa_sentimetro)' => 40,
            'Materyal_na_Ginamit_Bumubuo' => 41,
            'Kondisyon' => 42,
            'Interbensyon' => 43,

            'Pangkalahatang_Antas_ng_Panganib' => 44,
            'Mga_Sinuong_na_Panganib' => 45,
            'Mga_Potensyal_na_Panganib' => 46,
            'Description_Iba_pa' => 47,
            'Pahayag_ng_Potensyal_na_Panganib' => 48,

            'Pangalan_ng_Lumikha' => 49,
            'Kapanahunan_ng_Paglikha' => 50,
            'Tiyak_na_Petsa_ng_Paglikha' => 51,
            'Lugar_Kung_Saan_Nilikha' => 52,
            'Lugar_o_Kulturang_May_Kinalaman_sa_Paglikha' => 53,
            'Kapanahunan_ng_Pagkatuklas' => 54,
            'Tiyak_na_Petsa_ng_Pagkatuklas' => 55,
            'Lugar_Kung_Saan_Natuklasan' => 56,
            'Pangalan_ng_Dating_Nagmamay_ari' => 57,
            'Kasaysayan_ng_Pagkamit_ng_Nagmamay_ari' => 58,

            'Pambansa_at_Pandaigdig_na_Deklarasyon' => 59,
            'Pambasa_Iba_pa' => 60,
            'Lokal_na_Deklarasyon' => 61,
            'Lokal_Iba_pa' => 62,
            'Pangalan_at_Numero_ng_Legal_na_Basehan_ng_Deklarasyon' => 63,

            'Makasaysayang_Kabuluhan' => 64,
            'Panlipunang_Kabuluhan' => 65,
            'Pulitikal_na_Kabuluhan' => 66,
            'Pangekonomiyang_Kabuluhan' => 67,
            'Espiritwal_na_Kabuluhan' => 68,
            'Pang_agham_na_Kabuluhan' => 69,
            'Pansining_na_Kabuluhan' => 70,

            'Pagkakatawan_Makakatayo_ba_ang_ari_ariang_kultural_bilang_mahusay_na_kinatawan' => 71,
            'Natatangi_Bihira_na_ba_ang_ganitong_ari_ariang_kultural' => 72,
            'Mapagpaliwanag_na_Potensiyal' => 73,

            'Mga_Bahagi_ng_Kabuluhan' => 74,
            'Area_Iba_pa' => 75,

            // 'I_upload_dito_ang_mga_larawan_ng_mahahalagang_ari_ariang_kultural_na_di_natitinag_(Upload_photos_of_associated_TangibleImmovable_cultural_property)' => 76,
            // 'I_upload_dito_ang_mga_larawan_ng_mahahalagang_flora_at_fauna_(Upload_photos_of_associated_flora_and_fauna)' => 77,
            'Narrate_Describe_here' => 78,
            // 'I_upload_dito_ang_mga_larawan_o_media_ng_mga_kuwento_o_pamanang_higit_sa_nasasalat_(Upload_photos_of_associated_stories_or_intangible_heritage)' => 79,

            'Major_Bibliographic_References_and_Key_Informants' => 80,

            'Pangalan_ng_Gumawa_ng_Dokumentasyon' => 81,
            'Submitter_Sex' => 82,
            'Submitter_Katungkulan' => 83,
            'Submitter_Petsa' => 84,
            'Submitter_Organisasyon' => 85,
            'Submitter_Adres_ng_Organisasyon' => 86,
            'Submitter_Pahinang_Facebook' => 87,
            'Pahinang_Website' => 88,

            // VALIDATOR
            'Lagda' => 89,
            'Pangalan_ng_Nagpatotoo' => 90,
            'Validator_Sex' => 91,
            'Validator_Katungkulan' => 92,
            'Validator_Petsa' => 93,
            'Validator_Organisasyon' => 94,
            'Validator_Adres_ng_Organisasyon' => 95,
            'Pinanggalingan_ng_Impormasyon' => 96, //
            // 'Maaaring_paki_upload_dito_ang_scanned_copy_ng_PRECUP_Form' => 97,
            'Encoded_by' => 98,
            'List_here_using_format_described_above' => 102,
            'Encoder_name' => 104,
            'Year_of_Submission' => 105,
            'Lebel_ng_Pinagmulang_LGU' => 106,
        ];

        // Read data from the excel file.
        foreach ($rows as $row) {
            $cleanColumnName = Str::replace(' ', '', $row[$columnMap[self::FIRST_REQUIRED_FIELD]]);

            if (!empty($row[$columnMap[self::FIRST_REQUIRED_FIELD]]) && $cleanColumnName !== $formattedField) {
                $propertyName = PropertyName::create([
                    'form_id' => self::FORM_ID,
                    'official_name' => $row[$columnMap['Opisyal_na_Pangalan']],
                    'common_name' => $row[$columnMap['Karaniwang_Pangalan']],
                    'filipino_name' => $row[$columnMap['Lokal_na_Pangalan']],
                ]);

                $propertyNameID = $propertyName->id;
                $propertyName->save();

                $location = Location::create([
                    'form_id' => self::FORM_ID,
                    'region' => $row[$columnMap['Rehiyon']],
                    'city_municipality' => $row[$columnMap['Location_Lungsod']],
                    'province' => $row[$columnMap['Location_Lalawigan']],
                    'barangay' => $row[$columnMap['Barangay']],
                    'street_address' => $row[$columnMap['Numero_at_Adres']],
                    'longitude' => Common::convertCoordinatesToDecimal($row[$columnMap['Longitud']]),
                    'latitude' => Common::convertCoordinatesToDecimal($row[$columnMap['Latitud']]),
                ]);

                $locationID = $location->id;
                $location->save();

                //TO BE REVISED: checkboxes
                $description = Description::create([
                    'form_id' => self::FORM_ID,
                    'previous_threats_encountered' => $row[$columnMap['Mga_Sinuong_na_Panganib']],
                    'unlisted_potential_threat' => $row[$columnMap['Description_Iba_pa']],
                    'statement_potential_threat' => $row[$columnMap['Pahayag_ng_Potensyal_na_Panganib']],
                ]);

                $descriptionID = $description->id;
                $description->save();

                if ($row[$columnMap['Kondisyon']]) {
                    F12ConditionResponse::create([
                        'form_id' => self::FORM_ID,
                        'description_id' => $descriptionID,
                        'name' => $row[$columnMap['Kondisyon']],
                    ]);
                }

                if ($row[$columnMap['Pangkalahatang_Antas_ng_Panganib']]) {
                    GeneralThreatLevel::create([
                        'form_id' => self::FORM_ID,
                        'description_id' => $descriptionID,
                        'name' => $row[$columnMap['Pangkalahatang_Antas_ng_Panganib']],
                    ]);
                }

                if ($row[$columnMap['Mga_Potensyal_na_Panganib']]) {
                    PotentialThreatLevel::create([
                        'form_id' => self::FORM_ID,
                        'description_id' => $descriptionID,
                        'name' => $row[$columnMap['Mga_Potensyal_na_Panganib']],
                    ]);
                }

                //TO BE REVISED: checkboxes
                $declaration = Declaration::create([
                    'form_id' => self::FORM_ID,
                    'unlisted_national_declaration' => $row[$columnMap['Pambasa_Iba_pa']],
                    'unlisted_local_declaration' => $row[$columnMap['Lokal_Iba_pa']],
                    'number_and_title' => $row[$columnMap['Pangalan_at_Numero_ng_Legal_na_Basehan_ng_Deklarasyon']],
                ]);

                $declarationID = $declaration->id;
                $declaration->save();

                NationalDeclaration::create([
                    'declaration_id' => $declarationID,
                    'name' => $row[$columnMap['Pambansa_at_Pandaigdig_na_Deklarasyon']],
                ]);

                LocalDeclaration::create([
                    'declaration_id' => $declarationID,
                    'name' => $row[$columnMap['Lokal_na_Deklarasyon']],
                ]);

                $primaryCriteria = PrimaryCriteria::create([
                    'historical' => $row[$columnMap['Makasaysayang_Kabuluhan']],
                    'social' => $row[$columnMap['Panlipunang_Kabuluhan']],
                    'political' => $row[$columnMap['Pulitikal_na_Kabuluhan']],
                    'economic' => $row[$columnMap['Pangekonomiyang_Kabuluhan']],
                    'spiritual' => $row[$columnMap['Espiritwal_na_Kabuluhan']],
                    'scientific' => $row[$columnMap['Pang_agham_na_Kabuluhan']],
                    'aesthetic' => $row[$columnMap['Pansining_na_Kabuluhan']],
                ]);

                $primaryCriteriaID = $primaryCriteria->id;
                $primaryCriteria->save();

                $comparativeCriteria = ComparativeCriteria::create([
                    'representativeness' => $row[$columnMap['Pagkakatawan_Makakatayo_ba_ang_ari_ariang_kultural_bilang_mahusay_na_kinatawan']],
                    'rarity' => $row[$columnMap['Natatangi_Bihira_na_ba_ang_ganitong_ari_ariang_kultural']],
                    'interpretive_potential' => $row[$columnMap['Mapagpaliwanag_na_Potensiyal']],
                ]);

                $comparativeCriteriaID = $comparativeCriteria->id;
                $comparativeCriteria->save();

                $significance = Significance::create([
                    'form_id' => self::FORM_ID,
                    'primary_criteria_id' => $primaryCriteriaID,
                    'comparative_criteria_id' => $comparativeCriteriaID,
                    'unlisted_area' => $row[$columnMap['Area_Iba_pa']],
                ]);

                $significanceID = $significance->id;
                $significance->save();

                // WAIT FOR UPDATE
                // TangibleImmovable::create([
                //     '' => $row[$columnMap['']],
                // ]);

                // WAIT FOR UPDATE
                // FloraFauna::create([
                //     '' => $row[$columnMap['']],
                // ]);

                // WAIT FOR UPDATE
                // Movable::create([
                //     '' => $row[$columnMap['']],
                // ]);

                $submitter = Submitter::create([
                    'name' => $row[$columnMap['Pangalan_ng_Gumawa_ng_Dokumentasyon']],
                    'sex' => $row[$columnMap['Submitter_Sex']],
                    'designation' => $row[$columnMap['Submitter_Katungkulan']],
                    'date' => Common::parseDate($row[$columnMap['Submitter_Petsa']]),
                    'organization' => $row[$columnMap['Submitter_Organisasyon']],
                    'address_of_organization' => $row[$columnMap['Submitter_Adres_ng_Organisasyon']],
                    'facebook_page' => $row[$columnMap['Submitter_Pahinang_Facebook']],
                    'website_page' => $row[$columnMap['Pahinang_Website']],
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
                    'declaration_id' => $declarationID,
                    'significance_id' => $significanceID,
                    'submitter_id' => $submitterID,
                    'company_id' => $this->companyId,
                    // 'type_id',
                    // 'status_id',
                ]);

                $culturalPropertyID = $culturalProperty->id;

                if ($row[$columnMap['Pangalan_ng_Nagpatotoo']]) {
                    $culturalProperty->is_Validated = TRUE;
                    Validator::create([
                        'form_id' => self::FORM_ID,
                        'cultural_property_id' => $culturalPropertyID,
                        'name' => $row[$columnMap['Pangalan_ng_Nagpatotoo']],
                        'sex' => $row[$columnMap['Validator_Sex']],
                        'designation' => $row[$columnMap['Validator_Katungkulan']],
                        'date' => Common::parseDate($row[$columnMap['Validator_Petsa']]),
                        'organization' => $row[$columnMap['Validator_Organisasyon']],
                        'address_of_organization' => $row[$columnMap['Validator_Adres_ng_Organisasyon']],
                    ]);
                }

                $culturalProperty->save();

                $f12ClassificationResponse = F12ClassificationResponse::create([
                    'form_id' => self::FORM_ID,
                    'cultural_property_id' => $culturalPropertyID,
                    'category' => $row[$columnMap['Kategorya']],
                    'unlisted_category' => $row[$columnMap['Kategorya_Iba_pa']],
                ]);

                switch ($row[$columnMap['Kategorya']]) {
                    case 'Kagamitang Arkeolohikal (Archaeological Materials)':
                        if ($row[$columnMap["Artepakto"]] || $row[$columnMap["Artepakto_Iba_pa"]]) {
                            $f12ClassificationResponse->subcategory = "Artifacts)";
                            $f12ClassificationResponse->classification = $row[$columnMap["Artepakto"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Artepakto_Iba_pa"]];
                        }

                        if ($row[$columnMap["Ekopakto"]] || $row[$columnMap["Ekopakto_Iba_pa"]]) {
                            $f12ClassificationResponse->subcategory = "Ecofacts)";
                            $f12ClassificationResponse->classification = $row[$columnMap["Ekopakto"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Ekopakto_Iba_pa"]];
                        }
                        break;

                    case 'Kagamitang Etnograpikal (Ethnographical Materials)':
                        if ($row[$columnMap["Uri_ng_Kagamitang_Etnograpikal"]] || $row[$columnMap["Etnograpikal_Iba_pa"]]) {
                            $f12ClassificationResponse->classification = $row[$columnMap["Uri_ng_Kagamitang_Etnograpikal"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Etnograpikal_Iba_pa"]];
                        }
                        break;

                    case 'Kagamitang Panrelihiyon (Religious Objects)':
                        if ($row[$columnMap["Uri_ng_Kagamitang_Panrelihiyon"]] || $row[$columnMap["Panrelihiyon_Iba_pa"]]) {
                            $f12ClassificationResponse->classification = $row[$columnMap["Uri_ng_Kagamitang_Panrelihiyon"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Panrelihiyon_Iba_pa"]];
                        }
                        break;

                    case 'Likha ng Sining Industriyal/Komersyal (Works of Industrial/Commercial Arts)':
                        if ($row[$columnMap["Uri_ng_Likha_ng_Sining_Komersyal_Industriyal"]] || $row[$columnMap["Komersyal_Iba_pa"]]) {
                            $f12ClassificationResponse->classification = $row[$columnMap["Uri_ng_Likha_ng_Sining_Komersyal_Industriyal"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Komersyal_Iba_pa"]];
                        }
                        break;

                    case 'Likha ng Sining (Artworks)':
                        if ($row[$columnMap["Uri_ng_Likha_ng_Sining"]] || $row[$columnMap["Sining_Iba_pa"]]) {
                            $f12ClassificationResponse->classification = $row[$columnMap["Uri_ng_Likha_ng_Sining"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Sining_Iba_pa"]];
                        }
                        break;

                    case 'Mga Arkibo (Archival Holdings)':
                        if ($row[$columnMap["Uri_ng_Artsibo"]] || $row[$columnMap["Artsibo_Iba_pa"]]) {
                            $f12ClassificationResponse->classification = $row[$columnMap["Uri_ng_Artsibo"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Artsibo_Iba_pa"]];
                        }
                        break;

                    case 'Mga Espesimen ng Natural na Kasaysayan (Natural History Specimens)':
                        if ($row[$columnMap["Uri_ng_Espesimen"]] || $row[$columnMap["Espesimen_Iba_pa"]]) {
                            $f12ClassificationResponse->classification = $row[$columnMap["Uri_ng_Espesimen"]];
                            $f12ClassificationResponse->unlisted_classification = $row[$columnMap["Espesimen_Iba_pa"]];
                        }
                        break;
                    default:
                        // Handle any unrecognized category values or skip them
                        break;
                }

                $f12ClassificationResponse->save();

                $F2Description = F2Description::create([
                    'cultural_property_id' => $culturalPropertyID,
                    'function' => $row[$columnMap['Gamit']],
                    'measurement' => $row[$columnMap['Sukat_(sa_sentimetro)']],
                    'composition' => $row[$columnMap['Materyal_na_Ginamit_Bumubuo']],
                    'creator_name' => $row[$columnMap['Pangalan_ng_Lumikha']],
                    'exact_date_of_creation' => $row[$columnMap['Tiyak_na_Petsa_ng_Paglikha']],
                    'place_of_creation' => $row[$columnMap['Lugar_Kung_Saan_Nilikha']],
                    'associated_region_country_or_culture' => $row[$columnMap['Lugar_o_Kulturang_May_Kinalaman_sa_Paglikha']],
                    'exact_date_of_discovery' => $row[$columnMap['Tiyak_na_Petsa_ng_Pagkatuklas']],
                    'place_of_discovery' => $row[$columnMap['Lugar_Kung_Saan_Natuklasan']],
                    'name_of_previous_owners' => $row[$columnMap['Pangalan_ng_Dating_Nagmamay_ari']],
                    'history_of_acquisition' => $row[$columnMap['Kasaysayan_ng_Pagkamit_ng_Nagmamay_ari']],
                ]);

                $F2DescriptionID = $F2Description->id;
                $F2Description->save();

                if ($row[$columnMap['Interbensyon']]) {
                    F2Intervention::create([
                        'f2_description_id' => $F2DescriptionID,
                        'name' => $row[$columnMap['Interbensyon']],
                    ]);
                }

                if ($row[$columnMap['Kapanahunan_ng_Paglikha']]) {
                    F2PeriodOfCreation::create([
                        'f2_description_id' => $F2DescriptionID,
                        'name' => $row[$columnMap['Kapanahunan_ng_Paglikha']],
                    ]);
                }

                if ($row[$columnMap['Kapanahunan_ng_Pagkatuklas']]) {
                    F2PeriodOfDiscovery::create([
                        'f2_description_id' => $F2DescriptionID,
                        'name' => $row[$columnMap['Kapanahunan_ng_Pagkatuklas']],
                    ]);
                }

                if ($row[$columnMap['Mga_Bahagi_ng_Kabuluhan']]) {
                    AreaOfSignificance::create([
                        'form_id' => self::FORM_ID,
                        'significance_id' => $significanceID,
                        'cultural_property_id' => $culturalPropertyID,
                        'name' =>  $row[$columnMap['Mga_Bahagi_ng_Kabuluhan']],
                    ]);
                }

                if ($row[$columnMap['Narrate_Describe_here']]) {
                    F2StoryAndHeritage::create([
                        'cultural_property_id' => $culturalPropertyID,
                        'name' => $row[$columnMap['Narrate_Describe_here']],
                    ]);
                }

                if ($row[$columnMap['Major_Bibliographic_References_and_Key_Informants']]) {
                    F2ReferenceAndInformant::create([
                        'cultural_property_id' => $culturalPropertyID,
                        'name' => $row[$columnMap['Major_Bibliographic_References_and_Key_Informants']],
                    ]);
                }

                /**
                 * OWNERSHIP
                 */
                F12Ownership::create([
                    'form_id' => self::FORM_ID,
                    'cultural_property_id' => $culturalPropertyID,
                    'owner_name' => $row[$columnMap['May_ari']],
                    'owner_sex' => $row[$columnMap['Biyolohikal_na_Pagkakakilanlan']],
                    'street_address' => $row[$columnMap['Numero_Adres_Barangay_o_Distrito']],
                    'city_municipality' => $row[$columnMap['Lungsod']],
                    'province' => $row[$columnMap['Lalawigan']],
                    'administrator' => $row[$columnMap['Tagapangasiwa']],
                    'kind_of_ownership' => $row[$columnMap['Klase_ng_Pagmamay_ari']],
                    'public_accessibility' => $row[$columnMap['Napupuntahan_ng_Publiko']],
                ]);
            }
        }
    }
}
