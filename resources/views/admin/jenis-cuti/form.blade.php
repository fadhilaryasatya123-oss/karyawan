@extends('layouts.app')
@section('title', $jenis ? 'Ubah Jenis Cuti' : 'Tambah Jenis Cuti')
@section('content')
<h4 class="mb-3">{{ $jenis ? 'Ubah' : 'Tambah' }} Jenis Cuti</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $jenis ? route('admin.jenis-cuti.update', $jenis) : route('admin.jenis-cuti.store') }}">
    @csrf @if($jenis) @method('PUT') @endif
    <div class="mb-3"><label class="form-label">Nama Jenis Cuti</label>
        <input name="nama_jenis" class="form-control" value="{{ old('nama_jenis', $jenis->nama_jenis ?? '') }}" required></div>
    <div class="mb-3"><label class="form-label">Kuota (hari kerja per tahun)</label>
        <input type="number" min="0" name="kuota_hari" class="form-control" value="{{ old('kuota_hari', $jenis->kuota_hari ?? 0) }}" required></div>
    <div class="mb-3"><label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $jenis->keterangan ?? '') }}</textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.jenis-cuti.index') }}" class="btn btn-outline-secondary">Batal</a>
</form></div></div>
@endsection
