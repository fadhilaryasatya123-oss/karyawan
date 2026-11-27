@extends('layouts.app')
@section('title', 'Pengajuan Menunggu')
@section('content')
<h4 class="mb-3">Pengajuan Cuti Menunggu Keputusan</h4>
<div class="card"><div class="table-responsive"><table class="table mb-0 align-middle">
    <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Diajukan</th><th></th></tr></thead>
    <tbody>
    @forelse($data as $p)
        <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('manajer.persetujuan.show', $p) }}" class="btn btn-sm btn-primary">Tinjau</a></td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted">Tidak ada pengajuan menunggu.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links() }}</div>
@endsection
