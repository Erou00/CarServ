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
        Schema::create('detail_demandes', function (Blueprint $table) {
            $table->id();
            $table->integer('produit_id')->references('id')->on('produits');
            $table->boolean('qte_demandee');
            $table->integer('demande_id')->references('id')->on('demandes');
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
        Schema::dropIfExists('detail_demandes');
    }
};
