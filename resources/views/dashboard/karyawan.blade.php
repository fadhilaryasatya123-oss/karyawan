@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head">
    <div><h4>Halo, {{ strtok(auth()->user()->name, ' ') }}</h4><p class="page-description">Pantau saldo dan status pengajuan cuti Anda.</p></div>
    @if($karyawan)<a href="{{ route('karyawan.pengajuan.create') }}" class="btn btn-primary">Ajukan cuti</a>@endif
</div>

@if(!$karyawan)
    <div class="alert alert-warning">Akun Anda belum terhubung dengan data karyawan. Minta Admin SDM untuk melengkapinya.</div>
@else
    <h5 class="section-title" style="margin-top:.25rem">Sisa cuti tahun {{ now()->year }}</h5>
    @if($jenis->isEmpty())
        <div class="alert alert-info">Jenis cuti belum tersedia. Hubungi Admin SDM.</div>
    @else
        <div class="row g-3 mb-3">
            @foreach($jenis as $j)
                @php $s = $saldo->firstWhere('jenis_cuti_id', $j->id); @endphp
                <div class="col-sm-6 col-xl-3">
                    <div class="card balance-card"><div class="card-body">
                        <div class="balance-label">{{ $j->nama_jenis }}</div>
                        <div class="balance-number"><strong>{{ $s?->sisa ?? $j->kuota_hari }}</strong><span>hari tersisa</span></div>
                        <div class="balance-meta">{{ $s?->terpakai ?? 0 }} terpakai/dicadangkan dari {{ $s?->kuota ?? $j->kuota_hari }} hari</div>
                        @if($s) @include('partials.tally', ['s' => $s]) @else <div class="small text-muted">Kuota awal (belum dibuat)</div> @endif
                    </div></div>
                </div>
            @endforeach
        </div>
    @endif

    @if($karyawan->pengajuan()->where('status', 'menunggu_konfirmasi')->exists())
        <div class="alert alert-info">Manajer telah menyetujui cuti Anda. <a href="{{ route('karyawan.pengajuan.index') }}">Konfirmasi melalui Riwayat Cuti</a> untuk menyelesaikan pengajuan.</div>
    @endif
    <div class="section-head"><h5 class="section-title">Pengajuan terakhir</h5><a href="{{ route('karyawan.pengajuan.index') }}">Lihat riwayat</a></div>
    <div class="card">
        <div class="table-responsive"><table class="table">
            <thead><tr><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($terbaru as $p)
                <tr><td>{{ $p->jenisCuti->nama_jenis }}</td>
                    <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                    <td>{{ $p->jumlah_hari }}</td><td><span class="status {{ $p->badge }}">{{ $p->status_label }}</span></td></tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada pengajuan. Klik "Ajukan cuti" untuk mulai.</td></tr>
            @endforelse
            </tbody></table></div>
    </div>
@endif
@endsection
