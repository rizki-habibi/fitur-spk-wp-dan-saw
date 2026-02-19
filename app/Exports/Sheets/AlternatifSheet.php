<?php

namespace App\Exports\Sheets;

use App\Models\Alternatif;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlternatifSheet implements FromView, ShouldAutoSize, WithTitle
{
    public function view(): View
    {
        return view('exports.allinone.alternatif', [
            'alternatif' => Alternatif::orderBy('kode')->get(),
        ]);
    }

    public function title(): string
    {
        return '2. Data Alternatif';
    }
}
