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
        Schema::table('branches', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_status_id')->nullable()->after('is_head_office');
            $table->foreign('branch_status_id')->references('id')->on('branch_statuses')->onDelete('restrict');
            $table->index('branch_status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['branch_status_id']);
            $table->dropIndex(['branch_status_id']);
            $table->dropColumn('branch_status_id');
        });
    }
};
