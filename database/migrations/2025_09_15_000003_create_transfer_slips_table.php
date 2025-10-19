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
        Schema::create('transfer_slips', function (Blueprint $table) {
            $table->id(); // INTEGER PK
            $table->unsignedBigInteger('user_request_id'); // INTEGER FK
            $table->unsignedBigInteger('user_receive_id'); // INTEGER FK
            $table->string('transfer_slip_number', 50); // VARCHAR(50)
            $table->string('company_name', 150); // VARCHAR(150)
            $table->text('company_address')->nullable(); // TEXT
            $table->string('tax_id', 13)->nullable(); // VARCHAR(13)
            $table->string('tel', 30)->nullable(); // VARCHAR(30)
            $table->datetime('date_request'); // DATETIME
            $table->string('user_request_name', 150); // VARCHAR(150)
            $table->datetime('date_deliver')->nullable(); // DATETIME
            $table->string('deliver_name', 150)->nullable(); // VARCHAR(150)
            $table->datetime('date_receive')->nullable(); // DATETIME
            $table->string('user_receive_name', 150)->nullable(); // VARCHAR(150)
            $table->unsignedBigInteger('warehouse_origin_id'); // INTEGER FK
            $table->string('warehouse_origin_name', 150); // VARCHAR(150)
            $table->unsignedBigInteger('warehouse_destination_id'); // INTEGER FK
            $table->string('warehouse_destination_name', 150); // VARCHAR(150)
            $table->integer('total_quantity'); // INTEGER
            $table->unsignedTinyInteger('transfer_slip_status_id'); // TINYINT FK
            $table->text('description')->nullable(); // TEXT
            $table->text('note')->nullable(); // TEXT
            $table->timestamps(); // Laravel timestamps

            $table->foreign('user_request_id')->references('id')->on('users');
            $table->foreign('user_receive_id')->references('id')->on('users');
            $table->foreign('warehouse_origin_id')->references('id')->on('warehouses');
            $table->foreign('warehouse_destination_id')->references('id')->on('warehouses');
            $table->foreign('transfer_slip_status_id')->references('id')->on('transfer_slip_statuses');
            
            // Add unique constraint for transfer_slip_number
            $table->unique('transfer_slip_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_slips');
    }
};

