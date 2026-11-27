<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\Karyawan;
use App\Models\SaldoCuti;
use Illuminate\Http\Request;

class SaldoCutiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) $request->input('tahun', now()->year);

        $data = SaldoCuti::with(['karyawan', 'jenisCuti'])
            ->where('tahun', $tahun)
            ->get()
            ->sortBy(fn ($s) => $s->karyawan->nama_lengkap . $s->jenisCuti->nama_jenis)
            ->values();

        return view('admin.saldo.index', compact('data', 'tahun'));
    }

    /** Buat saldo semua karyawan x jenis cuti untuk tahun terpilih (yang belum ada saja). */
    public function generate(Request $request)
    {
        $tahun = (int) $request->validate(['tahun' => 'required|integer|min:2020|max:2100'])['tahun'];
        $dibuat = 0;

        foreach (Karyawan::all() as $k) {
            foreach (JenisCuti::all() as $j) {
                $saldo = SaldoCuti::firstOrCreate(
                    ['karyawan_id' => $k->id, 'jenis_cuti_id' => $j->id, 'tahun' => $tahun],
                    ['kuota' => $j->kuota_hari, 'terpakai' => 0, 'sisa' => $j->kuota_hari]
                );
                if ($saldo->wasRecentlyCreated) {
                    $dibuat++;
                }
            }
        }

        return redirect()->route('admin.saldo.index', ['tahun' => $tahun])
            ->with('success', "Saldo cuti tahun $tahun dibuat: $dibuat baris baru.");
    }

    public function update(Request $request, SaldoCuti $saldo)
    {
        $d = $request->validate(['kuota' => 'required|integer|min:' . $saldo->terpakai . '|max:365']);
        $saldo->update(['kuota' => $d['kuota']]);

        return back()->with('success', 'Kuota cuti berhasil diperbarui.');
    }
}
