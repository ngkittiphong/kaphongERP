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
        Schema::create('transfer_slip_statuses', function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true); // TINYINT PK (auto-increment)
            $table->string('name', 50); // VARCHAR(50)
            $table->string('sign', 200); // VARCHAR(200)
            $table->string('color', 50); // VARCHAR(50)
            $table->timestamps(); // Laravel timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_slip_statuses');
    }
};

