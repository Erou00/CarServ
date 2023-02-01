<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entites', function (Blueprint $table) {
            $table->id('id');
            $table->string('abbreviation')->nullable();
            $table->string('nom');
            $table->integer('entite_mere_id')->references('id')->on('entites')->onDelete('cascade')->nullable();
            $table->integer('type_entite_id')->references('id')->on('type_entites')->onDelete('cascade')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entites');
    }
};
