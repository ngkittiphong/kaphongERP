<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('move_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);
            $table->string('sign', 200)->nullable();
            $table->string('color', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('move_types');
    }
};
