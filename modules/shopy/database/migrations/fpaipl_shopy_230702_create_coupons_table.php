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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type')->default('fixed'); // fixed, percentage
            $table->integer('value')->default(0);
            $table->integer('max_value')->default(0);
            $table->integer('min_value')->default(0);
            $table->integer('max_usage')->default(0);
            $table->integer('max_usage_per_user')->default(0);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->boolean('active')->default(true);
            $table->text('detail')->nullable();
            $table->string('applicable')->default('all'); // all, products
            $table->json('products')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
