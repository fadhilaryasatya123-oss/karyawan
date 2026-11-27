@extends('layouts.app')
@section('title', 'Seluruh Pengajuan Cuti')
@section('content')
<div class="page-head"><div><h4>Seluruh Pengajuan Cuti</h4><p class="page-description">Pantau pengajuan dari seluruh karyawan berdasarkan status.</p></div></div>
<form method="GET" class="row g-2 toolbar">
    <div class="col-md-5 col-lg-4"><select name="status" aria-label="Filter status" class="form-select" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach(['menunggu', 'menunggu_konfirmasi', 'disetujui', 'ditolak'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ $s === 'menunggu_konfirmasi' ? 'Menunggu konfirmasi karyawan' : ucfirst($s) }}</option>@endforeach
    </select></div>
</form>
<div class="card"><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th><th>Keputusan</th></tr></thead>
    <tbody>
    @forelse($data as $p)
        <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
            <td><span class="status {{ $p->badge }}">{{ $p->status_label }}</span></td>
            <td class="small">{{ $p->manajer->name ?? '-' }} {{ $p->catatan_manajer ? '— ' . $p->catatan_manajer : '' }}</td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links('pagination::bootstrap-5') }}</div>
@endsection
