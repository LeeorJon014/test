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
        Schema::create('office_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street_address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city');
            $table->string('municipality');
            $table->string('province');
            $table->string('country');
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('office_address_id')->nullable();
            $table->timestamps();
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->foreignId('user_id')->cascadeOnDelete();
            $table->string('endpoint');
            $table->text('payloads');
            $table->text('response');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 64);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes()->nullable();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->binary('img_profile')->nullable();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('first_name', 64);
            $table->string('last_name', 64);
            $table->string('designation', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('office_addresses');
    }
};
