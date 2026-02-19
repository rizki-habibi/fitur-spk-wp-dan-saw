<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    use HasActiveProyek;

    public function index()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();

        $kriteria = Kriteria::where('proyek_id', $proyekId)->orderBy('kode')->get();
        $totalBobot = $kriteria->sum('bobot');
        return view('kriteria.index', compact('kriteria', 'totalBobot'));
    }

    public function store(Request $request)
    {
        $proyekId = $this->getActiveProyekId();

        $request->validate([
            'kode' => 'required|string|max:10|unique:kriteria,kode,NULL,id,proyek_id,' . $proyekId,
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
            'jenis' => 'required|in:benefit,cost',
            'keterangan' => 'nullable|string',
        ]);

        Kriteria::create(array_merge($request->all(), ['proyek_id' => $proyekId]));

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $proyekId = $this->getActiveProyekId();

        $request->validate([
            'kode' => 'required|string|max:10|unique:kriteria,kode,' . $kriteria->id . ',id,proyek_id,' . $proyekId,
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
            'jenis' => 'required|in:benefit,cost',
            'keterangan' => 'nullable|string',
        ]);

        $kriteria->update($request->all());

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->penilaian()->delete();
        $kriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus!');
    }
}
