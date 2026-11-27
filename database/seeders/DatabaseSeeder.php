<?php

namespace Database\Seeders;

use App\Models\JenisCuti;
use App\Models\Karyawan;
use App\Models\SaldoCuti;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jenis cuti contoh. SESUAIKAN dengan kebijakan cuti PT. Ciomas Adisatwa Unit Bogor.
        $jenis = collect([
            ['Cuti Tahunan', 12, 'Hak cuti tahunan karyawan'],
            ['Cuti Sakit', 12, 'Cuti karena sakit'],
            ['Cuti Melahirkan', 90, 'Cuti melahirkan'],
            ['Cuti Khusus', 3, 'Pernikahan, kelahiran anak, atau musibah keluarga'],
        ])->map(fn ($j) => JenisCuti::create([
            'nama_jenis' => $j[0], 'kuota_hari' => $j[1], 'keterangan' => $j[2],
        ]));

        // Akun demo lokal saja (password: password). Tidak ada email yang dikirim.
        User::create(['name' => 'Admin SDM', 'email' => 'admin@gmail.com', 'password' => 'password', 'role' => 'admin']);

        $manajer = User::create(['name' => 'Budi Manajer', 'email' => 'manajer@gmail.com', 'password' => 'password', 'role' => 'manajer']);
        Karyawan::create([
            'user_id' => $manajer->id, 'nik' => 'MGR001', 'nama_lengkap' => 'Budi Manajer',
            'departemen' => 'Produksi', 'jabatan' => 'Manajer Produksi', 'tanggal_bergabung' => '2020-01-10',
        ]);

        $karyawanUser = User::create(['name' => 'Siti Karyawan', 'email' => 'karyawan@gmail.com', 'password' => 'password', 'role' => 'karyawan']);
        Karyawan::create([
            'user_id' => $karyawanUser->id, 'nik' => 'KRY001', 'nama_lengkap' => 'Siti Karyawan',
            'departemen' => 'Produksi', 'jabatan' => 'Operator Produksi',
            'atasan_id' => $manajer->id, 'tanggal_bergabung' => '2022-03-01',
        ]);

        // Saldo tahun berjalan untuk semua karyawan
        foreach (Karyawan::all() as $k) {
            foreach ($jenis as $j) {
                SaldoCuti::create([
                    'karyawan_id' => $k->id, 'jenis_cuti_id' => $j->id, 'tahun' => now()->year,
                    'kuota' => $j->kuota_hari, 'terpakai' => 0,
                ]);
            }
        }
    }
}
