@extends('layouts.app')
@section('title', 'Verifikasi Email')
@section('content')
<div class="card"><div class="card-body">
    <h4>Verifikasi email Anda</h4>
    <p>Untuk memastikan email aktif dan benar-benar milik Anda, verifikasi alamat <strong>{{ auth()->user()->email }}</strong> sebelum mengakses fitur cuti.</p>
    <p>Tekan tombol berikut, lalu buka tautan verifikasi di kotak masuk. Jika alamat salah, hubungi Admin SDM untuk memperbaruinya.</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn btn-primary">Kirim tautan verifikasi</button>
    </form>
</div></div>
@endsection
