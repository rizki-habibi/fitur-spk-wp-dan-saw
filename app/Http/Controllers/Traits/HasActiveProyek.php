<?php

namespace App\Http\Controllers\Traits;

use App\Models\Proyek;

trait HasActiveProyek
{
    protected function getActiveProyek(): ?Proyek
    {
        $proyekId = session('proyek_id');
        if (!$proyekId) return null;
        return Proyek::find($proyekId);
    }

    protected function getActiveProyekId(): ?int
    {
        return session('proyek_id');
    }

    protected function requireProyek()
    {
        $proyek = $this->getActiveProyek();
        if (!$proyek) {
            return redirect()->route('proyek.index')
                ->with('error', 'Silakan pilih proyek terlebih dahulu!');
        }
        return null;
    }
}
