# Revisi sistem cuti

- Pengajuan maksimal 5 hari kerja Senin–Jumat, minimal H-3 hari kalender (WIB). Rentang lintas tahun harus dipisah; kebijakan ini berlaku untuk semua jenis cuti.
- Saat manajer menyetujui, status berubah menjadi menunggu konfirmasi dan saldo dicadangkan satu kali. Karyawan mengonfirmasi dari riwayat; status menjadi disetujui. Pengajuan lama yang sudah disetujui tidak diubah. Saldo tetap dicadangkan sampai konfirmasi; pembatalan setelah persetujuan tidak disediakan.
- Dashboard dan formulir menampilkan saldo tahun berjalan per jenis; jika belum ada record saldo, kuota awal diberi keterangan.
- Admin memvalidasi email RFC, DNS, dan unik. Karyawan/manajer harus memverifikasi kepemilikan email melalui tautan bertanda tangan. Perubahan alamat mereset verifikasi. Admin dikecualikan agar tetap dapat mengelola akun.
- Akun demo @ciomas.test tidak dapat menerima email nyata; gunakan admin demo hanya untuk pengembangan, ganti email karyawan/manajer dengan email aktif melalui admin, dan ganti kata sandi demo sebelum produksi. Jangan jalankan seeder demo pada database produksi.

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
Atur file .env lokal (jangan commit):
```dotenv
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Jakarta
MAIL_MAILER=smtp
MAIL_HOST=host-smtp-anda
MAIL_PORT=587
MAIL_USERNAME=akun-smtp-anda
MAIL_PASSWORD=kata-sandi-smtp-anda
MAIL_FROM_ADDRESS=alamat-pengirim-terverifikasi
MAIL_FROM_NAME="Sistem Cuti Karyawan"
```
Gunakan pengaturan TLS dan kredensial sesuai penyedia email. Untuk produksi gunakan HTTPS dan URL aplikasi sebenarnya. Mailer log/array tidak mengirim email nyata. Setelah konfigurasi jalankan php artisan optimize:clear dan login sebagai karyawan/manajer, tekan Kirim tautan verifikasi, lalu buka tautan dengan sesi akun yang sama. DNS saja tidak membuktikan mailbox aktif.
