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
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained()->restrictOnDelete();
            $table->boolean('is_cover')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'media_id']);
            $table->index(['product_id', 'is_cover', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
