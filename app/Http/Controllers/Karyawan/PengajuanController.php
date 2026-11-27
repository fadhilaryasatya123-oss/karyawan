<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $karyawan = $this->karyawan($request);
        $jenis = JenisCuti::orderBy('nama_jenis')->get();
        $saldo = SaldoCuti::where('karyawan_id', $karyawan->id)
            ->where('tahun', now()->year)->get()->keyBy('jenis_cuti_id');

        return view('karyawan.pengajuan.create', compact('jenis', 'saldo'));
    }

    public function store(Request $request)
    {
        $karyawan = $this->karyawan($request);

        $d = $request->validate([
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_mulai' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->addDays(3)->toDateString()],
            'tanggal_selesai' => 'required|date_format:Y-m-d|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
        ], [
            'tanggal_mulai.after_or_equal' => 'Pengajuan cuti wajib dilakukan minimal 3 hari kalender sebelum tanggal mulai.',
        ]);

        $mulai = Carbon::parse($d['tanggal_mulai']);
        $selesai = Carbon::parse($d['tanggal_selesai']);
        if ($mulai->year !== $selesai->year) {
            return back()->withInput()->with('error', 'Pengajuan lintas tahun harus dipisah karena saldo dihitung per tahun.');
        }

        $hari = PengajuanCuti::hitungHariKerja($mulai, $selesai);
        if ($hari > 5) {
            return back()->withInput()->with('error', 'Maksimal 5 hari kerja dalam satu pengajuan cuti.');
        }

        if ($hari < 1) {
            return back()->withInput()->with('error', 'Rentang tanggal tidak mengandung hari kerja (Senin-Jumat).');
        }

        return DB::transaction(function () use ($karyawan, $d, $mulai, $selesai, $hari) {
            // Serialisasi pengajuan baru milik karyawan yang sama.
            \App\Models\Karyawan::whereKey($karyawan->id)->lockForUpdate()->firstOrFail();
            $bentrok = $karyawan->pengajuan()
                ->whereIn('status', ['menunggu', 'menunggu_konfirmasi', 'disetujui'])
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
        });
    }

    public function konfirmasi(Request $request, PengajuanCuti $pengajuan)
    {
        $karyawan = $this->karyawan($request);
        abort_unless($pengajuan->karyawan_id === $karyawan->id, 403);

        $berhasil = DB::transaction(function () use ($pengajuan) {
            $p = PengajuanCuti::lockForUpdate()->findOrFail($pengajuan->id);
            if ($p->status !== 'menunggu_konfirmasi') {
                return false;
            }
            // Saldo telah dicadangkan saat manajer menyetujui; jangan potong lagi.
            $p->update(['status' => 'disetujui', 'tanggal_konfirmasi' => now()]);
            return true;
        });

        return back()->with($berhasil ? 'success' : 'error', $berhasil
            ? 'Cuti berhasil dikonfirmasi. Pengajuan telah final.'
            : 'Pengajuan tidak menunggu konfirmasi atau sudah dikonfirmasi.');
    }

    public function destroy(Request $request, PengajuanCuti $pengajuan)
    {
        $karyawan = $this->karyawan($request);

        abort_unless($pengajuan->karyawan_id === $karyawan->id, 403);

        if (! PengajuanCuti::whereKey($pengajuan->id)->where('status', 'menunggu')->delete()) {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang dapat dibatalkan.');
        }

        return back()->with('success', 'Pengajuan dibatalkan.');
    }

    private function karyawan(Request $request)
    {
        $karyawan = $request->user()->karyawan;
        abort_if(! $karyawan, 403, 'Akun Anda belum terhubung dengan data karyawan. Hubungi Admin.');

        return $karyawan;
    }
}
