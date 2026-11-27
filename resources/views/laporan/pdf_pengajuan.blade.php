<!DOCTYPE html><html><head><meta charset="utf-8"><style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    h2, h4 { text-align: center; margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 14px; }
    th, td { border: 1px solid #000; padding: 4px 6px; }
    th { background: #e5e5e5; }
</style></head><body>
<h2>LAPORAN PENGAJUAN CUTI</h2>
<h4>PT. Ciomas Adisatwa Unit Bogor</h4>
<p style="text-align:center">Periode {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
<table>
    <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Jenis Cuti</th><th>Mulai</th><th>Selesai</th><th>Hari</th><th>Status</th></tr></thead>
    <tbody>
    @forelse($data as $i => $p)
        <tr><td>{{ $i + 1 }}</td><td>{{ $p->karyawan->nik }}</td><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }}</td><td>{{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td><td>{{ $p->status_label }}</td></tr>
    @empty
        <tr><td colspan="8" style="text-align:center">Tidak ada data pada periode ini.</td></tr>
    @endforelse
    </tbody>
</table>
<p style="margin-top:20px">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body></html>
