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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->string('mode'); // previous named as 'type'
            $table->string('type'); // upi, bank transfer, etc
            $table->string('reference_id');
            $table->float('amount')->default(0);
            $table->date('date');
            $table->text('tags')->nullable();
            $table->string('status');
            $table->json('other')->nullable();
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->softDeletes();

            $table->foreign('approved_by')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('checked_by')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
