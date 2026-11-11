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
        if (Schema::hasTable('media_attachments')) {
            Schema::table('media_attachments', function (Blueprint $table) {
                $table->index(['attachable_type', 'attachable_id', 'slot', 'sort_order'], 'media_attachment_order_index');
            });

            return;
        }

        Schema::create('media_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained()->restrictOnDelete();
            $table->morphs('attachable');
            $table->string('slot')->default('default');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id', 'slot', 'sort_order'], 'media_attachment_order_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_attachments');
    }
};
