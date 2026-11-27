@extends('layouts.app')
@section('title', 'Ajukan Cuti')
@section('content')
<div class="page-head"><div><h4>Ajukan cuti</h4><p class="page-description">Periksa sisa saldo sebelum mengisi tanggal dan alasan pengajuan.</p></div><a href="{{ route('karyawan.pengajuan.index') }}" class="btn btn-outline-secondary">Riwayat cuti</a></div>
<h5 class="section-title">Saldo cuti tahun {{ now()->year }}</h5>
<div class="row g-2 mb-3">
    @foreach($jenis as $j)
        @php $s = $saldo->get($j->id); @endphp
        <div class="col-sm-6 col-lg-3">
            <div class="card balance-card"><div class="card-body">
                <div class="balance-label">{{ $j->nama_jenis }}</div>
                <div class="balance-number"><strong>{{ $s?->sisa ?? $j->kuota_hari }}</strong><span>hari tersisa</span></div>
                <div class="balance-meta">{{ $s?->terpakai ?? 0 }} terpakai/dicadangkan dari {{ $s?->kuota ?? $j->kuota_hari }}</div>
                @if($s) @include('partials.tally', ['s' => $s]) @else <div class="small text-muted">Kuota awal (belum dibuat)</div> @endif
            </div></div>
        </div>
    @endforeach
</div>
<div class="alert alert-info">Ajukan minimal H-3 hari kalender, maksimal 5 hari kerja (Senin–Jumat). Saldo sesuai tahun tanggal mulai; jika lintas tahun, pisahkan pengajuan.</div>
<div class="card form-panel"><div class="card-header">Detail pengajuan</div><div class="card-body">
<form method="POST" action="{{ route('karyawan.pengajuan.store') }}">
    @csrf
    <div class="mb-3"><label class="form-label" for="field-jenis_cuti_id-main">Jenis Cuti</label>
        <select id="field-jenis_cuti_id-main" name="jenis_cuti_id" class="form-select" required>
            <option value="">- Pilih -</option>
            @foreach($jenis as $j)<option value="{{ $j->id }}" @selected(old('jenis_cuti_id') == $j->id)>{{ $j->nama_jenis }}</option>@endforeach
        </select></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label" for="field-tanggal_mulai-main">Tanggal Mulai</label>
            <input type="date" id="field-tanggal_mulai-main" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" min="{{ now()->addDays(3)->format('Y-m-d') }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label" for="field-tanggal_selesai-main">Tanggal Selesai</label>
            <input type="date" id="field-tanggal_selesai-main" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" min="{{ now()->addDays(3)->format('Y-m-d') }}" required></div>
    </div>
    <div class="mb-3"><label class="form-label" for="field-alasan-main">Alasan</label>
        <textarea id="field-alasan-main" name="alasan" rows="3" class="form-control" required>{{ old('alasan') }}</textarea></div>
    <p class="text-muted small">Jumlah hari dihitung otomatis (hari kerja Senin-Jumat). Saldo dicadangkan setelah manajer menyetujui; konfirmasi kembali melalui Riwayat Cuti untuk menyelesaikan pengajuan.</p>
    <div class="form-actions"><button class="btn btn-primary">Kirim Pengajuan</button>
    <a href="{{ route('karyawan.pengajuan.index') }}" class="btn btn-outline-secondary">Batal</a></div>
</form></div></div>
@endsection
