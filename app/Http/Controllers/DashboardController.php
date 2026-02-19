<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Penilaian;
use App\Services\WeightedProductService;
use App\Services\SAWService;

class DashboardController extends Controller
{
    use HasActiveProyek;

    public function index()
    {
        $proyekId = $this->getActiveProyekId();
        $proyek = $this->getActiveProyek();

        if (!$proyekId) {
            return redirect()->route('proyek.index')
                ->with('info', 'Silakan pilih atau buat proyek terlebih dahulu.');
        }

        $totalKriteria = Kriteria::where('proyek_id', $proyekId)->count();
        $totalAlternatif = Alternatif::where('proyek_id', $proyekId)->count();

        $altIds = Alternatif::where('proyek_id', $proyekId)->pluck('id');
        $totalPenilaian = Penilaian::whereIn('alternatif_id', $altIds)->count();

        $wpService = new WeightedProductService($proyekId);
        $hasilWP = $wpService->hitung();

        $sawService = new SAWService($proyekId);
        $hasilSAW = $sawService->hitung();

        return view('dashboard', compact(
            'totalKriteria',
            'totalAlternatif',
            'totalPenilaian',
            'hasilWP',
            'hasilSAW',
            'proyek'
        ));
    }
}
