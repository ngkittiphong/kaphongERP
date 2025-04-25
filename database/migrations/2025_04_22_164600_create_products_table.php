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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('product_type_id');
            $table->unsignedInteger('product_group_id');
            $table->string('sku_number', 50)->nullable();
            $table->string('serial_number', 150)->nullable();
            $table->string('name', 150)->nullable();
            $table->mediumText('product_cover_img')->nullable();
            $table->string('unit_name', 80)->nullable();
            $table->double('buy_price', 10, 2)->default(0);
            $table->unsignedInteger('buy_vat_id')->nullable();
            $table->unsignedInteger('buy_withholding_id')->nullable();
            $table->text('buy_description')->nullable();
            $table->double('sale_price', 10, 2)->default(0);
            $table->unsignedInteger('sale_vat_id')->nullable();
            $table->unsignedInteger('sale_withholding_id')->nullable();
            $table->text('sale_description')->nullable();
            $table->integer('minimum_quantity')->nullable();
            $table->integer('maximum_quantity')->nullable();
            $table->unsignedInteger('product_status_id')->nullable();
            $table->dateTime('date_create')->useCurrent();
            
            // Foreign keys
            $table->foreign('product_type_id')->references('id')->on('product_types');
            $table->foreign('product_group_id')->references('id')->on('product_groups');
            $table->foreign('buy_vat_id')->references('id')->on('vats');
            $table->foreign('buy_withholding_id')->references('id')->on('withholdings');
            $table->foreign('sale_vat_id')->references('id')->on('vats');
            $table->foreign('sale_withholding_id')->references('id')->on('withholdings');
            $table->foreign('product_status_id')->references('id')->on('product_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
