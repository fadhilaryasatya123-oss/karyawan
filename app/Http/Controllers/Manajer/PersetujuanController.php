<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->milikTim($request)
            ->with(['karyawan', 'jenisCuti'])
            ->where('status', 'menunggu')
            ->oldest()
            ->paginate(10);

        return view('manajer.persetujuan.index', compact('data'));
    }

    public function show(Request $request, PengajuanCuti $pengajuan)
    {
        $this->pastikanTim($request, $pengajuan);

        $saldo = SaldoCuti::where([
            'karyawan_id' => $pengajuan->karyawan_id,
            'jenis_cuti_id' => $pengajuan->jenis_cuti_id,
            'tahun' => $pengajuan->tanggal_mulai->year,
        ])->first();

        return view('manajer.persetujuan.show', ['p' => $pengajuan->load(['karyawan', 'jenisCuti']), 'saldo' => $saldo]);
    }

    public function keputusan(Request $request, PengajuanCuti $pengajuan)
    {
        $this->pastikanTim($request, $pengajuan);

        $d = $request->validate([
            'keputusan' => 'required|in:disetujui,ditolak',
            'catatan_manajer' => 'required_if:keputusan,ditolak|nullable|string|max:1000',
        ], [
            'catatan_manajer.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($pengajuan, $d, $request) {
                $p = PengajuanCuti::lockForUpdate()->findOrFail($pengajuan->id);

                if ($p->status !== 'menunggu') {
                    throw new \RuntimeException('Pengajuan ini sudah diproses.');
                }

                if ($d['keputusan'] === 'disetujui') {
                    $saldo = SaldoCuti::lockForUpdate()->where([
                        'karyawan_id' => $p->karyawan_id,
                        'jenis_cuti_id' => $p->jenis_cuti_id,
                        'tahun' => $p->tanggal_mulai->year,
                    ])->first();

                    if (! $saldo || $saldo->sisa < $p->jumlah_hari) {
                        throw new \RuntimeException('Saldo cuti karyawan tidak mencukupi.');
                    }

                    // Cadangkan saldo saat persetujuan manajer agar tidak dapat dipakai dua kali.
                    $saldo->terpakai += $p->jumlah_hari;
                    $saldo->save();
                }

                $p->update([
                    'status' => $d['keputusan'] === 'disetujui' ? 'menunggu_konfirmasi' : 'ditolak',
                    'manajer_id' => $request->user()->id,
                    'catatan_manajer' => $d['catatan_manajer'] ?? null,
                    'tanggal_keputusan' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('manajer.persetujuan.index')->with('success', 'Keputusan berhasil disimpan.');
    }

    public function riwayat(Request $request)
    {
        $data = $this->milikTim($request)
            ->with(['karyawan', 'jenisCuti'])
            ->whereIn('status', ['disetujui', 'ditolak', 'menunggu_konfirmasi'])
            ->latest('tanggal_keputusan')
            ->paginate(10);

        return view('manajer.persetujuan.riwayat', compact('data'));
    }

    private function milikTim(Request $request)
    {
        $uid = $request->user()->id;

        return PengajuanCuti::whereHas('karyawan', fn ($q) => $q->where('atasan_id', $uid));
    }

    private function pastikanTim(Request $request, PengajuanCuti $pengajuan): void
    {
        abort_unless($pengajuan->karyawan->atasan_id === $request->user()->id, 403);
    }
}
