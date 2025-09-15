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
        Schema::create('stock_checkers', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->string('checker_number', 50); // VARCHAR(50)
            $table->unsignedBigInteger('user_check_id'); // INTEGER FK (matches users.id)
            $table->unsignedBigInteger('warehouse_id'); // INTEGER FK
            $table->datetime('date_create'); // DATETIME
            $table->timestamps(); // Laravel timestamps

            $table->foreign('user_check_id')->references('id')->on('users');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            
            // Add unique constraint for checker_number
            $table->unique('checker_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_checkers');
    }
};

