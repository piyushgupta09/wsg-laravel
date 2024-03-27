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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('type'); // dropoff, pickup
            $table->text('shipping_address');
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('secret')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'processing', 'completed', 'cancelled'
            $table->text('tags')->nullable();
            $table->json('other')->nullable();
            $table->timestamps();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('expected_on')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
