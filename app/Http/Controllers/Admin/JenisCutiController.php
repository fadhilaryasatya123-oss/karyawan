<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use Illuminate\Http\Request;

class JenisCutiController extends Controller
{
    public function index()
    {
        return view('admin.jenis-cuti.index', ['data' => JenisCuti::orderBy('nama_jenis')->get()]);
    }

    public function create()
    {
        return view('admin.jenis-cuti.form', ['jenis' => null]);
    }

    public function store(Request $request)
    {
        JenisCuti::create($this->validated($request));

        return redirect()->route('admin.jenis-cuti.index')->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function edit(JenisCuti $jenisCuti)
    {
        return view('admin.jenis-cuti.form', ['jenis' => $jenisCuti]);
    }

    public function update(Request $request, JenisCuti $jenisCuti)
    {
        $jenisCuti->update($this->validated($request));

        return redirect()->route('admin.jenis-cuti.index')->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy(JenisCuti $jenisCuti)
    {
        if ($jenisCuti->pengajuan()->exists()) {
            return back()->with('error', 'Jenis cuti sudah dipakai pada pengajuan, tidak dapat dihapus.');
        }

        $jenisCuti->delete();

        return redirect()->route('admin.jenis-cuti.index')->with('success', 'Jenis cuti berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_jenis' => 'required|string|max:100',
            'kuota_hari' => 'required|integer|min:0|max:365',
            'keterangan' => 'nullable|string|max:1000',
        ]);
    }
}
