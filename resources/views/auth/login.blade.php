@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="login">
    <section class="login-intro">
        <div class="login-company"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18m-13 5 3 3 5-5"/></svg></span><span>PT. Ciomas Adisatwa<br>Unit Bogor</span></div><div><h1>Sistem Cuti Karyawan</h1><p>Kelola pengajuan, persetujuan, dan saldo cuti karyawan dalam satu sistem.</p></div><div class="login-foot">Sistem informasi internal · SDM</div>
    </section>
    <section class="login-form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h2>Masuk</h2><p class="login-description">Gunakan email dan kata sandi akun Anda.</p>
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if($errors->has('email') && !$errors->has('password'))
                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
            @endif
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" autocomplete="username" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Kata sandi</label>
                <input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required>
            </div>
            <button class="btn btn-primary w-100">Masuk</button>
            <p class="text-muted small mt-3 mb-0">Belum punya akun? Minta Admin SDM untuk membuatkan.</p>
        </form>
    </section>
</div>
@endsection
