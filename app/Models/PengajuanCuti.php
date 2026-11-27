<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanCuti extends Model
{
    protected $table = 'pengajuan_cuti';

    protected $fillable = [
        'karyawan_id', 'jenis_cuti_id', 'tanggal_mulai', 'tanggal_selesai', 'jumlah_hari',
        'alasan', 'status', 'manajer_id', 'catatan_manajer', 'tanggal_keputusan', 'tanggal_konfirmasi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_keputusan' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jenisCuti(): BelongsTo
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function manajer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manajer_id');
    }

    /** Hitung hari kerja (Senin-Jumat) dari rentang tanggal, termasuk tanggal awal dan akhir. */
    public static function hitungHariKerja(Carbon $mulai, Carbon $selesai): int
    {
        $n = 0;
        for ($d = $mulai->copy(); $d->lte($selesai); $d->addDay()) {
            if (! $d->isWeekend()) {
                $n++;
            }
        }
        return $n;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'menunggu_konfirmasi' ? 'Menunggu konfirmasi karyawan' : ucfirst($this->status);
    }

    public function getBadgeAttribute(): string
    {
        return match ($this->status) {
            'disetujui' => 'is-ok',
            'ditolak' => 'is-no',
            default => 'is-wait',
        };
    }
}
