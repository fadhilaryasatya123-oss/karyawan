# Revisi sistem cuti

- Pengajuan maksimal 5 hari kerja Senin–Jumat, minimal H-3 hari kalender (WIB). Rentang lintas tahun harus dipisah; kebijakan ini berlaku untuk semua jenis cuti.
- Saat manajer menyetujui, status berubah menjadi menunggu konfirmasi dan saldo dicadangkan satu kali. Karyawan mengonfirmasi dari riwayat; status menjadi disetujui. Pengajuan lama yang sudah disetujui tidak diubah. Saldo tetap dicadangkan sampai konfirmasi; pembatalan setelah persetujuan tidak disediakan.
- Dashboard dan formulir menampilkan saldo tahun berjalan per jenis; jika belum ada record saldo, kuota awal diberi keterangan.
- Saat admin menambah atau mengubah akun karyawan/manajer, email wajib valid secara format, unik, dan berakhiran @gmail.com. Tidak ada verifikasi kepemilikan, OTP, atau pengiriman email.
- Akun lama dan akun demo @ciomas.test tetap dapat login. Saat data karyawan/manajer diubah, gunakan email Gmail. Ganti kata sandi demo sebelum produksi. Jangan jalankan seeder demo pada database produksi.

## Instalasi revisi
Backup database, lalu jalankan dari terminal Laragon pada folder project:
```sh
composer install
php artisan migrate
php artisan optimize:clear
php artisan test --filter=RevisiCutiTest
php artisan serve
```
Jangan gunakan migrate:fresh pada database yang berisi data.

## Email
Tidak perlu konfigurasi SMTP, Google App Password, atau Gmail API. Validasi domain hanya memeriksa format alamat, bukan keberadaan atau kepemilikan kotak masuk. Akun lama tidak diubah otomatis untuk menghindari kehilangan akses.
