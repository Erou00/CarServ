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
        Schema::create('inventaire_details', function (Blueprint $table) {
            $table->id();
            $table->integer('inventaire_id')->references('id')->on('inventaires');
            $table->integer('produit_id')->references('id')->on('produits');
            $table->string('lot')->nullable();
            $table->date('date_premption')->nullable();
            $table->integer('qte_inventorie')->nullable();
            $table->integer('qte_stock')->nullable();
            $table->integer('magasin_id')->references('id')->on('magasins')->nullable();
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
        Schema::dropIfExists('inventaire_details');
    }
};
