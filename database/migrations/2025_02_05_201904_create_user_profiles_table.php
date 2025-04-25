<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // Now just a normal column
            $table->string('profile_no', 20)->nullable();
            $table->mediumText('avatar')->nullable();
            $table->string('nickname', 30)->nullable();
            $table->string('card_id_no', 13)->nullable();
            $table->string('prefix_th', 30)->nullable();
            $table->string('fullname_th', 200)->nullable();
            $table->string('prefix_en', 30)->nullable();
            $table->string('fullname_en', 150)->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
