@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<h4 class="mb-3">Laporan Cuti (PDF)</h4>
<div class="row g-3">
    <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h6>Laporan Pengajuan Cuti per Periode</h6>
        <form method="GET" action="{{ route('admin.laporan.pengajuan') }}">
            <label class="form-label small">Dari</label><input type="date" name="dari" class="form-control mb-2" value="{{ now()->startOfYear()->format('Y-m-d') }}" required>
            <label class="form-label small">Sampai</label><input type="date" name="sampai" class="form-control mb-3" value="{{ now()->endOfYear()->format('Y-m-d') }}" required>
            <button class="btn btn-primary">Unduh PDF</button>
        </form></div></div></div>
    <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h6>Laporan Saldo Cuti per Karyawan</h6>
        <form method="GET" action="{{ route('admin.laporan.saldo') }}">
            <label class="form-label small">Tahun</label><input type="number" name="tahun" class="form-control mb-3" value="{{ now()->year }}" required>
            <button class="btn btn-primary">Unduh PDF</button>
        </form></div></div></div>
    <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h6>Rekap Cuti Keseluruhan</h6>
        <form method="GET" action="{{ route('admin.laporan.rekap') }}">
            <label class="form-label small">Tahun</label><input type="number" name="tahun" class="form-control mb-3" value="{{ now()->year }}" required>
            <button class="btn btn-primary">Unduh PDF</button>
        </form></div></div></div>
</div>
@endsection
