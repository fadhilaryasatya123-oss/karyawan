@extends('layouts.app')
@section('title', 'Seluruh Pengajuan Cuti')
@section('content')
<h4 class="mb-3">Seluruh Pengajuan Cuti</h4>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><select name="status" class="form-select" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach(['menunggu', 'disetujui', 'ditolak'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>@endforeach
    </select></div>
</form>
<div class="card"><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Status</th><th>Keputusan</th></tr></thead>
    <tbody>
    @forelse($data as $p)
        <tr><td>{{ $p->karyawan->nama_lengkap }}</td><td>{{ $p->jenisCuti->nama_jenis }}</td>
            <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $p->jumlah_hari }}</td>
            <td><span class="status {{ $p->badge }}">{{ ucfirst($p->status) }}</span></td>
            <td class="small">{{ $p->manajer->name ?? '-' }} {{ $p->catatan_manajer ? '— ' . $p->catatan_manajer : '' }}</td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links() }}</div>
@endsection
