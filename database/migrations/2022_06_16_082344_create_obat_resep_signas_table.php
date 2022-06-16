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
        Schema::create('obat_resep_signas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resep_signa_id');
            $table->integer('obat_id');
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('resep_signa_id')->references('id')->on('resep_signas')->onUpdate('CASCADE');
            $table->foreign('obat_id')->references('obatalkes_id')->on('obatalkes_m')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obat_resep_signas');
    }
};
