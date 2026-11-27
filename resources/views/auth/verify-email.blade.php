@extends('layouts.app')
@section('title', 'Verifikasi Email')
@section('content')
<div class="page-head"><div><h4>Verifikasi email</h4><p class="page-description">Aktifkan akses akun sebelum menggunakan sistem cuti.</p></div></div><div class="card verify-panel"><div class="card-body"><h5 class="mb-3">Verifikasi email Anda</h5>
    <p>Untuk memastikan email aktif dan benar-benar milik Anda, verifikasi alamat <strong>{{ auth()->user()->email }}</strong> sebelum mengakses fitur cuti.</p>
    <p>Tekan tombol berikut, lalu buka tautan verifikasi di kotak masuk. Jika alamat salah, hubungi Admin SDM untuk memperbaruinya.</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn btn-primary">Kirim tautan verifikasi</button>
    </form>
</div></div>
@endsection
