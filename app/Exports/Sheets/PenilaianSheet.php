<?php

namespace App\Exports\Sheets;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PenilaianSheet implements FromView, ShouldAutoSize, WithTitle
{
    public function view(): View
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $alternatif = Alternatif::orderBy('kode')->get();
        $penilaian = Penilaian::all();

        $matriks = [];
        foreach ($penilaian as $p) {
            $matriks[$p->alternatif_id][$p->kriteria_id] = $p->nilai;
        }

        return view('exports.allinone.penilaian', compact('kriteria', 'alternatif', 'matriks'));
    }

    public function title(): string
    {
        return '3. Matriks Penilaian';
    }
}
