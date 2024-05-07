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
        Schema::create('line_stations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bus_id')->unsigned();
            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
            $table->bigInteger('line_id')->unsigned();
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->string('name_en');
            $table->string('name_ar');
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_stations');
    }
};
