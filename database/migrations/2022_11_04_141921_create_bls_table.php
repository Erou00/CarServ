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
        Schema::create('bls', function (Blueprint $table) {
            $table->id();
            $table->string('no_bl');
            $table->string('no_entrer');
            $table->integer('annee');
            $table->date('date');
            $table->integer('retard')->nullable();
            $table->integer('facture_id')->references('id')->on('facture')->nullable();
            $table->integer('commande_id')->references('id')->on('commandes')->nullable();
            $table->integer('marche_id')->references('id')->on('marches')->nullable();
            $table->integer('convention_id')->references('id')->on('conventions')->nullable();
            $table->integer('fournisseur_id')->references('id')->on('fournisseurs');
            $table->integer('magasin_id')->references('id')->on('magasins');
            $table->integer('sous_magasin_id')->references('id')->on('magasins');
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
        Schema::dropIfExists('bls');
    }
};
