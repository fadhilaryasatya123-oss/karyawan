@extends('layouts.app')
@section('title', 'Riwayat Cuti')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Riwayat Pengajuan Cuti</h4>
    <a href="{{ route('karyawan.pengajuan.create') }}" class="btn btn-primary">Ajukan Cuti</a>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0 align-middle">
    <thead><tr><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Alasan</th><th>Status</th><th>Catatan Manajer</th><th></th></tr></thead>
    <tbody>
    @forelse($data as $p)
        <tr><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
            <td>{{ $p->alasan }}</td><td><span class="status {{ $p->badge }}">{{ ucfirst($p->status) }}</span></td>
            <td class="small">{{ $p->catatan_manajer ?? '-' }}</td>
            <td>@if($p->status === 'menunggu')
                <form method="POST" action="{{ route('karyawan.pengajuan.destroy', $p) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Batalkan</button></form>
                @endif</td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada pengajuan.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links() }}</div>
@endsection
