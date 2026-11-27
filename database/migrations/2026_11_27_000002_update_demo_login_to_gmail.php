<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            foreach (['admin', 'manajer', 'karyawan'] as $role) {
                $lama = $role . '@ciomas.test';
                $baru = $role . '@gmail.com';

                $user = DB::table('users')->where('email', $lama)->where('role', $role)
                    ->lockForUpdate()->first();

                if (! $user) {
                    continue;
                }

                if (DB::table('users')->where('email', $baru)->exists()) {
                    throw new \RuntimeException("Migrasi email dibatalkan: {$baru} sudah digunakan. Ubah alamat yang bentrok melalui admin sebelum menjalankan migrasi lagi.");
                }

                DB::table('users')->where('id', $user->id)->update([
                    'email' => $baru,
                    'email_verified_at' => null,
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        // Perubahan data satu arah: jangan menimpa alamat email yang mungkin
        // sudah diperbarui pengguna setelah migrasi.
    }
};
