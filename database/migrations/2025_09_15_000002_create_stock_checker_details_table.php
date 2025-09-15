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
        Schema::create('stock_checker_details', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedBigInteger('stock_checker_id'); // INTEGER FK
            $table->unsignedBigInteger('product_id'); // INTEGER FK
            $table->datetime('date_check'); // DATETIME
            $table->integer('count_stock'); // INTEGER
            $table->integer('remain_stock'); // INTEGER
            $table->timestamps(); // Laravel timestamps

            $table->foreign('stock_checker_id')->references('id')->on('stock_checkers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_checker_details');
    }
};

