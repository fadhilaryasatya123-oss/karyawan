@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head">
    <h4>Dashboard</h4>
    <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">Tambah karyawan</a>
</div>

<p class="summary">
    <b>{{ $totalKaryawan }}</b> karyawan terdaftar.
    @if($menunggu > 0)
        <a href="{{ route('admin.pengajuan.index', ['status' => 'menunggu']) }}"><b>{{ $menunggu }}</b> pengajuan</a> belum diputuskan manajer.
    @else
        Semua pengajuan sudah diputuskan.
    @endif
    Bulan ini <b>{{ $disetujuiBulanIni }}</b> pengajuan disetujui.
</p>

<div class="card">
    <div class="card-header">Pengajuan terbaru</div>
    <div class="table-responsive"><table class="table">
        <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($terbaru as $p)
            <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
                <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                <td>{{ $p->jumlah_hari }}</td><td><span class="status {{ $p->badge }}">{{ ucfirst($p->status) }}</span></td></tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan cuti.</td></tr>
        @endforelse
        </tbody></table></div>
</div>
@endsection
