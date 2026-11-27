<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => $this->admin(),
            'manajer' => $this->manajer($request),
            default => $this->karyawan($request),
        };
    }

    private function admin()
    {
        return view('dashboard.admin', [
            'totalKaryawan' => Karyawan::count(),
            'menunggu' => PengajuanCuti::where('status', 'menunggu')->count(),
            'disetujuiBulanIni' => PengajuanCuti::where('status', 'disetujui')
                ->whereMonth('tanggal_keputusan', now()->month)
                ->whereYear('tanggal_keputusan', now()->year)->count(),
            'terbaru' => PengajuanCuti::with(['karyawan', 'jenisCuti'])->latest()->take(5)->get(),
        ]);
    }

    private function manajer(Request $request)
    {
        $uid = $request->user()->id;
        $base = PengajuanCuti::whereHas('karyawan', fn ($q) => $q->where('atasan_id', $uid));

        return view('dashboard.manajer', [
            'menunggu' => (clone $base)->where('status', 'menunggu')->count(),
            'jumlahTim' => Karyawan::where('atasan_id', $uid)->count(),
            'daftar' => (clone $base)->with(['karyawan', 'jenisCuti'])->where('status', 'menunggu')->latest()->take(5)->get(),
        ]);
    }

    private function karyawan(Request $request)
    {
        $karyawan = $request->user()->karyawan;

        return view('dashboard.karyawan', [
            'karyawan' => $karyawan,
            'jenis' => \App\Models\JenisCuti::orderBy('nama_jenis')->get(),
            'saldo' => $karyawan
                ? SaldoCuti::with('jenisCuti')->where('karyawan_id', $karyawan->id)->where('tahun', now()->year)->get()
                : collect(),
            'terbaru' => $karyawan
                ? $karyawan->pengajuan()->with('jenisCuti')->latest()->take(5)->get()
                : collect(),
        ]);
    }
}
