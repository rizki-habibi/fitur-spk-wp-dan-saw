<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Services\WeightedProductService;
use App\Services\SAWService;

class LaporanController extends Controller
{
    use HasActiveProyek;

    public function index()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();
        $proyek = $this->getActiveProyek();

        $wpService = new WeightedProductService($proyekId);
        $sawService = new SAWService($proyekId);

        $hasilWP = $wpService->hitung();
        $hasilSAW = $sawService->hitung();

        return view('laporan.index', compact('proyek', 'hasilWP', 'hasilSAW'));
    }
}
