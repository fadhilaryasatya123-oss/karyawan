<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_cuti', function (Blueprint $table) {
            $table->dateTime('tanggal_konfirmasi')->nullable();
        });
    }

    public function down(): void
    {
        // Pertahankan saldo yang sudah dicadangkan jika revisi dibatalkan.
        \Illuminate\Support\Facades\DB::table('pengajuan_cuti')
            ->where('status', 'menunggu_konfirmasi')->update(['status' => 'disetujui']);
        Schema::table('pengajuan_cuti', function (Blueprint $table) {
            $table->dropColumn('tanggal_konfirmasi');
        });
    }
};
