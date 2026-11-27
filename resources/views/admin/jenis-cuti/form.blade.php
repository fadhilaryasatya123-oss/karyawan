@extends('layouts.app')
@section('title', $jenis ? 'Ubah Jenis Cuti' : 'Tambah Jenis Cuti')
@section('content')
<div class="page-head"><div><h4>{{ $jenis ? 'Ubah' : 'Tambah' }} Jenis Cuti</h4><p class="page-description">Tentukan nama jenis cuti dan kuota hari kerja per tahun.</p></div></div>
<div class="card form-panel"><div class="card-body">
<form method="POST" action="{{ $jenis ? route('admin.jenis-cuti.update', $jenis) : route('admin.jenis-cuti.store') }}">
    @csrf @if($jenis) @method('PUT') @endif
    <div class="mb-3"><label class="form-label" for="field-nama_jenis-main">Nama Jenis Cuti</label>
        <input id="field-nama_jenis-main" name="nama_jenis" class="form-control" value="{{ old('nama_jenis', $jenis->nama_jenis ?? '') }}" required></div>
    <div class="mb-3"><label class="form-label" for="field-kuota_hari-main">Kuota (hari kerja per tahun)</label>
        <input type="number" min="0" id="field-kuota_hari-main" name="kuota_hari" class="form-control" value="{{ old('kuota_hari', $jenis->kuota_hari ?? 0) }}" required></div>
    <div class="mb-3"><label class="form-label" for="field-keterangan-main">Keterangan</label>
        <textarea id="field-keterangan-main" name="keterangan" class="form-control" rows="3">{{ old('keterangan', $jenis->keterangan ?? '') }}</textarea></div>
    <div class="form-actions"><button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.jenis-cuti.index') }}" class="btn btn-outline-secondary">Batal</a></div>
</form></div></div>
@endsection
