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
        // Schema::connection(config('authy.altdb'))->create('countries', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->unique();
        //     $table->timestamps();
        // });

        // Schema::connection(config('authy.altdb'))->create('states', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->unique();
        //     $table->foreignId('country_id')->constrained();
        //     $table->timestamps();
        // });

        // Schema::connection(config('authy.altdb'))->create('districts', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->foreignId('state_id')->constrained();
        //     $table->timestamps();
        // });

        // Schema::connection(config('authy.altdb'))->create('pincodes', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('pincode')->unique();
        //     $table->foreignId('district_id')->constrained();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('authy.altdb'))->dropIfExists('countries');
        Schema::connection(config('authy.altdb'))->dropIfExists('states');
        Schema::connection(config('authy.altdb'))->dropIfExists('districts');
        Schema::connection(config('authy.altdb'))->dropIfExists('pincodes');
    }
};
