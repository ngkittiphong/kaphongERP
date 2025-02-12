<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->string('username', 150)->unique();
            $table->string('email', 150)->unique();
            $table->string('password', 300);
            $table->unsignedTinyInteger('user_type_id');
            $table->unsignedTinyInteger('user_status_id');
            $table->boolean('request_change_pass')->default(false);
            $table->timestamps(); // Laravel's created_at & updated_at

            // Indexing for performance
            $table->index(['email', 'username', 'user_type_id', 'user_status_id']);

            // Foreign Keys
            $table->foreign('profile_id')->references('id')->on('user_profiles')->onDelete('set null');
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('cascade');
            $table->foreign('user_status_id')->references('id')->on('user_statuses')->onDelete('cascade');

            // Adds deleted_at column
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
