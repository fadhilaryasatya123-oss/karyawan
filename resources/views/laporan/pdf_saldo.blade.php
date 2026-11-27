<!DOCTYPE html><html><head><meta charset="utf-8"><style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    h2, h4 { text-align: center; margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 14px; }
    th, td { border: 1px solid #000; padding: 4px 6px; }
    th { background: #e5e5e5; }
</style></head><body>
<h2>LAPORAN SALDO CUTI KARYAWAN</h2>
<h4>PT. Ciomas Adisatwa Unit Bogor - Tahun {{ $tahun }}</h4>
<table>
    <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Jenis Cuti</th><th>Kuota</th><th>Terpakai</th><th>Sisa</th></tr></thead>
    <tbody>
    @forelse($data as $i => $s)
        <tr><td>{{ $i + 1 }}</td><td>{{ $s->karyawan->nik }}</td><td>{{ $s->karyawan->nama_lengkap }}</td><td>{{ $s->jenisCuti->nama_jenis }}</td>
            <td>{{ $s->kuota }}</td><td>{{ $s->terpakai }}</td><td>{{ $s->sisa }}</td></tr>
    @empty
        <tr><td colspan="7" style="text-align:center">Belum ada saldo untuk tahun ini.</td></tr>
    @endforelse
    </tbody>
</table>
<p style="margin-top:20px">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body></html>
