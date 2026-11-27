# Revisi sistem cuti

- Pengajuan maksimal 5 hari kerja Senin–Jumat, minimal H-3 hari kalender (WIB). Rentang lintas tahun harus dipisah; kebijakan ini berlaku untuk semua jenis cuti.
- Saat manajer menyetujui, status berubah menjadi menunggu konfirmasi dan saldo dicadangkan satu kali. Karyawan mengonfirmasi dari riwayat; status menjadi disetujui. Pengajuan lama yang sudah disetujui tidak diubah. Saldo tetap dicadangkan sampai konfirmasi; pembatalan setelah persetujuan tidak disediakan.
- Dashboard dan formulir menampilkan saldo tahun berjalan per jenis; jika belum ada record saldo, kuota awal diberi keterangan.
- Saat admin menambah atau mengubah akun karyawan/manajer, email wajib valid secara format, unik, dan berakhiran @gmail.com. Tidak ada verifikasi kepemilikan, OTP, atau pengiriman email.
- Migrasi tambahan mengubah tiga akun demo lama @ciomas.test menjadi @gmail.com tanpa mengubah ID, password, saldo, atau riwayat. Akun lain tidak diubah. Jika alamat tujuan sudah digunakan, migrasi dibatalkan tanpa menimpa akun yang ada. Ganti kata sandi demo sebelum produksi. Jangan jalankan seeder demo pada database produksi.

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
Tidak perlu konfigurasi SMTP, Google App Password, atau Gmail API. Validasi domain hanya memeriksa format alamat, bukan keberadaan atau kepemilikan kotak masuk. Hanya tiga akun demo bawaan yang diubah otomatis. Akun lain tetap memakai email yang sudah terdaftar.

## Login demo lokal

| Peran | Email | Password bawaan |
| --- | --- | --- |
| Admin | admin@gmail.com | password |
| Manajer | manajer@gmail.com | password |
| Karyawan | karyawan@gmail.com | password |

Jalankan php artisan migrate setelah mengambil commit terbaru. Password yang pernah diganti tetap berlaku. Akun demo tidak membuktikan kepemilikan alamat Gmail; tidak ada pengiriman email. Jika migrasi menemukan bentrok, gunakan akun lama/admin untuk menyelesaikannya. Rollback migrasi tidak mengembalikan alamat lama agar tidak menimpa perubahan pengguna.
