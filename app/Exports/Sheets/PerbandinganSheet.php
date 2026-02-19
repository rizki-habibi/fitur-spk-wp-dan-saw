<?php

namespace App\Exports\Sheets;

use App\Services\WeightedProductService;
use App\Services\SAWService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PerbandinganSheet implements FromView, ShouldAutoSize, WithTitle
{
    public function view(): View
    {
        $wpService = new WeightedProductService();
        $sawService = new SAWService();

        $hasilWP = $wpService->hitung();
        $hasilSAW = $sawService->hitung();

        return view('exports.allinone.perbandingan', compact('hasilWP', 'hasilSAW'));
    }

    public function title(): string
    {
        return '6. Perbandingan & Kesimpulan';
    }
}
