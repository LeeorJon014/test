<?php

namespace App\Imports;

use App\Models\CulturalProperty;
use App\Models\PropertyName;
use App\Models\Location;
use App\Models\F12ClassificationResponse;
use App\Models\F12Ownership;
use App\Models\F1LegalDescription;
use App\Models\F1CurrentUse;
use App\Models\F1Description;
use App\Models\Description;
use App\Models\PotentialThreatLevel;
use App\Models\GeneralThreatLevel;
use App\Models\Declaration;
use App\Models\NationalDeclaration;
use App\Models\LocalDeclaration;
use App\Models\PrimaryCriteria;
use App\Models\ComparativeCriteria;
use App\Models\AreaOfSignificance;
use App\Models\Significance;
use App\Models\Submitter;
use App\Models\Validator;
use App\Models\F1CurrentUseResponse;
use App\Models\F1IntegrityResponse;
use App\Models\F12ConditionResponse;
use App\Models\RecognitionsNonculturalBody;

use App\Helpers\Common;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CulturalPropertiesType1Import implements ToCollection, WithCalculatedFormulas
{
    const FIRST_REQUIRED_FIELD = 'Opisyal_na_Pangalan';
    const FORM_ID = 1;
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
            'Lungsod_Bayan' => 6,
            'Lalawigan' => 7,
            'Rehiyon' => 8,
            'Longitud' => 9,
            'Latitud' => 10,
            'For_Multiple_Provinces_please_indicate_below' => 11,

            'Kategorya' => 12,
            'Pag_Uuri_Govt_Structures' => 13,
            'Pag_Uuri_Schools' => 14,
            'Pag_Uuri_Hospitals' => 15,
            'Pag_Uuri_Churches' => 16,
            'Pag_Uuri_Monuments' => 17,
            'Pag_Uuri_Sites' => 18,
            'Pag_Uuri_Heritage' => 19,
            'Pag_Uuri_Natural_Geological' => 20,
            'Pag_Uuri_Bodies_of_Water' => 21,
            'Pag_Uuri_Flora' => 22,
            'Pag_Uuri_Fauna' => 23,
            'Pag_Uuri_Protected_Areas' => 24,
            'Makasaysayang_Lugar' => 25,
            'Kultural_na_Pook' => 26,
            'Iba_pa_tukuyin' => 27,

            'May_ari' => 28,
            'Biyolohikal_na_Pagkakakilanlan' => 29,
            'Tagapangasiwa' => 30,
            'Numero_Adres_Barangay_o_Distrito' => 31,
            'Owner_Lungsod' => 32,
            'Owner_Lalawigan' => 33,
            'Owner_Rehiyon' => 34,
            'Klase_ng_Pagmamay_ari' => 35,
            'Napupuntahan_ng_Publiko' => 36,

            'Numero_ng_Titulo' => 37,
            'Barangay_(Kasama_ang_Baryo_Sitio_o_Purok_kung_mayroon)' => 38,
            'Legal_Lungsod' => 39,
            'Legal_Lalawigan' => 40,
            'Tinatayang_Sukat_ng_Ari_Arian_sa_Metro_Kwadrado' => 41,

            'Kasalukuyang_Gamit' => 42,
            'Ibat_Ibang_Gamit_tukuyin' => 43,

            'Maaaring_paki_upload_ang_mapa_dito_Mga_tiyak_na_uri_ng_file_lamang_ang_tinatanggap' => 44,
            'Maaaring_paki_upload_ang_mga_larawan_o_ibang_multimedia_files_dito' => 45,

            'Kondisyon' => 46,
            'Lagay_ng_Paggamit_sa_Espasyo' => 47,
            'Ilarawan_ang_kasalukuyang_anyong_pisikal' => 48,
            'Integridad' => 49,
            'Ilarawan_ang_orihinal_na_anyong_pisikal_ato_mga_pagbabago_o_interbensyon' => 50,
            'Kapanahunan_ng_Pagpapatayo_ng_Estruktura' => 51,
            'Tiyak_na_Petsa' => 52,
            'Ilarawan_ang_iba_pang_kaligirang_impormasyon' => 53,
            'Kasalukuyang_may_gawain_ng_pangangalaga' => 54,
            'Kung_oo_ilarawan_ang_mga_ginawang_hakbang_ng_pangangalaga' => 55,

            'Pangkalahatang_Antas_ng_Panganib' => 56,
            'Mga_Sinuong_na_Panganib' => 57,
            'Maaaring_paki_upload_ang_mga_dagdag_impormasyon' => 58,
            'Mga_Potensiyal_na_Panganib' => 59,
            'Pahayag_ng_Potensyal_na_Panganib' => 60,

            'PambansaPandaigdig_na_Deklarasyon' => 61,
            'Lokal_na_Deklarasyon' => 62,
            'Pagkilala_mula_sa_mga_Lupong_hindi_Pangkultura' => 63,
            'Numero_at_Pamagat_ng_Deklarasyon_Ordinansa_o_Resolusyon' => 64,
            'Maaaring_paki_upload_ang_kopya_nga_deklarasyon_ordinansa' => 65,

            'Makasaysayang_Kabuluhan' => 66,
            'Panlipunan_na_Kabuluhan' => 67,
            'Pulitikal_na_Kabuluhan' => 68,
            'Pangekonomiyang_Kabuluhan' => 69,
            'Espiritwal_na_Kabuluhan' => 70,
            'Pang_agham_Pananaliksik_o_Teknolohikal_na_Kabuluhan' => 71,
            'Pansining_na_Kabuluhan' => 72,

            'Pagkakatawan_Makakatayo_ba_ang_ari_ariang_kultural_bilang_mahusay_na_kinatawan' => 73,
            'Natatangi_Bihira_na_ba_ang_ganitong_ari_ariang_kultural_hindi_pangkaraniwan' => 74,
            'Mapagpaliwanag_na_Potensiyal_Mayroon_bang_kakayahan_itong_ari_ariang_kultural' => 75,

            'Mga_Bahagi_ng_Kabuluhan' => 76,

            'Mahahalagang_Ari_Ariang_Kultural_na_Nasasalat' => 77,
            'Mga_Halaman_ato_Hayop' => 78,
            'Mga_Kwento_o_Pamanang_Higit_sa_Nasasalat' => 79,
            'Mga_Pangunahing_Sanggunian' => 80,

            'Submitter_Pangalan' => 81,
            'Submitter_Sex' => 82,
            'Submitter_Katungkulan' => 83,
            'Submitter_Petsa' => 84,
            'Submitter_Organisasyon' => 85,
            'Submitter_Adres_ng_Organisasyon' => 86,
            'Submitter_Pahinang_Facebook' => 87,
            'Pahinang_Website' => 88,
            'Validator_Pangalan' => 89,

            'Validator_Sex' => 90,
            'Validator_Katungkulan' => 91,
            'Validator_Petsa' => 92,
            'Validator_Organisasyon' => 93,
            'Validator_Adres_ng_Organisasyon' => 94,
            'Maaring_paki_upload_ang_scanned_copy_ng_PRECUP_Form' => 95,
            'Encoded_by' => 96,
            'Pinanggalingan_ng_Impormasyon' => 97, //
            'Lebel_ng_Pinagmulang_LGU' => 98,
            'Taon_ng_Pagpapasa' => 99,
            'For_Multiple_Provinces_please_indicate_below' => 100,
            'For_Multiple_Provinces_please_indicate_below' => 101,
            'Mahahalagang_Ari-Ariang_Kultural_na_Nasasalat' => 102,
            'Mga_Halaman_ato_Hayop' => 103,
            'Mga_Kwento_o_Pamanang_Higit_sa_Nasasalat' => 104,
            'Maaaring_paki_upload_ang_mga_larawan_o_ibang_multimedia_files_ng_ari_ariang_kultural_na_nasasalat_dito' => 105,
            'Mahahalagang Ari-Ariang Kultural na Nasasalat' => 106,
            'Mga Halaman at/o Hayop' => 107,
            'Isalaysay_dito' => 108,
            'Maaaring_paki_upload_ang_mga_larawan_o_ibang_multimedia_files_ng_flora_at_fauna_dito' => 109,
            'Maaaring_paki_upload_ang_mga_larawan_o_ibang_multimedia_files_ng_mga_kwento_o_pamanang_higit_sa_nasasalat_dito' => 110,
            'Updated_Entry_from_Previous_Years_Include_Remarks_in_Others_for_entries_with_"Possible_Duplication"_only' => 111,
            // 112-123 Iba_Pa_(Others_specify)
            'Iba_Pa_(Others_specify)' => 124,
            'Iba_pa_tukuyin_(Others_specify)' => 125,
            'Iba_pa_tukuyin_(Others_Specify)' => 126,
            'Google_Drive_Link' => 133,
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
                    'street_address' => $row[$columnMap['Numero_at_Adres']],
                    'barangay' => $row[$columnMap['Barangay']],
                    'city_municipality' => $row[$columnMap['Lungsod_Bayan']],
                    'province' => $row[$columnMap['Lalawigan']],
                    'region' => $row[$columnMap['Rehiyon']],
                    'longitude' => Common::convertCoordinatesToDecimal($row[$columnMap['Longitud']]),
                    'latitude' => Common::convertCoordinatesToDecimal($row[$columnMap['Latitud']]),
                ]);

                $locationID = $location->id;
                $location->save();

                $description = Description::create([
                    'form_id' => self::FORM_ID,
                    'statement_potential_threat' => $row[$columnMap['Pahayag_ng_Potensyal_na_Panganib']],
                    'previous_threats_encountered' => $row[$columnMap['Mga_Sinuong_na_Panganib']],
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

                if ($row[$columnMap['Mga_Sinuong_na_Panganib']]) {
                    PotentialThreatLevel::create([
                        'form_id' => self::FORM_ID,
                        'description_id' => $descriptionID,
                        'name' => $row[$columnMap['Mga_Sinuong_na_Panganib']],
                    ]);
                }

                $declaration = Declaration::create([
                    'form_id' => self::FORM_ID,
                    'number_and_title' => $row[$columnMap['Numero_at_Pamagat_ng_Deklarasyon_Ordinansa_o_Resolusyon']],
                ]);

                $declarationID = $declaration->id;
                $declaration->save();

                NationalDeclaration::create([
                    'declaration_id' => $declarationID,
                    'name' => $row[$columnMap['PambansaPandaigdig_na_Deklarasyon']],
                ]);

                LocalDeclaration::create([
                    'declaration_id' => $declarationID,
                    'name' => $row[$columnMap['Lokal_na_Deklarasyon']],
                ]);

                if ($row[$columnMap['Pagkilala_mula_sa_mga_Lupong_hindi_Pangkultura']]) {
                    RecognitionsNonculturalBody::create([
                        'declaration_id' => $declarationID,
                        'name' => $row[$columnMap['Pagkilala_mula_sa_mga_Lupong_hindi_Pangkultura']],
                        'form_id' => self::FORM_ID,
                    ]);
                }

                $primaryCriteria = PrimaryCriteria::create([
                    'historical' => $row[$columnMap['Makasaysayang_Kabuluhan']],
                    'social' => $row[$columnMap['Panlipunan_na_Kabuluhan']],
                    'political' => $row[$columnMap['Pulitikal_na_Kabuluhan']],
                    'economic' => $row[$columnMap['Pangekonomiyang_Kabuluhan']],
                    'spiritual' => $row[$columnMap['Espiritwal_na_Kabuluhan']],
                    'scientific' => $row[$columnMap['Pang_agham_Pananaliksik_o_Teknolohikal_na_Kabuluhan']],
                    'aesthetic' => $row[$columnMap['Pansining_na_Kabuluhan']],
                ]);

                $primaryCriteriaID = $primaryCriteria->id;
                $primaryCriteria->save();

                $comparativeCriteria = ComparativeCriteria::create([
                    'representativeness' => $row[$columnMap['Pagkakatawan_Makakatayo_ba_ang_ari_ariang_kultural_bilang_mahusay_na_kinatawan']],
                    'rarity' => $row[$columnMap['Natatangi_Bihira_na_ba_ang_ganitong_ari_ariang_kultural_hindi_pangkaraniwan']],
                    'interpretive_potential' => $row[$columnMap['Mapagpaliwanag_na_Potensiyal_Mayroon_bang_kakayahan_itong_ari_ariang_kultural']],
                ]);

                $comparativeCriteriaID = $comparativeCriteria->id;
                $comparativeCriteria->save();

                $significance = Significance::create([
                    'form_id' => self::FORM_ID,
                    'primary_criteria_id' => $primaryCriteriaID,
                    'comparative_criteria_id' => $comparativeCriteriaID,
                ]);

                $significanceID = $significance->id;
                $significance->save();

                if ($row[$columnMap['Mga_Bahagi_ng_Kabuluhan']]) {
                    AreaOfSignificance::create([
                        'significance_id' => $significanceID,
                        'name' => $row[$columnMap['Mga_Bahagi_ng_Kabuluhan']],
                    ]);
                }

                $submitter = Submitter::create([
                    'name' => $row[$columnMap['Submitter_Pangalan']],
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
                    'declaration_id' => $declarationID,
                    'description_id' => $descriptionID,
                    'significance_id' => $significanceID,
                    'submitter_id' => $submitterID,
                    'company_id' => $this->companyId,
                    // 'type_id' => 0,
                    // 'status_id' => 0,
                ]);

                $culturalPropertyID = $culturalProperty->id;

                if ($row[$columnMap['Validator_Pangalan']] || $row[$columnMap['Encoded_by']]) {
                    $culturalProperty->is_Validated = TRUE;
                    Validator::create([
                        'form_id' => self::FORM_ID,
                        'cultural_property_id' => $culturalPropertyID,
                        'name' => $row[$columnMap['Validator_Pangalan']],
                        'sex' => $row[$columnMap['Validator_Sex']],
                        'designation' => $row[$columnMap['Validator_Katungkulan']],
                        'date' => Common::parseDate($row[$columnMap['Validator_Petsa']]),
                        'organization' => $row[$columnMap['Validator_Organisasyon']],
                        'address_of_organization' => $row[$columnMap['Validator_Adres_ng_Organisasyon']],
                        'encoder_name' => $row[$columnMap['Encoded_by']],
                        'year_of_submission' => $row[$columnMap['Taon_ng_Pagpapasa']],
                        'level_of_LGU_of_origin' => $row[$columnMap['Lebel_ng_Pinagmulang_LGU']],
                    ]);
                }

                $culturalProperty->save();

                $category = F12ClassificationResponse::create([
                    'form_id' => self::FORM_ID,
                    'cultural_property_id' => $culturalPropertyID,
                    'category' => $row[$columnMap['Kategorya']],
                    'historical_site' => $row[$columnMap['Makasaysayang_Lugar']],
                    'cultural_landscape' => $row[$columnMap['Kultural_na_Pook']],
                ]);

                switch ($row[$columnMap['Kategorya']]) {
                    case 'Mga Gusaling Pampamahalaan, Pribado, at Pangkomersyo (Government Structures, Private Structures, and Commercial Establishments)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Govt_Structures']];
                        break;

                    case 'Mga Paaralan at Pang-Edukasyong Komplex (Schools and Educational Complexes)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Schools']];
                        break;

                    case 'Mga Ospital at Pasilidad Pangkalusugan (Hospital and Health Facilities)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Hospitals']];
                        break;

                    case 'Simbahan, Templo, at mga Lugar ng Pagsamba (Churches, Temples, and Places of Worship)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Churches']];
                        break;

                    case 'Mga Bantayog at Pananda (Monuments and Markers)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Monuments']];
                        break;

                    case 'Mga Lugar (Sites)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Sites']];
                        break;

                    case 'Mga Pamanang Bahay/Katutubong Arkitektura (Heritage Houses/Vernacular Architecture)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Heritage']];
                        break;

                    case 'Mga Likas na Pagkakabuong Heolohikal at Pisyograpikal/Pormasyon ng Lupa (Natural Geological and Physiographical/Land Formations)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Natural_Geological']];
                        break;

                    case 'Mga Uri ng Katubigan (Bodies of Water)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Bodies_of_Water']];
                        break;

                    case 'Mga Halaman (Flora)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Flora']];
                        break;

                    case 'Mga Hayop (Fauna)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Fauna']];
                        break;

                    case 'Iniingatang Pook (Protected Areas)':
                        $category->classification = $row[$columnMap['Pag_Uuri_Protected_Areas']];
                        break;

                    default:
                        break;
                }

                $category->save();

                F1LegalDescription::create([
                    'cultural_property_id' => $culturalPropertyID,
                    'registry_of_deeds' => $row[$columnMap['Numero_ng_Titulo']],
                    'legal_barangay' => $row[$columnMap['Barangay_(Kasama_ang_Baryo_Sitio_o_Purok_kung_mayroon)']],
                    'legal_city' => $row[$columnMap['Legal_Lungsod']],
                    'legal_province' => $row[$columnMap['Legal_Lalawigan']],
                    'approximate_area' => $row[$columnMap['Tinatayang_Sukat_ng_Ari_Arian_sa_Metro_Kwadrado']],
                ]);


                //TO BE REVISED: checkboxes
                $f1Description = F1Description::create([
                    'cultural_property_id' => $culturalPropertyID,
                    'occupany_status' => $row[$columnMap['Lagay_ng_Paggamit_sa_Espasyo']],
                    'current_physical_appearance' => $row[$columnMap['Ilarawan_ang_kasalukuyang_anyong_pisikal']],
                    'original_physical_appearance' => $row[$columnMap['Ilarawan_ang_orihinal_na_anyong_pisikal_ato_mga_pagbabago_o_interbensyon']],
                    'period_of_construction' => $row[$columnMap['Kapanahunan_ng_Pagpapatayo_ng_Estruktura']],
                    'specific_date' => $row[$columnMap['Tiyak_na_Petsa']],
                    'other_background_info' => $row[$columnMap['Ilarawan_ang_iba_pang_kaligirang_impormasyon']],
                ]);

                if ($row[$columnMap['Kasalukuyang_may_gawain_ng_pangangalaga']] === "Oo (Yes)") {
                    $f1Description->preservation_work_in_progress = TRUE;
                    $f1Description->preservation_work_in_progress_desc = $row[$columnMap['Kung_oo_ilarawan_ang_mga_ginawang_hakbang_ng_pangangalaga']];
                }

                $f1DescriptionID = $f1Description->id;
                $f1Description->save();

                if ($row[$columnMap['Integridad']]) {
                    F1IntegrityResponse::create([
                        'name' => $row[$columnMap['Integridad']],
                        'f1_description_id' => $f1DescriptionID,
                    ]);
                }

                F12Ownership::create([
                    'form_id' => self::FORM_ID,
                    'cultural_property_id' => $culturalPropertyID,
                    'owner_name' => $row[$columnMap['May_ari']],
                    'owner_sex' => $row[$columnMap['Biyolohikal_na_Pagkakakilanlan']],
                    'administrator' => $row[$columnMap['Tagapangasiwa']],
                    'street_address' => $row[$columnMap['Numero_Adres_Barangay_o_Distrito']],
                    'city_municipality' => $row[$columnMap['Owner_Lungsod']],
                    'province' => $row[$columnMap['Owner_Lalawigan']],
                    'region' => $row[$columnMap['Owner_Rehiyon']],
                    'kind_of_ownership' => $row[$columnMap['Klase_ng_Pagmamay_ari']],
                    'public_accessibility' => $row[$columnMap['Napupuntahan_ng_Publiko']],
                ]);

                $f1CurrentUse = F1CurrentUse::create([
                    'cultural_property_id' => $culturalPropertyID,
                    'unlisted_name' => $row[$columnMap['Ibat_Ibang_Gamit_tukuyin']],
                ]);

                $f1CurrentUseID = $f1CurrentUse->id;
                $f1CurrentUse->save();

                if ($row[$columnMap['Kasalukuyang_Gamit']]) {
                    F1CurrentUseResponse::create([
                        'f1_current_use_id' => $f1CurrentUseID,
                        'name' => $row[$columnMap['Kasalukuyang_Gamit']],
                    ]);
                }
            }
        }
    }
}
