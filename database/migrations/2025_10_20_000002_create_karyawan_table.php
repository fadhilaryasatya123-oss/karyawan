<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nik', 50)->unique();
            $table->string('nama_lengkap');
            $table->string('departemen', 100);
            $table->string('jabatan', 100);
            $table->foreignId('atasan_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_bergabung');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
