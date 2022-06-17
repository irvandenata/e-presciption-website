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
        Schema::create('resep_signas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resep_id');
            $table->integer('signa_id');
            $table->boolean('isRacikan')->default(0);
            $table->string('nama_racikan')->nullable();
            $table->foreign('resep_id')->references('id')->on('reseps')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('signa_id')->references('signa_id')->on('signa_m')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('resep_signas');
    }
};
