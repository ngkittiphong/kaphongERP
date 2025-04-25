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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->unsignedInteger('sale_order_id')->nullable();
            $table->unsignedInteger('transfer_slip_id')->nullable();
            $table->unsignedInteger('contact_id')->nullable();
            $table->dateTime('date_activity')->nullable();
            $table->unsignedTinyInteger('move_type_id');
            $table->string('detail', 300)->nullable();
            $table->integer('quantity_move');
            $table->string('unit_name', 50)->nullable();
            $table->integer('remaining')->nullable();
            $table->double('avr_buy_price', 10, 2)->nullable();
            $table->double('avr_sale_price', 10, 2)->nullable();
            $table->double('avr_remain_price', 10, 2)->nullable();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('move_type_id')->references('id')->on('move_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
