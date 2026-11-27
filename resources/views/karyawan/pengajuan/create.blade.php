@extends('layouts.app')
@section('title', 'Ajukan Cuti')
@section('content')
<h4 class="mb-3">Form Pengajuan Cuti</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('karyawan.pengajuan.store') }}">
    @csrf
    <div class="mb-3"><label class="form-label">Jenis Cuti</label>
        <select name="jenis_cuti_id" class="form-select" required>
            <option value="">- Pilih -</option>
            @foreach($jenis as $j)<option value="{{ $j->id }}" @selected(old('jenis_cuti_id') == $j->id)>{{ $j->nama_jenis }}</option>@endforeach
        </select></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" min="{{ now()->format('Y-m-d') }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" min="{{ now()->format('Y-m-d') }}" required></div>
    </div>
    <div class="mb-3"><label class="form-label">Alasan</label>
        <textarea name="alasan" rows="3" class="form-control" required>{{ old('alasan') }}</textarea></div>
    <p class="text-muted small">Jumlah hari dihitung otomatis (hari kerja Senin-Jumat). Saldo dipotong setelah pengajuan disetujui.</p>
    <button class="btn btn-primary">Kirim Pengajuan</button>
    <a href="{{ route('karyawan.pengajuan.index') }}" class="btn btn-outline-secondary">Batal</a>
</form></div></div>
@endsection
