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
        Schema::create('marche_details', function (Blueprint $table) {
            $table->id();
            $table->integer('marche_id')->references('id')->on('marches');
            $table->integer('produit_id')->references('id')->on('produits');
            $table->integer('qte');
            $table->float('puht');
            $table->integer('tva');
            $table->integer('prix_total');
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
        Schema::dropIfExists('marche_details');
    }
};
