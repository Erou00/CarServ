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
        Schema::create('bs', function (Blueprint $table) {
            $table->id();
            $table->integer('no_bl');
            $table->integer('annee');
            $table->date('date');
            $table->string('sortie')->default('preparation');
            $table->integer('demande_id')->references('id')->on('demandes')->nullable();
            $table->integer('magasin_id')->references('id')->on('magasins');
            $table->integer('sous_magasin_id')->references('id')->on('magasins');
            $table->integer('entite_id')->references('id')->on('entites');
            $table->integer('facture_id')->references('id')->on('facture')->nullable();
            $table->boolean('imp')->default(false);
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
        Schema::dropIfExists('bs');
    }
};
