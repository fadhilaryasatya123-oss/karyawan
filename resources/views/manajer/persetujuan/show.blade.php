@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')
<div class="page-head"><div><h4>Detail Pengajuan Cuti</h4><p class="page-description">Periksa informasi pengajuan sebelum memberikan keputusan.</p></div></div>
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0 detail-list">
        <dt class="col-sm-3">Karyawan</dt><dd class="col-sm-9">{{ $p->karyawan->nama_lengkap }} ({{ $p->karyawan->nik }})</dd>
        <dt class="col-sm-3">Jenis Cuti</dt><dd class="col-sm-9">{{ $p->jenisCuti->nama_jenis }}</dd>
        <dt class="col-sm-3">Tanggal</dt><dd class="col-sm-9">{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }} ({{ $p->jumlah_hari }} hari kerja)</dd>
        <dt class="col-sm-3">Alasan</dt><dd class="col-sm-9">{{ $p->alasan }}</dd>
        <dt class="col-sm-3">Sisa Saldo</dt><dd class="col-sm-9">@if($saldo){{ $saldo->sisa }} hari dari {{ $saldo->kuota }} @include('partials.tally', ['s' => $saldo])@else Belum ada saldo @endif</dd>
        <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><span class="status {{ $p->badge }}">{{ $p->status_label }}</span></dd>
    </dl>
</div></div>

@if($p->status === 'menunggu')
<div class="card form-panel"><div class="card-header">Keputusan manajer</div><div class="card-body">
    <form method="POST" action="{{ route('manajer.persetujuan.keputusan', $p) }}">
        @csrf
        <div class="mb-3"><label class="form-label" for="field-catatan_manajer-main">Catatan / alasan (wajib jika menolak)</label>
            <textarea id="field-catatan_manajer-main" name="catatan_manajer" rows="3" class="form-control">{{ old('catatan_manajer') }}</textarea></div>
        <button name="keputusan" value="disetujui" class="btn btn-success">Setujui</button>
        <button name="keputusan" value="ditolak" class="btn btn-danger">Tolak</button>
        <a href="{{ route('manajer.persetujuan.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </form>
</div></div>
@else
    <a href="{{ route('manajer.persetujuan.riwayat') }}" class="btn btn-outline-secondary">Kembali</a>
@endif
@endsection
