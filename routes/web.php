<?php

use App\Http\Controllers\Admin\JenisCutiController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\SaldoCutiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Karyawan\PengajuanController as KaryawanPengajuanController;
use App\Http\Controllers\Manajer\PersetujuanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---------- ADMIN ----------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('karyawan', KaryawanController::class)->except('show');
        Route::resource('jenis-cuti', JenisCutiController::class)
            ->parameters(['jenis-cuti' => 'jenisCuti'])->except('show');

        Route::get('saldo', [SaldoCutiController::class, 'index'])->name('saldo.index');
        Route::post('saldo/generate', [SaldoCutiController::class, 'generate'])->name('saldo.generate');
        Route::put('saldo/{saldo}', [SaldoCutiController::class, 'update'])->name('saldo.update');

        Route::get('pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');

        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/pengajuan', [LaporanController::class, 'pengajuan'])->name('laporan.pengajuan');
        Route::get('laporan/saldo', [LaporanController::class, 'saldo'])->name('laporan.saldo');
        Route::get('laporan/rekap', [LaporanController::class, 'rekap'])->name('laporan.rekap');
    });

    // ---------- MANAJER ----------
    Route::middleware('role:manajer')->prefix('manajer')->name('manajer.')->group(function () {
        Route::get('persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
        Route::get('persetujuan/riwayat', [PersetujuanController::class, 'riwayat'])->name('persetujuan.riwayat');
        Route::get('persetujuan/{pengajuan}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
        Route::post('persetujuan/{pengajuan}', [PersetujuanController::class, 'keputusan'])->name('persetujuan.keputusan');
    });

    // ---------- KARYAWAN ----------
    Route::middleware('role:karyawan')->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('pengajuan', [KaryawanPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('pengajuan/create', [KaryawanPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('pengajuan', [KaryawanPengajuanController::class, 'store'])->name('pengajuan.store');
        Route::delete('pengajuan/{pengajuan}', [KaryawanPengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    });
});
