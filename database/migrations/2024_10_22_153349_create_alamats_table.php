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
        Schema::create('alamats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_id')->nullable();
            $table->string('label');
            $table->string('nama');
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->string('organisasi')->nullable();
            $table->text('alamat_lengkap');
            $table->text('catatan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('id_lokasi')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('alamats');
    }
};
