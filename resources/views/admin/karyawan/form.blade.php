@extends('layouts.app')
@section('title', $karyawan ? 'Ubah Karyawan' : 'Tambah Karyawan')
@section('content')
<h4 class="mb-3">{{ $karyawan ? 'Ubah' : 'Tambah' }} Karyawan</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $karyawan ? route('admin.karyawan.update', $karyawan) : route('admin.karyawan.store') }}">
    @csrf @if($karyawan) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama Lengkap</label>
            <input name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $karyawan->nama_lengkap ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">NIK</label>
            <input name="nik" class="form-control" value="{{ old('nik', $karyawan->nik ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Email (untuk login)</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $karyawan->user->email ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Password {{ $karyawan ? '(kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" name="password" class="form-control" {{ $karyawan ? '' : 'required' }}></div>
        <div class="col-md-4"><label class="form-label">Peran</label>
            <select name="role" class="form-select" required>
                @foreach(['karyawan' => 'Karyawan', 'manajer' => 'Manajer'] as $v => $l)
                    <option value="{{ $v }}" @selected(old('role', $karyawan->user->role ?? 'karyawan') === $v)>{{ $l }}</option>
                @endforeach
            </select></div>
        <div class="col-md-4"><label class="form-label">Departemen</label>
            <input name="departemen" class="form-control" value="{{ old('departemen', $karyawan->departemen ?? '') }}" required></div>
        <div class="col-md-4"><label class="form-label">Jabatan</label>
            <input name="jabatan" class="form-control" value="{{ old('jabatan', $karyawan->jabatan ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Atasan (Manajer)</label>
            <select name="atasan_id" class="form-select">
                <option value="">- Tidak ada -</option>
                @foreach($manajers as $m)
                    <option value="{{ $m->id }}" @selected((string) old('atasan_id', $karyawan->atasan_id ?? '') === (string) $m->id)>{{ $m->name }}</option>
                @endforeach
            </select></div>
        <div class="col-md-6"><label class="form-label">Tanggal Bergabung</label>
            <input type="date" name="tanggal_bergabung" class="form-control" value="{{ old('tanggal_bergabung', optional($karyawan->tanggal_bergabung ?? null)->format('Y-m-d')) }}" required></div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form></div></div>
@endsection
