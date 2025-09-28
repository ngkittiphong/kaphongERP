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
        Schema::create('translation_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translation_key_id')->constrained('translation_keys')->onDelete('cascade');
            $table->string('locale', 5)->comment('Language code (e.g., en, th)');
            $table->text('value')->comment('Translated text');
            $table->boolean('is_active')->default(true)->comment('Whether this translation is active');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['locale', 'is_active']);
            $table->index('translation_key_id');
            $table->unique(['translation_key_id', 'locale'], 'unique_key_locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_values');
    }
};
