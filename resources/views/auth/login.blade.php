@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="login">
    <section class="login-intro">
        <h1>Cuti Karyawan</h1>
        <div class="co">PT. Ciomas Adisatwa Unit Bogor</div>
        <p class="lead2">Ajukan cuti, putuskan pengajuan tim, dan cek sisa saldo tanpa formulir kertas.</p>
    </section>
    <section class="login-form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h2>Masuk ke akun Anda</h2>
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if($errors->has('email') && !$errors->has('password'))
                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
            @endif
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Kata sandi</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Masuk</button>
            <p class="text-muted small mt-3 mb-0">Belum punya akun? Minta Admin SDM untuk membuatkan.</p>
        </form>
    </section>
</div>
@endsection
