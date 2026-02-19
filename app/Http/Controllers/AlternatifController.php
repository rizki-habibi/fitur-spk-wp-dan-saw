<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasActiveProyek;
use App\Models\Alternatif;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    use HasActiveProyek;

    public function index()
    {
        if ($redirect = $this->requireProyek()) return $redirect;
        $proyekId = $this->getActiveProyekId();

        $alternatif = Alternatif::where('proyek_id', $proyekId)->orderBy('kode')->get();
        return view('alternatif.index', compact('alternatif'));
    }

    public function store(Request $request)
    {
        $proyekId = $this->getActiveProyekId();

        $request->validate([
            'kode' => 'required|string|max:10|unique:alternatif,kode,NULL,id,proyek_id,' . $proyekId,
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Alternatif::create(array_merge($request->all(), ['proyek_id' => $proyekId]));

        return redirect()->route('alternatif.index')
            ->with('success', 'Alternatif berhasil ditambahkan!');
    }

    public function update(Request $request, Alternatif $alternatif)
    {
        $proyekId = $this->getActiveProyekId();

        $request->validate([
            'kode' => 'required|string|max:10|unique:alternatif,kode,' . $alternatif->id . ',id,proyek_id,' . $proyekId,
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $alternatif->update($request->all());

        return redirect()->route('alternatif.index')
            ->with('success', 'Alternatif berhasil diperbarui!');
    }

    public function destroy(Alternatif $alternatif)
    {
        $alternatif->penilaian()->delete();
        $alternatif->delete();

        return redirect()->route('alternatif.index')
            ->with('success', 'Alternatif berhasil dihapus!');
    }
}
