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
        Schema::create('transfer_slip_details', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedBigInteger('transfer_slip_id'); // INTEGER FK
            $table->unsignedBigInteger('product_id'); // INTEGER FK
            $table->string('product_name', 150); // VARCHAR(150)
            $table->text('product_description')->nullable(); // TEXT
            $table->integer('quantity'); // INTEGER
            $table->string('unit_name', 80); // VARCHAR(80)
            $table->decimal('cost_per_unit', 10, 2); // DOUBLE(10,2)
            $table->decimal('cost_total', 10, 2); // DOUBLE(10,2)
            $table->timestamps(); // Laravel timestamps

            $table->foreign('transfer_slip_id')->references('id')->on('transfer_slips')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_slip_details');
    }
};

