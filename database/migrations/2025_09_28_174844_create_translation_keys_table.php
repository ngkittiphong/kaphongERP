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
        Schema::create('translation_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Unique translation key identifier');
            $table->string('group')->default('default')->comment('Translation group for organization');
            $table->text('description')->nullable()->comment('Description of what this translation is for');
            $table->boolean('is_active')->default(true)->comment('Whether this translation key is active');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['group', 'is_active']);
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_keys');
    }
};
