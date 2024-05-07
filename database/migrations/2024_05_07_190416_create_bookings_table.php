<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('bus_id')->unsigned();
            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
            $table->bigInteger('line_id')->unsigned();
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->bigInteger('bus_seat_id')->unsigned();
            $table->foreign('bus_seat_id')->references('id')->on('bus_seats')->onDelete('cascade');
            $table->bigInteger('line_station_id')->unsigned();
            $table->foreign('line_station_id')->references('id')->on('line_stations')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
