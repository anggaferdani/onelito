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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('order_price')->nullable();
            $table->string('courier_tracking_id')->nullable();
            $table->string('courier_waybill_id')->nullable();
            $table->string('courier_company')->nullable();
            $table->string('courier_type')->nullable();
            $table->string('courier_driver_name')->nullable();
            $table->string('courier_driver_phone')->nullable();
            $table->string('courier_driver_plate_number')->nullable();
            $table->string('courier_driver_photo_url')->nullable();
            $table->string('courier_link')->nullable();
            $table->datetime('tanggal');
            $table->string('status');
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
        Schema::dropIfExists('trackings');
    }
};
