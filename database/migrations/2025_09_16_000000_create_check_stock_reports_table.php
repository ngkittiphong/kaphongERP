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
        Schema::create('check_stock_reports', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedBigInteger('user_create_id'); // INTEGER FK (matches users.id)
            $table->unsignedBigInteger('warehouse_id'); // INTEGER FK
            $table->datetime('datetime_create'); // DATETIME
            $table->boolean('closed')->default(false); // BOOLEAN
            $table->timestamps(); // Laravel timestamps

            $table->foreign('user_create_id')->references('id')->on('users');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_stock_reports');
    }
};
