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
    <link href="{{ asset('css/app.css') }}?v=2" rel="stylesheet">
</head>
<body>

@auth
@php $role = auth()->user()->role; @endphp
<div class="shell">
    <aside class="side">
        <a class="brand" href="{{ route('dashboard') }}">
            <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18m-13 5 3 3 5-5"/></svg></span><div><strong>Cuti Karyawan</strong><span>PT. Ciomas Adisatwa<br>Unit Bogor</span></div>
        </a>
        <nav aria-label="Navigasi utama"><p class="nav-caption">Menu utama</p>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg><span>Dashboard</span></a>
            @if($role === 'admin')
                <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18m-13 5h8"/></svg><span>Pengajuan cuti</span></a>
                <a href="{{ route('admin.karyawan.index') }}" class="{{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-4"/><circle cx="10" cy="7" r="4"/><path d="M17 3a4 4 0 0 1 0 8"/></svg><span>Data karyawan</span></a>
                <a href="{{ route('admin.jenis-cuti.index') }}" class="{{ request()->routeIs('admin.jenis-cuti.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg><span>Jenis cuti</span></a>
                <a href="{{ route('admin.saldo.index') }}" class="{{ request()->routeIs('admin.saldo.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20h18M6 16v-5m6 5V4m6 12V8"/></svg><span>Kuota dan saldo</span></a>
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M8 13h8m-8 4h8"/></svg><span>Laporan</span></a>
            @elseif($role === 'manajer')
                <a href="{{ route('manajer.persetujuan.index') }}" class="{{ request()->routeIs('manajer.persetujuan.index') || request()->routeIs('manajer.persetujuan.show') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span>Menunggu keputusan</span></a>
                <a href="{{ route('manajer.persetujuan.riwayat') }}" class="{{ request()->routeIs('manajer.persetujuan.riwayat') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8m0-5v5h5m4-1v5l3 2"/></svg><span>Riwayat keputusan</span></a>
            @else
                <a href="{{ route('karyawan.pengajuan.create') }}" class="{{ request()->routeIs('karyawan.pengajuan.create') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18m-9 3v5m-2.5-2.5h5"/></svg><span>Ajukan cuti</span></a>
                <a href="{{ route('karyawan.pengajuan.index') }}" class="{{ request()->routeIs('karyawan.pengajuan.index') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8m0-5v5h5m4-1v5l3 2"/></svg><span>Riwayat cuti</span></a>
            @endif
        </nav>
        <div class="who">
            <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><div class="who-info"><b>{{ auth()->user()->name }}</b><span>{{ ucfirst($role) }}</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button>Keluar</button></form>
        </div>
    </aside>

    <div class="workspace"><header class="topbar"><div class="context">Sistem cuti / <strong>{{ ucfirst($role) }}</strong></div><time datetime="{{ now()->toDateString() }}">{{ now()->locale('id')->translatedFormat('d F Y') }}</time></header><main class="main" id="main-content">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main></div>
</div>
@else
<main class="guest">
    @yield('content')
</main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
