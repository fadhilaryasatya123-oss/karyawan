@extends('layouts.app')
@section('title', 'Jenis Cuti')
@section('content')
<div class="page-head">
    <div><h4>Jenis Cuti</h4><p class="page-description">Kelola kategori cuti dan kuota tahunan.</p></div>
    <a href="{{ route('admin.jenis-cuti.create') }}" class="btn btn-primary">Tambah Jenis Cuti</a>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Nama Jenis</th><th>Kuota (hari/tahun)</th><th>Keterangan</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($data as $j)
        <tr><td>{{ $j->nama_jenis }}</td><td>{{ $j->kuota_hari }}</td><td>{{ $j->keterangan }}</td>
            <td class="text-nowrap">
                <a href="{{ route('admin.jenis-cuti.edit', $j) }}" class="btn btn-sm btn-warning">Ubah</a>
                <form method="POST" action="{{ route('admin.jenis-cuti.destroy', $j) }}" class="d-inline" onsubmit="return confirm('Hapus jenis cuti ini?')">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
            </td></tr>
    @empty
        <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
    @endforelse
    </tbody></table></div></div>
@endsection
