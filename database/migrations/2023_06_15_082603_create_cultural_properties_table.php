<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // COMMON FIELDS
        Schema::create('property_names', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('official_name', 255);
            $table->string('common_name', 255)->nullable();
            $table->string('filipino_name', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('street_address', 255)->nullable();
            $table->longText('barangay')->nullable();
            $table->string('city_municipality', 50);
            $table->string('province', 25);
            $table->string('region', 60);
            $table->string('cluster', 25)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->string('neighbouring_places', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->longText('previous_threats_encountered')->nullable();
            $table->string('unlisted_potential_threat', 255)->nullable();
            $table->longText('statement_potential_threat')->nullable();
            $table->timestamps();
        });

        Schema::create('declarations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('unlisted_national_name', 255)->nullable();
            $table->string('unlisted_local_name', 255)->nullable();
            $table->string('number_and_title', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('national_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('declaration_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('local_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('declaration_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('primary_criterias', function (Blueprint $table) {
            $table->id();
            $table->longText('historical')->nullable();
            $table->longText('social')->nullable();
            $table->longText('political')->nullable();
            $table->longText('economic')->nullable();
            $table->longText('spiritual')->nullable();
            $table->longText('scientific')->nullable();
            $table->longText('aesthetic')->nullable();
            $table->timestamps();
        });

        Schema::create('comparative_criterias', function (Blueprint $table) {
            $table->id();
            $table->longText('representativeness')->nullable();
            $table->longText('rarity')->nullable();
            $table->longText('interpretive_potential')->nullable();
            $table->timestamps();
        });

        Schema::create('significances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('primary_criteria_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('comparative_criteria_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('unlisted_area', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('tangible_immovables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->string('relationship_to_the_ich', 255)->nullable();
        });

        Schema::create('flora_faunas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->string('use', 255)->nullable();
        });

        Schema::create('movables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->string('relationship_to_the_ich', 255)->nullable();
        });

        Schema::create('submitters', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->string('sex', 250)->nullable();
            $table->string('designation', 255)->nullable();
            $table->date('date')->nullable();
            $table->longText('organization')->nullable();
            $table->longText('address_of_organization')->nullable();
            $table->string('facebook_page', 255)->nullable();
            $table->string('website_page', 255)->nullable();
            $table->string('encoder_name', 255)->nullable();
            $table->string('year_of_submission', 25)->nullable();
            $table->timestamps();
        });

        // MANDATORY TABLES (DON'T TOUCH)
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->timestamps();
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status', 55);
            $table->timestamps();
        });


        // CULTURAL PROPERTIES
        Schema::create('cultural_properties', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('form_id')->nullable();

            $table->foreignId('property_name_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('description_id')->constrained()->cascadeOnDelete();

            $table->foreignId('declaration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('significance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('submitter_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_Validated')->default(0);
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('status_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes()->nullable();
            $table->timestamps('validation_started_at')->nullable();
            $table->string('deletion_reason');
        });

        Schema::create('validators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255)->nullable();
            $table->string('sex', 50)->nullable();
            $table->string('designation', 255)->nullable();
            $table->date('date')->nullable();
            $table->string('organization', 255)->nullable();
            $table->string('address_of_organization', 255)->nullable();
            // $table->boolean('is_PRECUP')->default(0);
            $table->string('encoder_name', 255)->nullable();
            $table->string('year_of_submission', 50)->nullable();
            $table->string('level_of_LGU_of_origin', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('area_of_significances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('significance_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        // PARENT TABLE: declarations
        Schema::create('recognitions_noncultural_bodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('declaration_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        // PARENT TABLE: descriptions
        Schema::create('general_threat_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('description_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });

        // PARENT TABLE: descriptions
        Schema::create('potential_threat_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('description_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });

        Schema::create('f1_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('occupany_status', 50)->nullable();
            $table->longText('current_physical_appearance');
            $table->longText('original_physical_appearance');
            $table->string('period_of_construction', 100)->nullable();
            $table->string('specific_date', 25)->nullable();
            $table->longText('other_background_info');
            $table->boolean('preservation_work_in_progress')->default(0);
            $table->longText('preservation_work_in_progress_desc')->nullable();

            $table->timestamps();
        });

        Schema::create('f1_integrity_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f1_description_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('f1_legal_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('registry_of_deeds', 255);
            $table->string('legal_barangay', 50)->nullable();
            $table->string('legal_city', 50);
            $table->string('legal_province', 25);
            $table->string('approximate_area', 25);
            $table->timestamps();
        });

        Schema::create('f1_current_uses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('unlisted_name', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('f1_current_use_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f1_current_use_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('f12_classification_responses', function (Blueprint $table) {
            // FORM 1 only have category and classification (at iba pa)
            // FORM 2 have all of them
            // FORM 3 do not have classifications
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('category', 255);
            $table->string('unlisted_category', 255)->nullable();
            $table->string('subcategory', 255)->nullable();
            $table->string('unlisted_subcategory', 255)->nullable();
            $table->string('classification', 255)->nullable();
            $table->string('unlisted_classification', 255)->nullable();
            // FORM 1 ONLY
            $table->string('historical_site', 255)->nullable();
            $table->string('cultural_landscape', 255)->nullable();

            $table->timestamps();
        });

        Schema::create('f12_ownerships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('owner_name', 255);
            $table->string('owner_sex', 50)->nullable();
            $table->string('street_address', 255)->nullable();
            $table->string('city_municipality', 50);
            $table->string('province', 50);
            $table->string('region', 50)->nullable();
            $table->string('administrator', 255);
            $table->string('kind_of_ownership', 20);
            $table->string('public_accessibility', 20);
            $table->boolean('is_Multi_Provincial')->default(0);
            $table->timestamps();
        });

        Schema::create('f1_multiple_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f12_ownership_id')->constrained()->cascadeOnDelete();
            $table->string('stress_address', 255)->nullable();
            $table->string('city_municipality', 50);
            $table->string('province', 25);
            $table->string('region', 50)->nullable();
            $table->boolean('is_Public')->default(0);
            $table->timestamps();
        });

        Schema::create('f12_condition_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->foreignId('description_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('f2_story_and_heritages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });

        Schema::create('f2_reference_and_informants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });

        Schema::create('f2_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->longText('function');
            $table->longText('measurement');
            $table->longText('composition');
            $table->string('creator_name', 255)->nullable();
            $table->string('exact_date_of_creation', 250)->nullable();
            $table->string('place_of_creation', 255)->nullable();
            $table->string('associated_region_country_or_culture', 255)->nullable();
            $table->string('exact_date_of_discovery', 50)->nullable();
            $table->string('place_of_discovery', 255)->nullable();
            $table->string('name_of_previous_owners', 255)->nullable();
            $table->longText('history_of_acquisition')->nullable();
            $table->timestamps();
        });

        Schema::create('f2_interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f2_description_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('f2_period_of_creations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f2_description_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('f2_period_of_discoveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f2_description_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('f3_cultural_bearers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('ethnolinguistic_group', 255);
            $table->string('name', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('f3_related_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('unlisted_related_domains', 255)->nullable();
            $table->string('unlisted_supporting_docu', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('f3_given_related_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f3_related_domain_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });

        Schema::create('f3_given_supporting_docus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f3_related_domain_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });

        Schema::create('f3_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->longText('describe_history_of_practice');
            $table->longText('mode_of_transmission')->nullable();
            $table->longText('describe_intangible_practices')->nullable();
            $table->timestamps();
        });

        Schema::create('f3_safeguarding_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_property_id')->constrained()->cascadeOnDelete();
            $table->string('unlisted_kinds_measures_name', 255)->nullable();
            $table->longText('measures')->nullable();
            $table->timestamps();
        });

        Schema::create('f3_given_kind_of_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('f3_safeguarding_measure_id')->constrained()->cascadeOnDelete();
            $table->longText('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FORM 3
        Schema::dropIfExists('f3_given_kind_of_measures');
        Schema::dropIfExists('f3_safeguarding_measures');
        Schema::dropIfExists('f3_descriptions');
        Schema::dropIfExists('f3_given_supporting_docus');
        Schema::dropIfExists('f3_given_related_domains');
        Schema::dropIfExists('f3_related_domains');
        Schema::dropIfExists('f3_cultural_bearers');

        Schema::dropIfExists('f2_period_of_discoveries');
        Schema::dropIfExists('f2_period_of_creations');
        Schema::dropIfExists('f2_interventions');
        Schema::dropIfExists('f2_descriptions');
        Schema::dropIfExists('f2_reference_and_informants');
        Schema::dropIfExists('f2_story_and_heritages');

        Schema::dropIfExists('f12_condition_responses');
        Schema::dropIfExists('f1_multiple_addresses');
        Schema::dropIfExists('f12_ownerships');
        Schema::dropIfExists('f12_classification_responses');
        Schema::dropIfExists('f1_current_use_responses');
        Schema::dropIfExists('f1_current_uses');
        Schema::dropIfExists('f1_legal_descriptions');
        Schema::dropIfExists('f1_integrity_responses');
        Schema::dropIfExists('f1_descriptions');

        Schema::dropIfExists('potential_threat_levels');
        Schema::dropIfExists('general_threat_levels');
        Schema::dropIfExists('recognitions_noncultural_bodies');
        Schema::dropIfExists('area_of_significances');
        Schema::dropIfExists('validators');

        // CULTURE PROPERTY
        Schema::dropIfExists('cultural_properties');


        // MANDATORY TABLES
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('types');

        Schema::dropIfExists('submitters');
        Schema::dropIfExists('movables');
        Schema::dropIfExists('flora_faunas');
        Schema::dropIfExists('tangible_immovables');

        Schema::dropIfExists('significances');
        Schema::dropIfExists('comparative_criterias');
        Schema::dropIfExists('primary_criterias');
        Schema::dropIfExists('local_declarations');
        Schema::dropIfExists('national_declarations');
        Schema::dropIfExists('declarations');
        Schema::dropIfExists('descriptions');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('property_names');
    }
};
