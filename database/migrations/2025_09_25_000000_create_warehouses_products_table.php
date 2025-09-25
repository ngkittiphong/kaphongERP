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
        Schema::create('warehouses_products', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedBigInteger('warehouse_id'); // BIGINT FK
            $table->unsignedBigInteger('product_id'); // BIGINT FK
            $table->integer('balance')->default(0); // INTEGER - Current quantity of the product in the warehouse
            $table->decimal('avr_buy_price', 10, 2)->default(0); // DOUBLE(10,2) - Average buying price
            $table->decimal('avr_sale_price', 10, 2)->default(0); // DOUBLE(10,2) - Average selling price
            $table->decimal('avr_remain_price', 10, 2)->default(0); // DOUBLE(10,2) - Average remaining price (cost)
            $table->timestamps(); // Laravel timestamps

            // Foreign key constraints
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate warehouse-product combinations
            $table->unique(['warehouse_id', 'product_id']);
            
            // Indexes for better performance
            $table->index('warehouse_id');
            $table->index('product_id');
            $table->index(['warehouse_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses_products');
    }
};
