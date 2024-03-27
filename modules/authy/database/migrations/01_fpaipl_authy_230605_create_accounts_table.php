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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('application_id');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->string('approver_name')->nullable();
            $table->string('kycstep')->default('business');
            // Business
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('lifespan')->nullable();
            $table->string('turnover')->nullable();
            // Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('state')->nullable();
            $table->string('contact')->nullable();
            // Documents
            $table->string('gstin')->nullable();
            $table->string('aadhar')->nullable();
            $table->string('bank')->nullable();
            $table->string('pan')->nullable();
            $table->string('other')->nullable();
            // Tags
            $table->text('tags')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
