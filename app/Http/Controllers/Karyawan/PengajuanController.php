<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = $this->karyawan($request);

        $data = $karyawan->pengajuan()->with(['jenisCuti', 'manajer'])->latest()->paginate(10);

        return view('karyawan.pengajuan.index', compact('data'));
    }

    public function create(Request $request)
    {
        $this->karyawan($request);

        return view('karyawan.pengajuan.create', ['jenis' => JenisCuti::orderBy('nama_jenis')->get()]);
    }

    public function store(Request $request)
    {
        $karyawan = $this->karyawan($request);

        $d = $request->validate([
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
        ]);

        $mulai = Carbon::parse($d['tanggal_mulai']);
        $selesai = Carbon::parse($d['tanggal_selesai']);
        $hari = PengajuanCuti::hitungHariKerja($mulai, $selesai);

        if ($hari < 1) {
            return back()->withInput()->with('error', 'Rentang tanggal tidak mengandung hari kerja (Senin-Jumat).');
        }

        $bentrok = $karyawan->pengajuan()
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->where('tanggal_mulai', '<=', $selesai->toDateString())
            ->where('tanggal_selesai', '>=', $mulai->toDateString())
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Tanggal bertabrakan dengan pengajuan cuti lain yang masih aktif.');
        }

        $jenis = JenisCuti::findOrFail($d['jenis_cuti_id']);
        $saldo = SaldoCuti::firstOrCreate(
            ['karyawan_id' => $karyawan->id, 'jenis_cuti_id' => $jenis->id, 'tahun' => $mulai->year],
            ['kuota' => $jenis->kuota_hari, 'terpakai' => 0, 'sisa' => $jenis->kuota_hari]
        );

        if ($saldo->sisa < $hari) {
            return back()->withInput()->with('error', "Saldo tidak cukup. Sisa {$saldo->sisa} hari, diajukan $hari hari.");
        }

        $karyawan->pengajuan()->create([
            'jenis_cuti_id' => $jenis->id,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'jumlah_hari' => $hari,
            'alasan' => $d['alasan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('karyawan.pengajuan.index')->with('success', "Pengajuan cuti $hari hari kerja berhasil dikirim.");
    }

    public function destroy(Request $request, PengajuanCuti $pengajuan)
    {
        $karyawan = $this->karyawan($request);

        abort_unless($pengajuan->karyawan_id === $karyawan->id, 403);

        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang dapat dibatalkan.');
        }

        $pengajuan->delete();

        return back()->with('success', 'Pengajuan dibatalkan.');
    }

    private function karyawan(Request $request)
    {
        $karyawan = $request->user()->karyawan;
        abort_if(! $karyawan, 403, 'Akun Anda belum terhubung dengan data karyawan. Hubungi Admin.');

        return $karyawan;
    }
}
