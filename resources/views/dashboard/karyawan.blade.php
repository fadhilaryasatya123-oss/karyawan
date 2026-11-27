@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head">
    <h4>Halo, {{ strtok(auth()->user()->name, ' ') }}</h4>
    @if($karyawan)<a href="{{ route('karyawan.pengajuan.create') }}" class="btn btn-primary">Ajukan cuti</a>@endif
</div>

@if(!$karyawan)
    <div class="alert alert-warning">Akun Anda belum terhubung dengan data karyawan. Minta Admin SDM untuk melengkapinya.</div>
@else
    <h5 class="section-title" style="margin-top:.25rem">Sisa cuti tahun {{ now()->year }}</h5>
    @if($saldo->isEmpty())
        <div class="alert alert-info">Saldo cuti tahun ini belum dibuat. Minta Admin SDM untuk membuatnya.</div>
    @else
        <div class="ledger">
            @foreach($saldo as $s)
                <div class="ledger-row">
                    <div class="ledger-name">{{ $s->jenisCuti->nama_jenis }}<small>{{ $s->terpakai }} terpakai dari {{ $s->kuota }} hari</small></div>
                    @include('partials.tally', ['s' => $s])
                    <div class="ledger-num"><b>{{ $s->sisa }}</b>hari lagi</div>
                </div>
            @endforeach
        </div>
    @endif

    <h5 class="section-title">Pengajuan terakhir</h5>
    <div class="card">
        <div class="table-responsive"><table class="table">
            <thead><tr><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($terbaru as $p)
                <tr><td>{{ $p->jenisCuti->nama_jenis }}</td>
                    <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                    <td>{{ $p->jumlah_hari }}</td><td><span class="status {{ $p->badge }}">{{ ucfirst($p->status) }}</span></td></tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada pengajuan. Klik "Ajukan cuti" untuk mulai.</td></tr>
            @endforelse
            </tbody></table></div>
    </div>
@endif
@endsection
