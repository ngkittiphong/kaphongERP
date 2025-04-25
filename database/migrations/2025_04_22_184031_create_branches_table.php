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
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('branch_code', 10);
            $table->string('name_th', 200)->nullable();
            $table->string('name_en', 200)->nullable();
            $table->text('address_th')->nullable();
            $table->text('address_en')->nullable();
            $table->text('bill_address_th')->nullable();
            $table->text('bill_address_en')->nullable();
            $table->string('post_code', 10)->nullable();
            $table->string('phone_country_code', 5)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('website', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_head_office')->default(false);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_email', 200)->nullable();
            $table->string('contact_mobile', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');
            $table->unique(['company_id', 'branch_code']);
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
