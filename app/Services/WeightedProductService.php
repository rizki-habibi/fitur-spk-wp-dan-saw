<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class WeightedProductService
{
    protected $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    /**
     * Hitung SPK menggunakan metode Weighted Product (WP)
     *
     * Langkah-langkah:
     * 1. Normalisasi bobot (Wj) = Wj / Σ Wj
     * 2. Hitung Vektor S = Π (Xij ^ Wj)
     *    - Jika kriteria benefit: pangkat positif (+Wj)
     *    - Jika kriteria cost: pangkat negatif (-Wj)
     * 3. Hitung Vektor V = Si / Σ Si (preferensi relatif)
     * 4. Ranking berdasarkan Vektor V terbesar
     */
    public function hitung(): array
    {
        $kriteria = Kriteria::when($this->proyekId, fn($q) => $q->where('proyek_id', $this->proyekId))->orderBy('kode')->get();
        $alternatif = Alternatif::when($this->proyekId, fn($q) => $q->where('proyek_id', $this->proyekId))->orderBy('kode')->get();
        $penilaian = Penilaian::whereIn('alternatif_id', $alternatif->pluck('id'))->get();

        if ($kriteria->isEmpty() || $alternatif->isEmpty() || $penilaian->isEmpty()) {
            return [
                'kriteria' => $kriteria,
                'alternatif' => $alternatif,
                'penilaian' => collect(),
                'bobot_normal' => [],
                'vektor_s' => [],
                'vektor_v' => [],
                'ranking' => [],
                'detail_perhitungan' => [],
            ];
        }

        // =============================================
        // LANGKAH 1: Normalisasi Bobot
        // =============================================
        $totalBobot = $kriteria->sum('bobot');
        $bobotNormal = [];
        foreach ($kriteria as $k) {
            $bobotNormal[$k->id] = $totalBobot > 0 ? $k->bobot / $totalBobot : 0;
        }

        // =============================================
        // LANGKAH 2: Buat matriks penilaian
        // =============================================
        $matriks = [];
        foreach ($penilaian as $p) {
            $matriks[$p->alternatif_id][$p->kriteria_id] = $p->nilai;
        }

        // =============================================
        // LANGKAH 3: Hitung Vektor S
        // =============================================
        $vektorS = [];
        $detailPerhitungan = [];

        foreach ($alternatif as $a) {
            $s = 1;
            $detail = [];

            foreach ($kriteria as $k) {
                $nilai = $matriks[$a->id][$k->id] ?? 0;
                $w = $bobotNormal[$k->id];

                // Jika cost, pangkat negatif
                $pangkat = ($k->jenis === 'cost') ? -$w : $w;

                if ($nilai > 0) {
                    $hasil = pow($nilai, $pangkat);
                } else {
                    $hasil = 0;
                }

                $detail[] = [
                    'kriteria_id' => $k->id,
                    'kriteria_kode' => $k->kode,
                    'kriteria_nama' => $k->nama,
                    'jenis' => $k->jenis,
                    'nilai' => $nilai,
                    'bobot_normal' => round($w, 4),
                    'pangkat' => round($pangkat, 4),
                    'hasil' => round($hasil, 6),
                ];

                $s *= $hasil;
            }

            $vektorS[$a->id] = round($s, 6);
            $detailPerhitungan[$a->id] = $detail;
        }

        // =============================================
        // LANGKAH 4: Hitung Vektor V (Preferensi Relatif)
        // =============================================
        $totalS = array_sum($vektorS);
        $vektorV = [];

        foreach ($alternatif as $a) {
            $vektorV[$a->id] = $totalS > 0 ? round($vektorS[$a->id] / $totalS, 6) : 0;
        }

        // =============================================
        // LANGKAH 5: Ranking
        // =============================================
        arsort($vektorV);
        $ranking = [];
        $rank = 1;
        foreach ($vektorV as $altId => $nilaiV) {
            $alt = $alternatif->firstWhere('id', $altId);
            $ranking[] = [
                'rank' => $rank,
                'alternatif_id' => $altId,
                'kode' => $alt->kode,
                'nama' => $alt->nama,
                'vektor_s' => $vektorS[$altId],
                'vektor_v' => $nilaiV,
                'persentase' => round($nilaiV * 100, 2),
            ];
            $rank++;
        }

        return [
            'kriteria' => $kriteria,
            'alternatif' => $alternatif,
            'penilaian' => $penilaian,
            'bobot_normal' => $bobotNormal,
            'total_bobot' => $totalBobot,
            'matriks' => $matriks,
            'vektor_s' => $vektorS,
            'total_s' => $totalS,
            'vektor_v' => $vektorV,
            'ranking' => $ranking,
            'detail_perhitungan' => $detailPerhitungan,
        ];
    }
}
