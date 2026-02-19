<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    protected $table = 'proyek';

    protected $fillable = [
        'nama',
        'deskripsi',
        'icon',
        'warna',
    ];

    public function kriteria(): HasMany
    {
        return $this->hasMany(Kriteria::class);
    }

    public function alternatif(): HasMany
    {
        return $this->hasMany(Alternatif::class);
    }
}
