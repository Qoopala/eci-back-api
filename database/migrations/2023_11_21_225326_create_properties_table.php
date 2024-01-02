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
            $table->string('title', 100);
            $table->string('address', 100)->nullable();
            $table->string('reference', 45);
            $table->float('price', 10,2);
            $table->text('information');
            $table->foreignId('office_id')->references('id')->on('offices');
            $table->foreignId('locality_id')->references('id')->on('localities');
            $table->string('map', 800);
            $table->string('status', 50);
            
            //DISTRIBUCION
            $table->integer('number_room')->nullable();
            $table->float('hall_area', 8, 2)->nullable();
            $table->float('area', 8,2)->nullable();
            $table->integer('number_bath')->nullable();
            $table->float('terrace_area', 8, 2)->nullable();
            $table->float('balcony_area', 8, 2)->nullable();
            
            //CARACTERISTICAS GENERALES
            $table->boolean('heating')->nullable();
            $table->boolean('airconditioning')->nullable();
            $table->integer('year_construction')->nullable();
            $table->string('floor_type', 45)->nullable();
            $table->boolean('gas')->nullable();
            $table->string('energy_certification', 2)->nullable();

            //EQUIPAMIENTO
            $table->boolean('elevator')->nullable();
            $table->boolean('shared_terrace')->nullable();
            $table->boolean('parking')->nullable();
            $table->boolean('storage_room')->nullable();
            $table->boolean('pool')->nullable();
            $table->boolean('garden')->nullable();
            
            //ADICIONALES
            $table->boolean('public_transport')->nullable();
            $table->boolean('shopping')->nullable();
            $table->boolean('market')->nullable();
            $table->boolean('education_center')->nullable();
            $table->boolean('health_center')->nullable();
            $table->boolean('recreation_area')->nullable();
            
            $table->softDeletes();
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
