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
        Schema::table('transfer_slips', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['user_receive_id']);
            
            // Make the column nullable
            $table->unsignedBigInteger('user_receive_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('user_receive_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_slips', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_receive_id']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('user_receive_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('user_receive_id')->references('id')->on('users');
        });
    }
};
