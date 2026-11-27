<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('jenis_cuti_id')->constrained('jenis_cuti')->cascadeOnDelete();
            $table->unsignedSmallInteger('tahun');
            $table->integer('kuota')->default(0);
            $table->integer('terpakai')->default(0);
            $table->integer('sisa')->default(0);
            $table->timestamps();

            $table->unique(['karyawan_id', 'jenis_cuti_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_cuti');
    }
};
