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
        Schema::create('marches', function (Blueprint $table) {
            $table->id();
            $table->string('no_marche');
            $table->string('ligne_budgetaire');
            $table->string('objet')->nullable();
            $table->integer('delais_execution');
            $table->date('ods');
            $table->string('type');
            $table->integer('tva')->default(0);
            $table->integer('fournisseur_id')->references('id')->on('fournisseurs');
            $table->boolean('imp')->default(false);
            $table->integer('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('marches');
    }
};