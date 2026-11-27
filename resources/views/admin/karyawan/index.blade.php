@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('content')
<div class="page-head">
    <div><h4>Data Karyawan</h4><p class="page-description">Kelola akun, jabatan, dan atasan karyawan.</p></div>
    <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
</div>
<form class="row g-2 toolbar" method="GET">
    <div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIK" aria-label="Cari nama atau NIK"></div>
    <div class="col-auto"><button class="btn btn-outline-secondary">Cari</button></div>
</form>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0 align-middle">
    <thead><tr><th>NIK</th><th>Nama</th><th>Email</th><th>Peran</th><th>Departemen</th><th>Jabatan</th><th>Atasan</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($data as $k)
        <tr><td>{{ $k->nik }}</td><td>{{ $k->nama_lengkap }}</td><td>{{ $k->user->email }}</td><td>{{ ucfirst($k->user->role) }}</td>
            <td>{{ $k->departemen }}</td><td>{{ $k->jabatan }}</td><td>{{ $k->atasan->name ?? '-' }}</td>
            <td class="text-nowrap">
                <a href="{{ route('admin.karyawan.edit', $k) }}" class="btn btn-sm btn-warning">Ubah</a>
                <form method="POST" action="{{ route('admin.karyawan.destroy', $k) }}" class="d-inline" onsubmit="return confirm('Hapus karyawan ini beserta seluruh data cutinya?')">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
            </td></tr>
    @empty
        <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
    @endforelse
    </tbody></table></div></div>
<div class="mt-3">{{ $data->links('pagination::bootstrap-5') }}</div>
@endsection
