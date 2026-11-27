<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'user_id', 'nik', 'nama_lengkap', 'departemen', 'jabatan', 'atasan_id', 'tanggal_bergabung',
    ];

    protected $casts = ['tanggal_bergabung' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    public function saldo(): HasMany
    {
        return $this->hasMany(SaldoCuti::class);
    }
}
