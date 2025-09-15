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
        Schema::create('check_stock_details', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedBigInteger('user_check_id'); // INTEGER FK (matches users.id)
            $table->unsignedBigInteger('product_id'); // INTEGER FK
            $table->unsignedBigInteger('check_stock_report_id'); // INTEGER FK
            $table->integer('product_scan_num'); // INTEGER
            $table->datetime('datetime_scan'); // DATETIME
            $table->timestamps(); // Laravel timestamps

            $table->foreign('user_check_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('check_stock_report_id')->references('id')->on('check_stock_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_stock_details');
    }
};
