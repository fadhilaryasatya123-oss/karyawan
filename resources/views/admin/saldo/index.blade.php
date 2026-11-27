@extends('layouts.app')
@section('title', 'Kuota Cuti')
@section('content')
<h4 class="mb-3">Kuota dan Saldo Cuti</h4>
<div class="row g-2 mb-3">
    <div class="col-auto">
        <form method="GET" class="d-flex gap-2">
            <input type="number" name="tahun" value="{{ $tahun }}" class="form-control" style="width:110px">
            <button class="btn btn-outline-secondary">Tampilkan</button>
        </form>
    </div>
    <div class="col-auto">
        <form method="POST" action="{{ route('admin.saldo.generate') }}" onsubmit="return confirm('Buat saldo tahun {{ $tahun }} untuk semua karyawan?')">
            @csrf <input type="hidden" name="tahun" value="{{ $tahun }}">
            <button class="btn btn-primary">Buat Saldo Tahun {{ $tahun }}</button>
        </form>
    </div>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0 align-middle">
    <thead><tr><th>Karyawan</th><th>Jenis Cuti</th><th>Kuota</th><th>Terpakai</th><th>Sisa</th><th>Ubah Kuota</th></tr></thead>
    <tbody>
    @forelse($data as $s)
        <tr><td>{{ $s->karyawan->nama_lengkap }}</td><td>{{ $s->jenisCuti->nama_jenis }}</td>
            <td>{{ $s->kuota }}</td><td>{{ $s->terpakai }}</td><td><strong>{{ $s->sisa }}</strong></td>
            <td><form method="POST" action="{{ route('admin.saldo.update', $s) }}" class="d-flex gap-1">
                @csrf @method('PUT')
                <input type="number" name="kuota" value="{{ $s->kuota }}" min="{{ $s->terpakai }}" class="form-control form-control-sm" style="width:90px">
                <button class="btn btn-sm btn-warning">Simpan</button></form></td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted">Belum ada saldo untuk tahun {{ $tahun }}. Klik "Buat Saldo".</td></tr>
    @endforelse
    </tbody></table></div></div>
@endsection
