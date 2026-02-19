<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Exports\KriteriaExport;
use App\Exports\AlternatifExport;
use App\Exports\HasilExport;
use App\Exports\AllInOneExport;
use App\Exports\WordExport;
use App\Imports\KriteriaImport;
use App\Imports\AlternatifImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    use HasActiveProyek;

    // ============ EXPORT ============

    public function exportKriteria()
    {
        $proyekId = $this->getActiveProyekId();
        return Excel::download(new KriteriaExport($proyekId), 'data_kriteria.xlsx');
    }

    public function exportAlternatif()
    {
        $proyekId = $this->getActiveProyekId();
        return Excel::download(new AlternatifExport($proyekId), 'data_alternatif.xlsx');
    }

    public function exportHasil()
    {
        $proyekId = $this->getActiveProyekId();
        return Excel::download(new HasilExport($proyekId), 'hasil_perhitungan_wp.xlsx');
    }

    public function exportAllInOne()
    {
        $proyekId = $this->getActiveProyekId();
        $proyek = $this->getActiveProyek();
        $proyekNama = $proyek ? $proyek->nama : 'SPK';

        // Pre-compute all results so Excel always shows values (no formula dependency)
        $wpService = new \App\Services\WeightedProductService($proyekId);
        $sawService = new \App\Services\SAWService($proyekId);
        $hasilWP = $wpService->hitung();
        $hasilSAW = $sawService->hitung();

        $filename = 'SPK_' . str_replace(' ', '_', $proyekNama) . '_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new AllInOneExport($proyekId, $proyekNama, $hasilWP, $hasilSAW), $filename);
    }

    public function exportWord()
    {
        $proyekId = $this->getActiveProyekId();
        $proyek = $this->getActiveProyek();
        $proyekNama = $proyek ? $proyek->nama : 'SPK';
        $proyekDeskripsi = $proyek ? ($proyek->deskripsi ?? '') : '';

        $wpService = new \App\Services\WeightedProductService($proyekId);
        $sawService = new \App\Services\SAWService($proyekId);
        $hasilWP = $wpService->hitung();
        $hasilSAW = $sawService->hitung();

        $export = new WordExport($hasilWP, $hasilSAW, $proyekNama, $proyekDeskripsi);
        $tmpFile = $export->generate();

        $filename = 'Laporan_SPK_' . str_replace(' ', '_', $proyekNama) . '_' . date('Y-m-d_His') . '.docx';

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    // ============ IMPORT ============

    public function importKriteria(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $proyekId = $this->getActiveProyekId();
            Excel::import(new KriteriaImport($proyekId), $request->file('file'));
            return redirect()->back()->with('success', 'Data kriteria berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function importAlternatif(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $proyekId = $this->getActiveProyekId();
            Excel::import(new AlternatifImport($proyekId), $request->file('file'));
            return redirect()->back()->with('success', 'Data alternatif berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
