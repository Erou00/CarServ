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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('marque_id')->references('id')->on('marques');
            $table->integer('model_id')->references('id')->on('modeles');
            $table->string('year');
            $table->integer('carbirant_id')->references('id')->on('carbirants');
            $table->integer('kilometre_id')->nullable();
            $table->integer('fiscal_power')->nullable();
            $table->string('gearbox')->nullable();
            $table->string('title')->nullable()->unique();
            $table->integer('doors')->nullable();
            $table->integer('origin_id')->references('id')->on('origins')->nullable();
            $table->boolean('first_hand')->nullable();
            $table->boolean('for_sale')->nullable();
            $table->string('carte_grise_front');
            $table->string('carte_grise_back');
            $table->text('images')->nullable();
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->integer('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('cars');
    }
};
