<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVagonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vagons', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('train_id');
            $table->integer('kapasite');
            $table->integer('dolu_koltuk');
            $table->double('doluluk_yuzdesi');
            $table->timestamps();
        });

        Schema::table('vagons', function(Blueprint $table){
            $table->foreign('train_id')->references('id')->on('trains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vagons');
    }
}
