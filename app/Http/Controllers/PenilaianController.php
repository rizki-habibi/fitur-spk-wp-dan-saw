<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    use HasActiveProyek;

    public function index()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();

        $alternatif = Alternatif::where('proyek_id', $proyekId)->orderBy('kode')->get();
        $kriteria = Kriteria::where('proyek_id', $proyekId)->orderBy('kode')->get();
        $penilaian = Penilaian::with(['alternatif', 'kriteria'])
            ->whereIn('alternatif_id', $alternatif->pluck('id'))
            ->get();

        $matriks = [];
        foreach ($penilaian as $p) {
            $matriks[$p->alternatif_id][$p->kriteria_id] = $p->nilai;
        }

        return view('penilaian.index', compact('alternatif', 'kriteria', 'penilaian', 'matriks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->nilai as $alternatifId => $kriteriaValues) {
            foreach ($kriteriaValues as $kriteriaId => $nilai) {
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatifId,
                        'kriteria_id' => $kriteriaId,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }

        return redirect()->route('penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan!');
    }

    public function destroy($alternatifId)
    {
        Penilaian::where('alternatif_id', $alternatifId)->delete();

        return redirect()->route('penilaian.index')
            ->with('success', 'Penilaian alternatif berhasil dihapus!');
    }
}
