<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternatif extends Model
{
    protected $table = 'alternatif';

    protected $fillable = [
        'proyek_id',
        'kode',
        'nama',
        'keterangan',
    ];

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}
