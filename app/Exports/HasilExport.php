<?php

namespace App\Exports;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\WeightedProductService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class HasilExport implements FromView, ShouldAutoSize, WithTitle
{
    private $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    public function view(): View
    {
        $service = new WeightedProductService($this->proyekId);
        $hasil = $service->hitung();

        return view('exports.hasil', compact('hasil'));
    }

    public function title(): string
    {
        return 'Hasil Perhitungan WP';
    }
}
