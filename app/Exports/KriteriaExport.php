<?php

namespace App\Exports;

use App\Models\Kriteria;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class KriteriaExport implements FromView, ShouldAutoSize, WithTitle
{
    private $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    public function view(): View
    {
        $query = Kriteria::orderBy('kode');
        if ($this->proyekId) $query->where('proyek_id', $this->proyekId);
        return view('exports.kriteria', [
            'kriteria' => $query->get(),
        ]);
    }

    public function title(): string
    {
        return 'Data Kriteria';
    }
}
