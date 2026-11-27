<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaldoCuti extends Model
{
    protected $table = 'saldo_cuti';

    protected $fillable = ['karyawan_id', 'jenis_cuti_id', 'tahun', 'kuota', 'terpakai', 'sisa'];

    protected static function booted(): void
    {
        // sisa selalu dihitung otomatis: kuota - terpakai
        static::saving(function (SaldoCuti $saldo) {
            $saldo->sisa = $saldo->kuota - $saldo->terpakai;
        });
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jenisCuti(): BelongsTo
    {
        return $this->belongsTo(JenisCuti::class);
    }
}
