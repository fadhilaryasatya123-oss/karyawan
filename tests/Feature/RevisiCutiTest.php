<?php

namespace Tests\Feature;

use App\Models\JenisCuti;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\SaldoCuti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
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

    public function test_email_harus_diverifikasi(): void
    {
        Notification::fake();
        $this->pegawai->forceFill(['email_verified_at' => null])->save();
        $this->actingAs($this->pegawai);
        $this->get(route('dashboard'))->assertRedirect(route('verification.notice'));
        $this->get(route('karyawan.pengajuan.create'))->assertRedirect(route('verification.notice'));
        $this->post(route('verification.send'))->assertSessionHas('success');
        Notification::assertSentTo($this->pegawai, VerifyEmail::class);
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $this->pegawai->id, 'hash' => sha1($this->pegawai->email),
        ]);
        $this->get($url)->assertRedirect(route('dashboard'));
        $this->assertTrue($this->pegawai->fresh()->hasVerifiedEmail());
    }
}
