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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedInteger('branch_id'); // INTEGER FK
            $table->unsignedBigInteger('user_create_id'); // BIGINT FK (matches users.id)
            $table->boolean('main_warehouse'); // BOOLEAN
            $table->string('name', 150); // VARCHAR(150)
            $table->datetime('date_create'); // DATETIME
            $table->unsignedTinyInteger('warehouse_status_id'); // TINYINT FK
            $table->decimal('avr_remain_price', 10, 2)->nullable(); // DOUBLE(10,2)
            $table->timestamps(); // Laravel timestamps

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('user_create_id')->references('id')->on('users');
            $table->foreign('warehouse_status_id')->references('id')->on('warehouse_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
