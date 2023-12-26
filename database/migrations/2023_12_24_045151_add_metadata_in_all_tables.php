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
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('metadata_id')->nullable()->references('id')->on('metadata');
        });
        Schema::table('offices', function (Blueprint $table) {
            $table->foreignId('metadata_id')->nullable()->references('id')->on('metadata');
        });
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('metadata_id')->nullable()->references('id')->on('metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_tables', function (Blueprint $table) {
            //
        });
    }
};
