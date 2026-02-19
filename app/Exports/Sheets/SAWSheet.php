<?php

namespace App\Exports\Sheets;

use App\Services\SAWService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SAWSheet implements FromView, ShouldAutoSize, WithTitle
{
    public function view(): View
    {
        $service = new SAWService();
        $hasil = $service->hitung();

        return view('exports.allinone.saw', compact('hasil'));
    }

    public function title(): string
    {
        return '5. Perhitungan SAW';
    }
}
