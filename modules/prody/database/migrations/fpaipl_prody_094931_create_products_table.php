<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sid')->unique(); // brand->slug + product->slug
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('slug'); // product->name + product->code
            $table->string('code')->nullable();
            $table->longText('details')->nullable();
            $table->decimal('mrp', $precision = 8, $scale = 2)->default(0.00);
            $table->decimal('rate', $precision = 8, $scale = 2)->default(0.00);
            $table->string('moq')->default(1); // minimum order quantity
            $table->boolean('active')->default(true);
            $table->text('tags')->nullable();
            $table->foreignId('brand_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('tax_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('in_stock')->default(false);
            $table->json('stocks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // red
            $table->string('slug'); // red
            $table->string('code')->nullable(); // #ff0000
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unique(['product_id', 'slug']);
            $table->boolean('active')->default(true);
            $table->json('image')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });

        Schema::create('product_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Small
            $table->string('slug'); // small
            $table->decimal('mrp', $precision = 8, $scale = 2)->default(0.00);
            $table->decimal('rate', $precision = 8, $scale = 2)->default(0.00);
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unique(['product_id', 'slug']); // each product can have only one small size
            $table->boolean('active')->default(true); // enable/disable this size
            $table->timestamps();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // color
            $table->string('value'); // red
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });

        Schema::create('product_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // color
            $table->string('value')->nullable(); // red
            $table->string('size')->nullable(); // small
            $table->string('unit')->nullable(); // cm
            $table->foreignId('product_id')->constrained();
            $table->string('product_range_slug')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE products ADD FULLTEXT fulltext_index (tags)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_measurements');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_ranges');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('products');
    }
};
