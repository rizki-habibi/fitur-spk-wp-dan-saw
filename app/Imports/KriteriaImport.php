<?php

namespace App\Imports;

use App\Models\Kriteria;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KriteriaImport implements ToModel, WithHeadingRow, WithValidation
{
    private $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    public function model(array $row)
    {
        return new Kriteria([
            'kode' => $row['kode'],
            'nama' => $row['nama'],
            'bobot' => $row['bobot'],
            'jenis' => strtolower($row['jenis']),
            'keterangan' => $row['keterangan'] ?? null,
            'proyek_id' => $this->proyekId,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
            'jenis' => 'required|in:benefit,cost,Benefit,Cost,BENEFIT,COST',
        ];
    }
}
