@extends('layouts.app')
@section('title', 'Riwayat Cuti')
@section('content')
<div class="page-head">
    <div><h4>Riwayat Pengajuan Cuti</h4><p class="page-description">Lihat status, catatan manajer, dan konfirmasi pengajuan Anda.</p></div>
    <a href="{{ route('karyawan.pengajuan.create') }}" class="btn btn-primary">Ajukan Cuti</a>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0 align-middle">
    <thead><tr><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Alasan</th><th>Status</th><th>Catatan Manajer</th><th></th></tr></thead>
    <tbody>
    @forelse($data as $p)
        <tr><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
            <td>{{ $p->alasan }}</td><td><span class="status {{ $p->badge }}">{{ $p->status_label }}</span></td>
            <td class="small">{{ $p->catatan_manajer ?? '-' }}
                @if($p->tanggal_konfirmasi)<div class="text-success">Dikonfirmasi {{ $p->tanggal_konfirmasi->format('d/m/Y H:i') }}</div>@endif
            </td>
            <td>@if($p->status === 'menunggu_konfirmasi')
                <form method="POST" action="{{ route('karyawan.pengajuan.konfirmasi', $p) }}" onsubmit="return confirm('Konfirmasi pelaksanaan cuti ini?')">
                    @csrf <button class="btn btn-sm btn-success">Konfirmasi Cuti</button>
                </form>
                @elseif($p->status === 'menunggu')
                <form method="POST" action="{{ route('karyawan.pengajuan.destroy', $p) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Batalkan</button></form>
                @endif</td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada pengajuan.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links('pagination::bootstrap-5') }}</div>
@endsection
