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
        Schema::create('checkouts', function (Blueprint $table) {

            $table->id();

            // Required to create order
            $table->foreignId('user_id')->constrained();
            
            // Required
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->foreign('billing_address_id')->references('id')->on('addresses');
            
            // Required
            $table->boolean('billing_shipping_same')->default(true);

            // Required if billing_shipping_same is false
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->foreign('shipping_address_id')->references('id')->on('addresses');

            // Required 
            $table->string('delivery_type')->nullable();
            
            // Required if delivery_type is pickup
            $table->unsignedBigInteger('pickup_address_id')->nullable();
            $table->foreign('pickup_address_id')->references('id')->on('pickup_addresses');
            
             // Required if billing_shipping_same is false
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->integer('coupon_value')->default(0);
            $table->string('pay_mode')->nullable();
            $table->string('pay_amt')->nullable();

            // Required
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('secret')->nullable();
            $table->string('note')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
