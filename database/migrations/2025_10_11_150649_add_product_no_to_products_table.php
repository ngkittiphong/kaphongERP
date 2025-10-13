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
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_no', 50)->nullable()->after('id');
            $table->unique('product_no'); // Make product_no unique
            $table->unique('sku_number'); // Make sku_number unique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['product_no']);
            $table->dropColumn('product_no');
            $table->dropUnique(['sku_number']);
        });
    }
};
