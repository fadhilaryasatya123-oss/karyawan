<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function pengajuan(Request $request)
    {
        $d = $request->validate(['dari' => 'required|date', 'sampai' => 'required|date|after_or_equal:dari']);

        $data = PengajuanCuti::with(['karyawan', 'jenisCuti'])
            ->whereBetween('tanggal_mulai', [$d['dari'], $d['sampai']])
            ->orderBy('tanggal_mulai')
            ->get();

        return Pdf::loadView('laporan.pdf_pengajuan', ['data' => $data, 'dari' => $d['dari'], 'sampai' => $d['sampai']])
            ->setPaper('a4', 'landscape')
            ->download('laporan-pengajuan-cuti.pdf');
    }

    public function saldo(Request $request)
    {
        $tahun = (int) $request->validate(['tahun' => 'required|integer|min:2020|max:2100'])['tahun'];

        $data = SaldoCuti::with(['karyawan', 'jenisCuti'])
            ->where('tahun', $tahun)
            ->get()
            ->sortBy(fn ($s) => $s->karyawan->nama_lengkap . $s->jenisCuti->nama_jenis)
            ->values();

        return Pdf::loadView('laporan.pdf_saldo', compact('data', 'tahun'))
            ->download("laporan-saldo-cuti-$tahun.pdf");
    }

    public function rekap(Request $request)
    {
        $tahun = (int) $request->validate(['tahun' => 'required|integer|min:2020|max:2100'])['tahun'];

        $data = Karyawan::with(['pengajuan' => fn ($q) => $q->whereYear('tanggal_mulai', $tahun)])
            ->orderBy('nama_lengkap')
            ->get();

        return Pdf::loadView('laporan.pdf_rekap', compact('data', 'tahun'))
            ->download("rekap-cuti-$tahun.pdf");
    }
}
