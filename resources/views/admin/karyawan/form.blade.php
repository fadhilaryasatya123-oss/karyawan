@extends('layouts.app')
@section('title', $karyawan ? 'Ubah Karyawan' : 'Tambah Karyawan')
@section('content')
<div class="page-head"><div><h4>{{ $karyawan ? 'Ubah' : 'Tambah' }} Karyawan</h4><p class="page-description">Lengkapi data identitas, akun, dan penempatan karyawan.</p></div></div>
<div class="card form-panel"><div class="card-body">
<form method="POST" action="{{ $karyawan ? route('admin.karyawan.update', $karyawan) : route('admin.karyawan.store') }}">
    @csrf @if($karyawan) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label" for="field-nama_lengkap-main">Nama Lengkap</label>
            <input id="field-nama_lengkap-main" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $karyawan->nama_lengkap ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="field-nik-main">NIK</label>
            <input id="field-nik-main" name="nik" class="form-control" value="{{ old('nik', $karyawan->nik ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="field-email-main">Email (untuk login)</label>
            <input type="email" id="field-email-main" name="email" class="form-control" value="{{ old('email', $karyawan->user->email ?? '') }}" required><div class="form-text">Gunakan email aktif yang dapat diverifikasi.</div></div>
        <div class="col-md-6"><label class="form-label" for="field-password-main">Password {{ $karyawan ? '(kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" id="field-password-main" name="password" class="form-control" {{ $karyawan ? '' : 'required' }}></div>
        <div class="col-md-4"><label class="form-label" for="field-role-main">Peran</label>
            <select id="field-role-main" name="role" class="form-select" required>
                @foreach(['karyawan' => 'Karyawan', 'manajer' => 'Manajer'] as $v => $l)
                    <option value="{{ $v }}" @selected(old('role', $karyawan->user->role ?? 'karyawan') === $v)>{{ $l }}</option>
                @endforeach
            </select></div>
        <div class="col-md-4"><label class="form-label" for="field-departemen-main">Departemen</label>
            <input id="field-departemen-main" name="departemen" class="form-control" value="{{ old('departemen', $karyawan->departemen ?? '') }}" required></div>
        <div class="col-md-4"><label class="form-label" for="field-jabatan-main">Jabatan</label>
            <input id="field-jabatan-main" name="jabatan" class="form-control" value="{{ old('jabatan', $karyawan->jabatan ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="field-atasan_id-main">Atasan (Manajer)</label>
            <select id="field-atasan_id-main" name="atasan_id" class="form-select">
                <option value="">- Tidak ada -</option>
                @foreach($manajers as $m)
                    <option value="{{ $m->id }}" @selected((string) old('atasan_id', $karyawan->atasan_id ?? '') === (string) $m->id)>{{ $m->name }}</option>
                @endforeach
            </select></div>
        <div class="col-md-6"><label class="form-label" for="field-tanggal_bergabung-main">Tanggal Bergabung</label>
            <input type="date" id="field-tanggal_bergabung-main" name="tanggal_bergabung" class="form-control" value="{{ old('tanggal_bergabung', optional($karyawan->tanggal_bergabung ?? null)->format('Y-m-d')) }}" required></div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form></div></div>
@endsection
