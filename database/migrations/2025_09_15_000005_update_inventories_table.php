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
        Schema::table('inventories', function (Blueprint $table) {
            // First modify the column types to match the referenced tables
            $table->unsignedBigInteger('warehouse_id')->nullable()->change();
            $table->unsignedBigInteger('transfer_slip_id')->nullable()->change();
            
            // Add foreign key constraints that were missing
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('transfer_slip_id')->references('id')->on('transfer_slips');
            // Note: sale_order_id and contact_id foreign keys are not added as those tables don't exist yet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['transfer_slip_id']);
        });
    }
};

