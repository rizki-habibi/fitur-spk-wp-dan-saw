<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        $proyeks = Proyek::withCount(['kriteria', 'alternatif'])->orderBy('nama')->get();
        $activeProyekId = session('proyek_id');
        return view('proyek.index', compact('proyeks', 'activeProyekId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        if (empty($data['icon'])) $data['icon'] = 'bi-folder';
        if (empty($data['warna'])) $data['warna'] = '#4f46e5';

        $proyek = Proyek::create($data);

        // Auto-select the newly created project
        session(['proyek_id' => $proyek->id]);

        return redirect()->route('proyek.index')
            ->with('success', 'Proyek berhasil dibuat!');
    }

    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:20',
        ]);

        $proyek->update($request->all());

        return redirect()->route('proyek.index')
            ->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Proyek $proyek)
    {
        // Hapus proyek beserta semua data terkait (cascade)
        $proyek->delete();

        // Reset session jika proyek aktif dihapus
        if (session('proyek_id') == $proyek->id) {
            session()->forget('proyek_id');
        }

        return redirect()->route('proyek.index')
            ->with('success', 'Proyek berhasil dihapus beserta semua datanya!');
    }

    /**
     * Switch active project
     */
    public function activate(Proyek $proyek)
    {
        session(['proyek_id' => $proyek->id]);

        return redirect()->route('dashboard')
            ->with('success', "Proyek \"{$proyek->nama}\" berhasil dipilih!");
    }
}
