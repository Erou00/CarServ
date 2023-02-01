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
        Schema::create('bes', function (Blueprint $table) {
            $table->id();
            $table->integer('no_sortie');
            $table->integer('annee');
            $table->string('designation');
            $table->date('date');
            $table->boolean('imp')->default(false);
            $table->integer('user_id')->references('id')->on('users')->nullable();
            $table->integer('magasin_id')->references('id')->on('magasins');
            $table->integer('sous_magasin_id')->references('id')->on('magasins');
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
        Schema::dropIfExists('bes');
    }
};
