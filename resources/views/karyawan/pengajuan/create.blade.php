@extends('layouts.app')
@section('title', 'Ajukan Cuti')
@section('content')
<h4 class="mb-3">Form Pengajuan Cuti</h4>
<h5 class="section-title">Saldo cuti tahun {{ now()->year }}</h5>
<div class="row g-2 mb-3">
    @foreach($jenis as $j)
        @php $s = $saldo->get($j->id); @endphp
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">{{ $j->nama_jenis }}</div>
                <strong class="fs-3">{{ $s?->sisa ?? $j->kuota_hari }}</strong> hari
                <div class="small">{{ $s?->terpakai ?? 0 }} terpakai/dicadangkan dari {{ $s?->kuota ?? $j->kuota_hari }}</div>
                @if(!$s)<div class="small text-muted">Kuota awal (belum dibuat)</div>@endif
            </div></div>
        </div>
    @endforeach
</div>
<div class="alert alert-info">Ajukan minimal H-3 hari kalender, maksimal 5 hari kerja (Senin–Jumat). Saldo sesuai tahun tanggal mulai; jika lintas tahun, pisahkan pengajuan.</div>
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
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" min="{{ now()->addDays(3)->format('Y-m-d') }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" min="{{ now()->addDays(3)->format('Y-m-d') }}" required></div>
    </div>
    <div class="mb-3"><label class="form-label">Alasan</label>
        <textarea name="alasan" rows="3" class="form-control" required>{{ old('alasan') }}</textarea></div>
    <p class="text-muted small">Jumlah hari dihitung otomatis (hari kerja Senin-Jumat). Saldo dicadangkan setelah manajer menyetujui; konfirmasi kembali melalui Riwayat Cuti untuk menyelesaikan pengajuan.</p>
    <button class="btn btn-primary">Kirim Pengajuan</button>
    <a href="{{ route('karyawan.pengajuan.index') }}" class="btn btn-outline-secondary">Batal</a>
</form></div></div>
@endsection
