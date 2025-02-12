<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return  new class extends Migration
{
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50)->index();
            $table->string('sign', 200);
            $table->string('color', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_types');
    }
};