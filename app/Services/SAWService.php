<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class SAWService
{
    protected $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    /**
     * Hitung SPK menggunakan metode Simple Additive Weighting (SAW)
     *
     * Langkah-langkah:
     * 1. Normalisasi bobot (Wj) = Wj / Σ Wj
     * 2. Normalisasi matriks keputusan (Rij):
     *    - Benefit: Rij = Xij / Max(Xij)
     *    - Cost:    Rij = Min(Xij) / Xij
     * 3. Hitung Nilai Preferensi (Vi) = Σ (Wj * Rij)
     * 4. Ranking berdasarkan Vi terbesar
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
                'matriks' => [],
                'matriks_normal' => [],
                'preferensi' => [],
                'ranking' => [],
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
        // LANGKAH 3: Cari Max & Min untuk setiap kriteria
        // =============================================
        $maxKriteria = [];
        $minKriteria = [];
        foreach ($kriteria as $k) {
            $nilaiKolom = [];
            foreach ($alternatif as $a) {
                $val = $matriks[$a->id][$k->id] ?? 0;
                if ($val > 0) {
                    $nilaiKolom[] = $val;
                }
            }
            $maxKriteria[$k->id] = !empty($nilaiKolom) ? max($nilaiKolom) : 0;
            $minKriteria[$k->id] = !empty($nilaiKolom) ? min($nilaiKolom) : 0;
        }

        // =============================================
        // LANGKAH 4: Normalisasi Matriks (Rij)
        // =============================================
        $matriksNormal = [];
        $detailNormalisasi = [];

        foreach ($alternatif as $a) {
            foreach ($kriteria as $k) {
                $nilai = $matriks[$a->id][$k->id] ?? 0;

                if ($k->jenis === 'benefit') {
                    // Benefit: Rij = Xij / Max(Xij)
                    $rij = $maxKriteria[$k->id] > 0 ? $nilai / $maxKriteria[$k->id] : 0;
                    $rumus = "$nilai / {$maxKriteria[$k->id]}";
                } else {
                    // Cost: Rij = Min(Xij) / Xij
                    $rij = $nilai > 0 ? $minKriteria[$k->id] / $nilai : 0;
                    $rumus = "{$minKriteria[$k->id]} / $nilai";
                }

                $matriksNormal[$a->id][$k->id] = round($rij, 6);
                $detailNormalisasi[$a->id][$k->id] = [
                    'nilai' => $nilai,
                    'jenis' => $k->jenis,
                    'max' => $maxKriteria[$k->id],
                    'min' => $minKriteria[$k->id],
                    'rij' => round($rij, 6),
                    'rumus' => $rumus,
                ];
            }
        }

        // =============================================
        // LANGKAH 5: Hitung Nilai Preferensi (Vi)
        // =============================================
        $preferensi = [];
        $detailPreferensi = [];

        foreach ($alternatif as $a) {
            $vi = 0;
            $detail = [];

            foreach ($kriteria as $k) {
                $rij = $matriksNormal[$a->id][$k->id];
                $wj = $bobotNormal[$k->id];
                $hasil = $wj * $rij;
                $vi += $hasil;

                $detail[] = [
                    'kriteria_id' => $k->id,
                    'kriteria_kode' => $k->kode,
                    'kriteria_nama' => $k->nama,
                    'bobot_normal' => round($wj, 4),
                    'rij' => round($rij, 6),
                    'hasil' => round($hasil, 6),
                ];
            }

            $preferensi[$a->id] = round($vi, 6);
            $detailPreferensi[$a->id] = $detail;
        }

        // =============================================
        // LANGKAH 6: Ranking
        // =============================================
        arsort($preferensi);
        $ranking = [];
        $rank = 1;
        $maxV = max($preferensi) ?: 1;

        foreach ($preferensi as $altId => $nilaiV) {
            $alt = $alternatif->firstWhere('id', $altId);
            $ranking[] = [
                'rank' => $rank,
                'alternatif_id' => $altId,
                'kode' => $alt->kode,
                'nama' => $alt->nama,
                'preferensi' => $nilaiV,
                'persentase' => round(($nilaiV / $maxV) * 100, 2),
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
            'max_kriteria' => $maxKriteria,
            'min_kriteria' => $minKriteria,
            'matriks_normal' => $matriksNormal,
            'detail_normalisasi' => $detailNormalisasi,
            'preferensi' => $preferensi,
            'detail_preferensi' => $detailPreferensi,
            'ranking' => $ranking,
        ];
    }
}
