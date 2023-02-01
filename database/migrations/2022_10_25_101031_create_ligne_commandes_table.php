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
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('unite_id');
            $table->integer('qte');
            $table->float('pu');
            $table->string('observation');
            $table->float('puht');
            $table->float('tva');
            $table->string('n_lot')->nullable();
            $table->float('n_art')->nullable();
            $table->foreign('produit_id')->references('id')->on('produits');
            $table->foreign('commande_id')->references('id')->on('commandes');
            $table->foreign('unite_id')->references('id')->on('unite_reglementaires');
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
        Schema::dropIfExists('ligne_commandes');
    }
};
