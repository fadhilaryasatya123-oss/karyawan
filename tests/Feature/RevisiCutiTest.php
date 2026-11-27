<?php

namespace Tests\Feature;

use App\Models\JenisCuti;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RevisiCutiTest extends TestCase
{
    use RefreshDatabase;

    private User $pegawai;
    private User $manajer;
    private Karyawan $profil;
    private JenisCuti $jenis;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(\Carbon\Carbon::parse('2026-11-27 09:00:00'));
        $this->manajer = User::factory()->create(['role' => 'manajer']);
        $this->pegawai = User::factory()->create(['role' => 'karyawan']);
        $this->profil = Karyawan::create([
            'user_id' => $this->pegawai->id, 'nik' => 'TEST001', 'nama_lengkap' => 'Pegawai Uji',
            'departemen' => 'Produksi', 'jabatan' => 'Operator',
            'atasan_id' => $this->manajer->id, 'tanggal_bergabung' => '2022-01-01',
        ]);
        $this->jenis = JenisCuti::create(['nama_jenis' => 'Cuti Tahunan', 'kuota_hari' => 12]);
    }

    private function payload(string $mulai, string $selesai): array
    {
        return ['jenis_cuti_id' => $this->jenis->id, 'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai, 'alasan' => 'Keperluan keluarga'];
    }

    public function test_h_minus_tiga_dan_maksimal_lima_hari(): void
    {
        $this->actingAs($this->pegawai);
        $this->post(route('karyawan.pengajuan.store'), $this->payload('2026-11-29', '2026-11-30'))
            ->assertSessionHasErrors('tanggal_mulai');
        $this->post(route('karyawan.pengajuan.store'), $this->payload('2026-11-30', '2026-12-07'))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('pengajuan_cuti', 0);
        $this->post(route('karyawan.pengajuan.store'), $this->payload('2026-11-30', '2026-12-04'))
            ->assertRedirect(route('karyawan.pengajuan.index'));
        $this->assertDatabaseHas('pengajuan_cuti', ['jumlah_hari' => 5, 'status' => 'menunggu']);
    }

    public function test_saldo_dan_konfirmasi_tidak_ganda(): void
    {
        $this->actingAs($this->pegawai);
        $this->get(route('dashboard'))->assertOk()->assertSee('Cuti Tahunan');
        $this->get(route('karyawan.pengajuan.create'))->assertOk()->assertSee('Saldo cuti');
        $this->post(route('karyawan.pengajuan.store'), $this->payload('2026-11-30', '2026-12-04'));
        $p = PengajuanCuti::firstOrFail();
        $this->post(route('karyawan.pengajuan.konfirmasi', $p))->assertSessionHas('error');
        $this->actingAs($this->manajer)->post(route('manajer.persetujuan.keputusan', $p), ['keputusan' => 'disetujui'])
            ->assertRedirect(route('manajer.persetujuan.index'));
        $this->assertSame('menunggu_konfirmasi', $p->fresh()->status);
        $this->assertEquals(7, SaldoCuti::firstOrFail()->sisa);
        $lain = User::factory()->create(['role' => 'karyawan']);
        Karyawan::create(['user_id' => $lain->id, 'nik' => 'TEST002', 'nama_lengkap' => 'Lain',
            'departemen' => 'Produksi', 'jabatan' => 'Operator', 'tanggal_bergabung' => '2022-01-01']);
        $this->actingAs($lain)->post(route('karyawan.pengajuan.konfirmasi', $p))->assertForbidden();
        $this->actingAs($this->pegawai)->get(route('karyawan.pengajuan.index'))->assertSee('Konfirmasi Cuti');
        $this->post(route('karyawan.pengajuan.konfirmasi', $p))->assertSessionHas('success');
        $this->assertSame('disetujui', $p->fresh()->status);
        $this->assertNotNull($p->fresh()->tanggal_konfirmasi);
        $this->post(route('karyawan.pengajuan.konfirmasi', $p))->assertSessionHas('error');
        $this->assertEquals(7, SaldoCuti::firstOrFail()->sisa);
    }

    public function test_email_gmail_wajib_saat_membuat_dan_mengubah_akun(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $payload = [
            'nama_lengkap' => 'Pegawai Baru', 'email' => 'pegawai@example.com',
            'password' => 'password123', 'role' => 'karyawan', 'nik' => 'TEST003',
            'departemen' => 'Produksi', 'jabatan' => 'Operator',
            'atasan_id' => $this->manajer->id, 'tanggal_bergabung' => '2022-01-01',
        ];

        foreach (['pegawai@example.com', 'pegawai@gmail.com.example.org', 'gmail.com', '@gmail.com'] as $email) {
            $this->post(route('admin.karyawan.store'), array_replace($payload, ['email' => $email]))
                ->assertSessionHasErrors('email');
        }

        $payload['email'] = 'pegawai.uji@GMAIL.COM';
        $this->post(route('admin.karyawan.store'), $payload)->assertRedirect(route('admin.karyawan.index'));
        $user = User::where('email', 'pegawai.uji@gmail.com')->firstOrFail();
        $this->assertNull($user->email_verified_at);

        $this->post(route('admin.karyawan.store'), array_replace($payload, ['nik' => 'TEST004']))
            ->assertSessionHasErrors('email');

        $this->put(route('admin.karyawan.update', $user->karyawan), array_replace($payload, [
            'email' => 'pegawai@outlook.com', 'password' => null,
        ]))->assertSessionHasErrors('email');

        $this->put(route('admin.karyawan.update', $user->karyawan), array_replace($payload, [
            'email' => 'pegawai.baru@gmail.com', 'password' => null,
        ]))->assertRedirect(route('admin.karyawan.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'pegawai.baru@gmail.com']);
        Notification::assertNothingSent();
    }

    public function test_akun_tidak_memerlukan_verifikasi_email(): void
    {
        $this->pegawai->forceFill(['email_verified_at' => null])->save();
        $this->actingAs($this->pegawai);
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('karyawan.pengajuan.create'))->assertOk();
        $this->get('/email/verify')->assertNotFound();
    }

    public function test_migrasi_login_demo_gmail_tanpa_mengubah_password(): void
    {
        $migration = require database_path('migrations/2026_11_27_000002_update_demo_login_to_gmail.php');
        foreach (['admin', 'manajer', 'karyawan'] as $role) {
            $user = User::factory()->create([
                'email' => $role . '@ciomas.test',
                'role' => $role,
                'password' => 'password',
            ]);
            $hash = $user->password;
            $migration->up();
            $user->refresh();

            $this->assertSame($role . '@gmail.com', $user->email);
            $this->assertSame($hash, $user->password);
            $this->assertNull($user->email_verified_at);

            $this->post(route('login'), [
                'email' => $role . '@gmail.com',
                'password' => 'password',
            ])->assertRedirect(route('dashboard'));
            $this->assertAuthenticatedAs($user);
            $this->post(route('logout'))->assertRedirect(route('login'));
        }
    }

    public function test_migrasi_tidak_menimpa_email_yang_sudah_dipakai(): void
    {
        $lama = User::factory()->create(['email' => 'admin@ciomas.test', 'role' => 'admin']);
        $tujuan = User::factory()->create(['email' => 'admin@gmail.com', 'role' => 'karyawan']);
        $migration = require database_path('migrations/2026_11_27_000002_update_demo_login_to_gmail.php');

        try {
            $migration->up();
            $this->fail('Migrasi seharusnya menolak bentrok email.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('sudah digunakan', $e->getMessage());
        }
        $this->assertSame('admin@ciomas.test', $lama->fresh()->email);
        $this->assertSame('admin@gmail.com', $tujuan->fresh()->email);
    }
}
