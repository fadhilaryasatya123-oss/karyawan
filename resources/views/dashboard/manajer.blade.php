@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head"><h4>Dashboard</h4></div>

<p class="summary">
    @if($menunggu > 0)
        <b>{{ $menunggu }}</b> pengajuan dari tim Anda menunggu keputusan.
    @else
        Tidak ada pengajuan yang menunggu keputusan.
    @endif
    Tim Anda terdiri dari <b>{{ $jumlahTim }}</b> orang.
</p>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span>Menunggu keputusan</span>
        <a href="{{ route('manajer.persetujuan.index') }}" class="small fw-normal">Lihat semua</a>
    </div>
    <div class="table-responsive"><table class="table align-middle">
        <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th></th></tr></thead>
        <tbody>
        @forelse($daftar as $p)
            <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
                <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
                <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('manajer.persetujuan.show', $p) }}">Tinjau</a></td></tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pengajuan menunggu.</td></tr>
        @endforelse
        </tbody></table></div>
</div>
@endsection
