@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head">
    <div><h4>Dashboard</h4><p class="page-description">Ringkasan administrasi cuti dan data karyawan.</p></div>
    <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">Tambah karyawan</a>
</div>

<div class="metric-grid"><div class="metric"><div class="metric-label">Karyawan terdaftar</div><div class="metric-value">{{ $totalKaryawan }}</div><p class="metric-note"><a href="{{ route('admin.karyawan.index') }}">Lihat data karyawan</a></p></div><div class="metric"><div class="metric-label">Menunggu keputusan</div><div class="metric-value">{{ $menunggu }}</div><p class="metric-note"><a href="{{ route('admin.pengajuan.index', ['status' => 'menunggu']) }}">Tinjau pengajuan</a></p></div><div class="metric"><div class="metric-label">Disetujui bulan ini</div><div class="metric-value">{{ $disetujuiBulanIni }}</div><p class="metric-note">Pengajuan final</p></div></div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center gap-2"><span>Pengajuan terbaru</span><a class="small fw-normal text-decoration-none" href="{{ route('admin.pengajuan.index') }}">Lihat semua</a></div>
    <div class="table-responsive"><table class="table">
        <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($terbaru as $p)
            <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
                <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                <td>{{ $p->jumlah_hari }}</td><td><span class="status {{ $p->badge }}">{{ $p->status_label }}</span></td></tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan cuti.</td></tr>
        @endforelse
        </tbody></table></div>
</div>
@endsection
