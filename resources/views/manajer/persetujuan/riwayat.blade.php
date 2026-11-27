@extends('layouts.app')
@section('title', 'Riwayat Keputusan')
@section('content')
<h4 class="mb-3">Riwayat Keputusan</h4>
<div class="card"><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th><th>Diputuskan</th><th>Catatan</th></tr></thead>
    <tbody>
    @forelse($data as $p)
        <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
            <td><span class="status {{ $p->badge }}">{{ ucfirst($p->status) }}</span></td>
            <td>{{ optional($p->tanggal_keputusan)->format('d/m/Y H:i') }}</td><td class="small">{{ $p->catatan_manajer ?? '-' }}</td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada keputusan.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links() }}</div>
@endsection
