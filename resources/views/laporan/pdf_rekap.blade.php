<!DOCTYPE html><html><head><meta charset="utf-8"><style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    h2, h4 { text-align: center; margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 14px; }
    th, td { border: 1px solid #000; padding: 4px 6px; }
    th { background: #e5e5e5; }
</style></head><body>
<h2>REKAP CUTI KESELURUHAN</h2>
<h4>PT. Ciomas Adisatwa Unit Bogor - Tahun {{ $tahun }}</h4>
<table>
    <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Departemen</th><th>Menunggu</th><th>Disetujui</th><th>Ditolak</th><th>Total Hari Disetujui</th></tr></thead>
    <tbody>
    @foreach($data as $i => $k)
        <tr><td>{{ $i + 1 }}</td><td>{{ $k->nik }}</td><td>{{ $k->nama_lengkap }}</td><td>{{ $k->departemen }}</td>
            <td>{{ $k->pengajuan->where('status', 'menunggu')->count() }}</td>
            <td>{{ $k->pengajuan->where('status', 'disetujui')->count() }}</td>
            <td>{{ $k->pengajuan->where('status', 'ditolak')->count() }}</td>
            <td>{{ $k->pengajuan->where('status', 'disetujui')->sum('jumlah_hari') }}</td></tr>
    @endforeach
    </tbody>
</table>
<p style="margin-top:20px">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body></html>
