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
        Schema::create('demande_details', function (Blueprint $table) {
            $table->id();
            $table->integer('demande_id')->references('id')->on('demandes');
            $table->integer('produit_id')->references('id')->on('produits');
            $table->integer('qte_demandee');
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
        Schema::dropIfExists('demande_details');
    }
};
