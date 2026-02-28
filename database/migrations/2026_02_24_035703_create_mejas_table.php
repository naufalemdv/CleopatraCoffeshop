<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mejas', function (Blueprint $table) {
            $table->id();
            $table->string('no_meja');
            $table->integer('kapasitas_kursi');
            $table->string('area')->nullable(); // cth: Teras, Lantai 1, Bar Counter
            $table->enum('status_meja', ['Tersedia', 'Terisi', 'Reserved', 'Maintenance'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mejas');
    }
};