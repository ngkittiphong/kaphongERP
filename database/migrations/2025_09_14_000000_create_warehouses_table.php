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
            $table->id();
            $table->unsignedInteger('branch_id');
            $table->string('warehouse_code', 50)->unique();
            $table->string('name_th', 255);
            $table->string('name_en', 255)->nullable();
            $table->text('address_th')->nullable();
            $table->text('address_en')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_main_warehouse')->default(false);
            $table->text('description')->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->string('contact_mobile', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('branches');
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
