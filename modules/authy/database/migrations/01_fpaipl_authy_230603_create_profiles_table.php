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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('contacts')->nullable();
            $table->text('tags')->nullable();

            $table->boolean('role_assigned')->default(false);
            $table->unsignedBigInteger('account')->nullable();
            $table->unsignedBigInteger('cart_default')->nullable();
            $table->unsignedBigInteger('cart_buynow')->nullable();
            $table->unsignedBigInteger('checkout')->nullable();
            $table->unsignedBigInteger('billing')->nullable();
            $table->unsignedBigInteger('shipping')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
