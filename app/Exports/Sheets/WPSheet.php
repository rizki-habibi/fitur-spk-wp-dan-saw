<?php

namespace App\Exports\Sheets;

use App\Services\WeightedProductService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class WPSheet implements FromView, ShouldAutoSize, WithTitle
{
    public function view(): View
    {
        $service = new WeightedProductService();
        $hasil = $service->hitung();

        return view('exports.allinone.wp', compact('hasil'));
    }

    public function title(): string
    {
        return '4. Perhitungan WP';
    }
}
