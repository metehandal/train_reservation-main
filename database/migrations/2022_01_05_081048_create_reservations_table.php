<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('train_id');
            $table->unsignedBigInteger('vagon_id');
            $table->integer('kisi_sayisi');
            $table->timestamps();
        });

        Schema::table('reservations', function(Blueprint $table){
            $table->foreign('train_id')->references('id')->on('trains');
            $table->foreign('vagon_id')->references('id')->on('vagons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
