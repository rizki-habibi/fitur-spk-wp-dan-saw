<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'proyek_id',
        'kode',
        'nama',
        'bobot',
        'jenis',
        'keterangan',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
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
