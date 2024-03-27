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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('default'); // cart name
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unique(['user_id', 'name']);
            $table->timestamps();
        });

        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0);
            $table->string('order_type')->default('preset'); // sku's name
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('taxtype')->default('interstate'); // intrastate, interstate and union-territory
            $table->unique(['cart_id', 'product_id']);
            $table->boolean('draft')->default(false); // true: saveforlater & false: incart
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity'); // each slu's pcs quantity
            $table->foreignId('cart_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_range_id')->constrained()->onDelete('cascade');
            $table->unique(['cart_product_id', 'product_option_id', 'product_range_id'], 'cart_item_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('cart_products');
        Schema::dropIfExists('carts');
    }
};
