<?php

namespace App\Exports;

use App\Models\Alternatif;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlternatifExport implements FromView, ShouldAutoSize, WithTitle
{
    private $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    public function view(): View
    {
        $query = Alternatif::orderBy('kode');
        if ($this->proyekId) $query->where('proyek_id', $this->proyekId);
        return view('exports.alternatif', [
            'alternatif' => $query->get(),
        ]);
    }

    public function title(): string
    {
        return 'Data Alternatif';
    }
}
