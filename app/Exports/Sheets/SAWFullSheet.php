<?php

namespace App\Exports\Sheets;

use App\Services\SAWService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SAWFullSheet implements FromView, ShouldAutoSize, WithTitle
{
    public function view(): View
    {
        $service = new SAWService();
        $hasil = $service->hitung();
        return view('exports.allinone.saw_full', compact('hasil'));
    }

    public function title(): string
    {
        return 'Metode SAW';
    }
}
