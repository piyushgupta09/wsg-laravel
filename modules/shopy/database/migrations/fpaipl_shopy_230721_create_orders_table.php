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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('oid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // 'pending', 'processing', 'completed', 'cancelled'
            $table->float('total')->default(0);
            $table->float('amount')->default(0);
            $table->float('tax')->default(0);
            $table->text('tags')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('pay_mode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('skus')->nullable(); // sku's count
            $table->integer('quantity')->nullable(); // quantity of every sku
            $table->float('amount')->default(0); // total - tax
            $table->float('tax')->default(0); // total * (taxrate/100)
            $table->float('total')->default(0); // quantity * rate
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unique(['order_id', 'product_id']);
            $table->string('suborder_id')->nullable(); // Order id + index
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('mrp', $precision = 8, $scale = 2)->default(0.00);
            $table->decimal('rate', $precision = 8, $scale = 2)->default(0.00);
            $table->decimal('price', $precision = 8, $scale = 2)->default(0.00);
            $table->decimal('discount', $precision = 8, $scale = 2)->default(0.00);
            $table->integer('quantity')->default(0); // each slu's pcs quantity
            $table->float('amount')->default(0); // total - tax
            $table->float('tax')->default(0); // total * (taxrate/100)
            $table->float('total')->default(0); // quantity * rate
            $table->foreignId('order_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_range_id')->constrained()->onDelete('cascade');
            $table->unique(['order_product_id', 'product_option_id', 'product_range_id'], 'order_item_unique');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->unique(['order_id', 'coupon_id']);
            $table->float('value')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_id')->constrained()->onDelete('cascade');
            $table->unique(['order_product_id', 'tax_id']);
            $table->float('igst')->default(0);
            $table->float('cgst')->default(0);
            $table->float('sgst')->default(0);
            $table->string('hsncode')->nullable();
            $table->float('gstrate')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('review')->nullable();
            $table->integer('rating')->default(0);
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_coupon');
        Schema::dropIfExists('order_tax');
        Schema::dropIfExists('order_product_reviews');
    }
};
