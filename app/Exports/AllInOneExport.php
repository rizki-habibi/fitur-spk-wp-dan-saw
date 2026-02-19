<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllInOneExport implements WithMultipleSheets
{
    private $proyekNama;
    private $hasilWP;
    private $hasilSAW;

    public function __construct($proyekId = null, $proyekNama = 'SPK', $hasilWP = [], $hasilSAW = [])
    {
        $this->proyekNama = $proyekNama;
        $this->hasilWP = $hasilWP;
        $this->hasilSAW = $hasilSAW;
    }

    public function sheets(): array
    {
        return [
            new Sheets\WPFormulaSheet($this->hasilWP, $this->proyekNama),
            new Sheets\SAWFormulaSheet($this->hasilSAW, $this->proyekNama),
        ];
    }
}
