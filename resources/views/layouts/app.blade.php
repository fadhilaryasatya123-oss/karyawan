<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cuti Karyawan') - PT. Ciomas Adisatwa Unit Bogor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

@auth
@php $role = auth()->user()->role; @endphp
<div class="shell">
    <aside class="side">
        <a class="brand" href="{{ route('dashboard') }}">
            <strong>Cuti Karyawan</strong>
            <span>PT. Ciomas Adisatwa Unit Bogor</span>
        </a>
        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            @if($role === 'admin')
                <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">Pengajuan cuti</a>
                <a href="{{ route('admin.karyawan.index') }}" class="{{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">Data karyawan</a>
                <a href="{{ route('admin.jenis-cuti.index') }}" class="{{ request()->routeIs('admin.jenis-cuti.*') ? 'active' : '' }}">Jenis cuti</a>
                <a href="{{ route('admin.saldo.index') }}" class="{{ request()->routeIs('admin.saldo.*') ? 'active' : '' }}">Kuota dan saldo</a>
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">Laporan</a>
            @elseif($role === 'manajer')
                <a href="{{ route('manajer.persetujuan.index') }}" class="{{ request()->routeIs('manajer.persetujuan.index') || request()->routeIs('manajer.persetujuan.show') ? 'active' : '' }}">Menunggu keputusan</a>
                <a href="{{ route('manajer.persetujuan.riwayat') }}" class="{{ request()->routeIs('manajer.persetujuan.riwayat') ? 'active' : '' }}">Riwayat keputusan</a>
            @else
                <a href="{{ route('karyawan.pengajuan.create') }}" class="{{ request()->routeIs('karyawan.pengajuan.create') ? 'active' : '' }}">Ajukan cuti</a>
                <a href="{{ route('karyawan.pengajuan.index') }}" class="{{ request()->routeIs('karyawan.pengajuan.index') ? 'active' : '' }}">Riwayat cuti</a>
            @endif
        </nav>
        <div class="who">
            <div><b>{{ auth()->user()->name }}</b><span>{{ ucfirst($role) }}</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button>Keluar</button></form>
        </div>
    </aside>

    <main class="main">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
</div>
@else
<main class="guest">
    @yield('content')
</main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
