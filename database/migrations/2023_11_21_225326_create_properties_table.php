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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title', 45);
            $table->string('address', 100)->nullable();
            $table->string('reference', 45);
            $table->float('price', 8,2);
            $table->string('information', 255);
            $table->integer('number_room');
            $table->integer('number_bath');
            $table->float('square_meter', 8,2);
            $table->string('energy_certification', 2)->nullable();
            $table->string('map', 255);
            $table->boolean('status');
            $table->foreignId('office_id')->references('id')->on('offices');
            $table->foreignId('locality_id')->references('id')->on('localities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
