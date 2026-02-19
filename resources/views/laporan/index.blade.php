@extends('layouts.app')

@section('title', 'Laporan Lengkap')
@section('page-title', 'Laporan Perhitungan SPK')

@section('guide-title', 'Panduan Laporan')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Tentang Halaman Laporan</h6>
    <p>Halaman ini menampilkan <strong>seluruh alur perhitungan SPK</strong> dari awal hingga akhir untuk proyek yang sedang aktif, termasuk metode WP dan SAW secara lengkap dalam satu halaman.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-printer me-1"></i> Cetak / Export</h6>
    <p>Gunakan tombol <strong>Cetak</strong> untuk mencetak halaman ini, atau gunakan <strong>Export Excel</strong> untuk mengunduh file Excel dengan rumus otomatis.</p>
</div>
@endsection

@section('top-actions')
<a href="{{ route('export.word') }}" class="btn btn-outline-primary btn-sm no-print">
    <i class="bi bi-file-earmark-word me-1"></i> Export Word
</a>
<a href="{{ route('export.allinone') }}" class="btn btn-outline-success btn-sm no-print">
    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
</a>
<button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
    <i class="bi bi-printer me-1"></i> Cetak
</button>
@endsection

@section('content')
@php
    $kriteria = $hasilWP['kriteria'];
    $alternatif = $hasilWP['alternatif'];
    $matriks = $hasilWP['matriks'] ?? [];
    $skala = [1 => 'Sangat Kurang', 2 => 'Kurang', 3 => 'Cukup', 4 => 'Baik', 5 => 'Sangat Baik'];
@endphp

{{-- HEADER --}}
<div class="card mb-4">
    <div class="card-body text-center" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: white; border-radius: 0.75rem;">
        <h3 class="mb-1 fw-bold">LAPORAN SISTEM PENGAMBILAN KEPUTUSAN</h3>
        <h5 class="mb-2">{{ $proyek->nama }}</h5>
        @if($proyek->deskripsi)
        <p class="mb-1 small opacity-75">{{ $proyek->deskripsi }}</p>
        @endif
        <p class="mb-0 small opacity-50">Metode: Weighted Product (WP) &amp; Simple Additive Weighting (SAW) | {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</div>

@if($kriteria->isEmpty() || $alternatif->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: #f59e0b;"></i>
        <p class="text-muted mt-3">Data belum lengkap. Pastikan kriteria, alternatif, dan penilaian sudah diisi.</p>
    </div>
</div>
@else

{{-- SKALA PENILAIAN --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #f0fdf4;">
        <i class="bi bi-star me-2"></i> Skala Penilaian
    </div>
    <div class="card-body py-2">
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            @foreach($skala as $val => $label)
            <span class="badge {{ $val == 1 ? 'bg-danger' : ($val == 2 ? 'bg-warning text-dark' : ($val == 3 ? 'bg-info text-dark' : ($val == 4 ? 'bg-primary' : 'bg-success'))) }} px-3 py-2" style="font-size: 0.85rem;">{{ $val }} = {{ $label }}</span>
            @endforeach
        </div>
    </div>
</div>

{{-- 1. DATA KRITERIA --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #eef2ff;">
        <i class="bi bi-1-circle me-2"></i> Data Kriteria
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode</th>
                        <th>Nama Kriteria</th>
                        <th class="text-center">Bobot</th>
                        <th class="text-center">Jenis</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria as $i => $k)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center"><code>{{ $k->kode }}</code></td>
                        <td>{{ $k->nama }}</td>
                        <td class="text-center fw-bold">{{ $k->bobot }}</td>
                        <td class="text-center">
                            <span class="badge {{ $k->jenis == 'benefit' ? 'badge-benefit' : 'badge-cost' }}">{{ ucfirst($k->jenis) }}</span>
                        </td>
                        <td class="small">{{ $k->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-warning">
                        <td colspan="3" class="text-end fw-bold">Total Bobot</td>
                        <td class="text-center fw-bold">{{ $hasilWP['total_bobot'] ?? $kriteria->sum('bobot') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 2. DATA ALTERNATIF --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #eef2ff;">
        <i class="bi bi-2-circle me-2"></i> Data Alternatif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode</th>
                        <th>Nama Alternatif</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatif as $i => $a)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center"><code>{{ $a->kode }}</code></td>
                        <td>{{ $a->nama }}</td>
                        <td class="small">{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 3. MATRIKS PENILAIAN --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #eef2ff;">
        <i class="bi bi-3-circle me-2"></i> Matriks Penilaian (Data Asli)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Alternatif</th>
                        @foreach($kriteria as $k)
                        <th class="text-center">{{ $k->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatif as $i => $a)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $a->kode }} - {{ $a->nama }}</td>
                        @foreach($kriteria as $k)
                        <td class="text-center">{{ $matriks[$a->id][$k->id] ?? 0 }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- METODE WP --}}
{{-- ============================================================ --}}
<div class="card mb-1" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 0.75rem;">
    <div class="card-body text-white text-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2"></i> METODE WEIGHTED PRODUCT (WP)</h4>
    </div>
</div>

{{-- WP 4. Normalisasi Bobot --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #ede9fe;">
        <i class="bi bi-4-circle me-2"></i> Normalisasi Bobot (Wj = Wi / &Sigma;W)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Kriteria</th>
                        @foreach($kriteria as $k)
                        <th class="text-center">{{ $k->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">Bobot Awal</td>
                        @foreach($kriteria as $k)
                        <td class="text-center">{{ $k->bobot }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Wj (Ternormalisasi)</td>
                        @foreach($kriteria as $k)
                        <td class="text-center">{{ number_format($hasilWP['bobot_normal'][$k->id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Pangkat (Benefit +, Cost -)</td>
                        @foreach($kriteria as $k)
                        @php $w = $hasilWP['bobot_normal'][$k->id] ?? 0; @endphp
                        <td class="text-center {{ $k->jenis == 'cost' ? 'text-danger' : 'text-success' }} fw-bold">
                            {{ $k->jenis == 'cost' ? '-' : '+' }}{{ number_format($w, 4) }}
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- WP 5. Vektor S --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #ede9fe;">
        <i class="bi bi-5-circle me-2"></i> Vektor S (Si = &Pi; Xij<sup>Wj</sup>)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Alternatif</th>
                        @foreach($kriteria as $k)
                        <th class="text-center">{{ $k->kode }}<sup>Wj</sup></th>
                        @endforeach
                        <th class="text-center table-warning">Si</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatif as $a)
                    <tr>
                        <td class="fw-bold">{{ $a->kode }}</td>
                        @foreach($hasilWP['detail_perhitungan'][$a->id] ?? [] as $d)
                        <td class="text-center small">{{ number_format($d['hasil'], 6) }}</td>
                        @endforeach
                        <td class="text-center fw-bold table-warning">{{ number_format($hasilWP['vektor_s'][$a->id] ?? 0, 6) }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-info">
                        <td class="fw-bold" colspan="{{ $kriteria->count() + 1 }}" style="text-align: right;">&Sigma;S</td>
                        <td class="text-center fw-bold">{{ number_format($hasilWP['total_s'] ?? 0, 6) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- WP 6. Vektor V & Ranking --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #ede9fe;">
        <i class="bi bi-6-circle me-2"></i> Vektor V &amp; Ranking (Vi = Si / &Sigma;S)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Rank</th>
                        <th>Alternatif</th>
                        <th class="text-center">Si</th>
                        <th class="text-center">Vi</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasilWP['ranking'] ?? [] as $r)
                    <tr class="{{ $r['rank'] == 1 ? 'table-success' : '' }}">
                        <td class="text-center fw-bold">
                            @if($r['rank'] == 1)<i class="bi bi-trophy-fill text-warning"></i>@endif {{ $r['rank'] }}
                        </td>
                        <td><strong>{{ $r['kode'] }}</strong> - {{ $r['nama'] }}</td>
                        <td class="text-center">{{ number_format($r['vektor_s'], 6) }}</td>
                        <td class="text-center fw-bold">{{ number_format($r['vektor_v'], 6) }}</td>
                        <td class="text-center">{{ $r['persentase'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- METODE SAW --}}
{{-- ============================================================ --}}
<div class="card mb-1" style="background: linear-gradient(135deg, #059669, #10b981); border-radius: 0.75rem;">
    <div class="card-body text-white text-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2"></i> METODE SIMPLE ADDITIVE WEIGHTING (SAW)</h4>
    </div>
</div>

{{-- SAW 7. Normalisasi Matriks --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #dcfce7;">
        <i class="bi bi-7-circle me-2"></i> Normalisasi Matriks (Benefit: Xij/Max | Cost: Min/Xij)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Alternatif</th>
                        @foreach($kriteria as $k)
                        <th class="text-center">{{ $k->kode }}</th>
                        @endforeach
                    </tr>
                    <tr class="table-secondary small">
                        <td class="text-muted">Jenis</td>
                        @foreach($kriteria as $k)
                        <td class="text-center">{{ ucfirst($k->jenis) }}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatif as $a)
                    <tr>
                        <td class="fw-bold">{{ $a->kode }}</td>
                        @foreach($kriteria as $k)
                        <td class="text-center">{{ number_format($hasilSAW['matriks_normal'][$a->id][$k->id] ?? 0, 6) }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SAW 8. Preferensi & Ranking --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background: #dcfce7;">
        <i class="bi bi-8-circle me-2"></i> Nilai Preferensi &amp; Ranking (Vi = &Sigma;Wj &times; Rij)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Rank</th>
                        <th>Alternatif</th>
                        <th class="text-center">Vi (Preferensi)</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasilSAW['ranking'] ?? [] as $r)
                    <tr class="{{ $r['rank'] == 1 ? 'table-success' : '' }}">
                        <td class="text-center fw-bold">
                            @if($r['rank'] == 1)<i class="bi bi-trophy-fill text-warning"></i>@endif {{ $r['rank'] }}
                        </td>
                        <td><strong>{{ $r['kode'] }}</strong> - {{ $r['nama'] }}</td>
                        <td class="text-center fw-bold">{{ number_format($r['preferensi'], 6) }}</td>
                        <td class="text-center">{{ $r['persentase'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- KESIMPULAN --}}
<div class="card mb-4">
    <div class="card-body text-center" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: white; border-radius: 0.75rem;">
        <h5 class="fw-bold mb-3"><i class="bi bi-award me-2"></i> KESIMPULAN</h5>
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="p-3" style="background: rgba(255,255,255,0.1); border-radius: 0.5rem;">
                    <small class="d-block opacity-75">Terbaik Metode WP</small>
                    @if(!empty($hasilWP['ranking']))
                    <h4 class="mb-0 fw-bold text-warning">{{ $hasilWP['ranking'][0]['kode'] }} - {{ $hasilWP['ranking'][0]['nama'] }}</h4>
                    <small>Vi = {{ number_format($hasilWP['ranking'][0]['vektor_v'], 6) }}</small>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3" style="background: rgba(255,255,255,0.1); border-radius: 0.5rem;">
                    <small class="d-block opacity-75">Terbaik Metode SAW</small>
                    @if(!empty($hasilSAW['ranking']))
                    <h4 class="mb-0 fw-bold text-warning">{{ $hasilSAW['ranking'][0]['kode'] }} - {{ $hasilSAW['ranking'][0]['nama'] }}</h4>
                    <small>Vi = {{ number_format($hasilSAW['ranking'][0]['preferensi'], 6) }}</small>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
