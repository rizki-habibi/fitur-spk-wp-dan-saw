<?php

namespace App\Imports;

use App\Models\Alternatif;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlternatifImport implements ToModel, WithHeadingRow, WithValidation
{
    private $proyekId;

    public function __construct($proyekId = null)
    {
        $this->proyekId = $proyekId;
    }

    public function model(array $row)
    {
        return new Alternatif([
            'kode' => $row['kode'],
            'nama' => $row['nama'],
            'keterangan' => $row['keterangan'] ?? null,
            'proyek_id' => $this->proyekId,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
        ];
    }
}
