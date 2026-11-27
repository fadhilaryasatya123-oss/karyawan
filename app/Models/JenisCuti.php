<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisCuti extends Model
{
    protected $table = 'jenis_cuti';

    protected $fillable = ['nama_jenis', 'kuota_hari', 'keterangan'];

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    public function saldo(): HasMany
    {
        return $this->hasMany(SaldoCuti::class);
    }
}
