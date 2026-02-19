<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Services\WeightedProductService;
use App\Services\SAWService;

class PerhitunganController extends Controller
{
    use HasActiveProyek;

    public function wp()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();

        $service = new WeightedProductService($proyekId);
        $hasil = $service->hitung();

        return view('perhitungan.wp', compact('hasil'));
    }

    public function saw()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();

        $service = new SAWService($proyekId);
        $hasil = $service->hitung();

        return view('perhitungan.saw', compact('hasil'));
    }

    public function perbandingan()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();

        $wpService = new WeightedProductService($proyekId);
        $sawService = new SAWService($proyekId);

        $hasilWP = $wpService->hitung();
        $hasilSAW = $sawService->hitung();

        return view('perhitungan.perbandingan', compact('hasilWP', 'hasilSAW'));
    }
}
