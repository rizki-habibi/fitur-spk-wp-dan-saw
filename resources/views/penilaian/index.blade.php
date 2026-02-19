@extends('layouts.app')

@section('title', 'Penilaian')
@section('page-title', 'Penilaian Alternatif')

@section('guide-title', 'Panduan Penilaian')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Apa itu Penilaian?</h6>
    <p>Penilaian adalah proses memberikan nilai untuk setiap alternatif (calon penerima beasiswa) pada setiap kriteria. Nilai ini yang akan digunakan dalam perhitungan metode WP &amp; SAW.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Cara Mengisi Penilaian</h6>
    <p>Isi semua kolom nilai pada tabel. Setiap baris mewakili satu alternatif, dan setiap kolom mewakili satu kriteria. Pastikan semua sel terisi sebelum menyimpan.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Skala Penilaian</h6>
    <p>Gunakan skala <strong>1-5</strong>: 1 = Sangat Kurang, 2 = Kurang, 3 = Cukup, 4 = Baik, 5 = Sangat Baik. Nilai <strong>tidak boleh 0</strong> untuk perhitungan yang akurat.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Menyimpan Data</h6>
    <p>Klik tombol <strong>"Simpan Semua Penilaian"</strong> untuk menyimpan data. Data yang sudah ada akan diperbarui secara otomatis.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-exclamation-triangle me-1"></i> Catatan Penting</h6>
    <p>Pastikan data Kriteria dan Alternatif sudah diinput terlebih dahulu sebelum melakukan penilaian.</p>
</div>
@endsection

@section('content')
@if($alternatif->isEmpty() || $kriteria->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: #f59e0b;"></i>
        <p class="text-muted mt-2 mb-3">
            @if($kriteria->isEmpty() && $alternatif->isEmpty())
                Data Kriteria dan Alternatif belum tersedia. Silakan tambahkan terlebih dahulu.
            @elseif($kriteria->isEmpty())
                Data Kriteria belum tersedia. Silakan tambahkan data kriteria terlebih dahulu.
            @else
                Data Alternatif belum tersedia. Silakan tambahkan data alternatif terlebih dahulu.
            @endif
        </p>
        <div class="d-flex gap-2 justify-content-center">
            @if($kriteria->isEmpty())
            <a href="{{ route('kriteria.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kriteria
            </a>
            @endif
            @if($alternatif->isEmpty())
            <a href="{{ route('alternatif.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Tambah Alternatif
            </a>
            @endif
        </div>
    </div>
</div>
@else
{{-- Skala Penilaian Legend --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-star me-2"></i> Skala Penilaian</span>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#skalaCollapse" aria-expanded="true">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>
    <div class="collapse show" id="skalaCollapse">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center">
                <span class="badge bg-danger text-white px-3 py-2" style="font-size: 0.85rem;">1 = Sangat Kurang</span>
                <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.85rem;">2 = Kurang</span>
                <span class="badge bg-info text-dark px-3 py-2" style="font-size: 0.85rem;">3 = Cukup</span>
                <span class="badge bg-primary text-white px-3 py-2" style="font-size: 0.85rem;">4 = Baik</span>
                <span class="badge bg-success text-white px-3 py-2" style="font-size: 0.85rem;">5 = Sangat Baik</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-data me-2"></i> Matriks Penilaian</span>
    </div>
    <div class="card-body">
        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="min-width: 50px;">No</th>
                            <th style="min-width: 80px;">Kode</th>
                            <th style="min-width: 150px;">Nama Alternatif</th>
                            @foreach($kriteria as $k)
                            <th class="text-center" style="min-width: 100px;">
                                {{ $k->kode }}<br>
                                <small class="text-muted">{{ $k->nama }}</small><br>
                                @if($k->jenis == 'benefit')
                                    <span class="badge badge-benefit" style="font-size: 0.65rem;">Benefit</span>
                                @else
                                    <span class="badge badge-cost" style="font-size: 0.65rem;">Cost</span>
                                @endif
                            </th>
                            @endforeach
                            <th class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $index => $a)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><code>{{ $a->kode }}</code></td>
                            <td>{{ $a->nama }}</td>
                            @foreach($kriteria as $k)
                            <td>
                                <input type="number"
                                       name="nilai[{{ $a->id }}][{{ $k->id }}]"
                                       class="form-control form-control-sm text-center"
                                       value="{{ $matriks[$a->id][$k->id] ?? '' }}"
                                       min="1" max="5" step="1" required
                                       placeholder="1-5"
                                       style="min-width: 70px;">
                            </td>
                            @endforeach
                            <td class="text-center no-print">
                                <form action="{{ route('penilaian.destroy', $a->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus semua penilaian untuk {{ $a->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus penilaian">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Simpan Semua Penilaian
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
