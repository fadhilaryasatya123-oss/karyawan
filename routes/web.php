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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/email/verify', function (Request $request) {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        if (! app()->environment('testing') && in_array(config('mail.default'), ['log', 'array'], true)) {
            return back()->with('error', 'Pengiriman email belum dikonfigurasi. Hubungi Admin SDM.');
        }
        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            report($e);
            return back()->with('error', 'Email gagal dikirim. Hubungi Admin SDM.');
        }
        return back()->with('success', 'Tautan verifikasi telah dikirim. Periksa kotak masuk atau spam.');
    })->middleware('throttle:6,1')->name('verification.send');

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
    Route::middleware(['role:manajer', 'verified'])->prefix('manajer')->name('manajer.')->group(function () {
        Route::get('persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
        Route::get('persetujuan/riwayat', [PersetujuanController::class, 'riwayat'])->name('persetujuan.riwayat');
        Route::get('persetujuan/{pengajuan}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
        Route::post('persetujuan/{pengajuan}', [PersetujuanController::class, 'keputusan'])->name('persetujuan.keputusan');
    });

    // ---------- KARYAWAN ----------
    Route::middleware(['role:karyawan', 'verified'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('pengajuan', [KaryawanPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('pengajuan/create', [KaryawanPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('pengajuan', [KaryawanPengajuanController::class, 'store'])->name('pengajuan.store');
        Route::post('pengajuan/{pengajuan}/konfirmasi', [KaryawanPengajuanController::class, 'konfirmasi'])->name('pengajuan.konfirmasi');
        Route::delete('pengajuan/{pengajuan}', [KaryawanPengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    });
});
