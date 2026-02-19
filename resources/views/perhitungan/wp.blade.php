@extends('layouts.app')

@section('title', 'Perhitungan WP')
@section('page-title', 'Perhitungan Weighted Product (WP)')

@section('top-actions')
@if(!empty($hasil['ranking']))
<a href="{{ route('export.hasil') }}" class="btn btn-sm btn-outline-success no-print">
    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
</a>
<button onclick="window.print()" class="btn btn-sm btn-outline-secondary no-print">
    <i class="bi bi-printer me-1"></i> Print
</button>
@endif
@endsection

@section('guide-title', 'Panduan Perhitungan Metode WP')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Tentang Metode Weighted Product</h6>
    <p>Metode <strong>Weighted Product (WP)</strong> adalah salah satu metode MCDM (Multi-Criteria Decision Making) yang menggunakan perkalian untuk menghubungkan rating atribut, dimana rating setiap atribut harus dipangkatkan dengan bobot atribut yang bersangkutan.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Langkah 1: Normalisasi Bobot</h6>
    <p>Bobot setiap kriteria dinormalisasi dengan membagi bobot masing-masing kriteria dengan total bobot:</p>
    <p class="text-center"><strong>W<sub>j</sub> = W<sub>j</sub> / &Sigma; W<sub>j</sub></strong></p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Langkah 2: Hitung Vektor S</h6>
    <p>Vektor S dihitung dengan memangkatkan nilai setiap alternatif terhadap setiap kriteria dengan bobot yang telah dinormalisasi:</p>
    <p class="text-center"><strong>S<sub>i</sub> = &Pi; X<sub>ij</sub><sup>W<sub>j</sub></sup></strong></p>
    <p><i class="bi bi-arrow-right text-success"></i> <strong>Benefit:</strong> pangkat positif (+W<sub>j</sub>)<br>
    <i class="bi bi-arrow-right text-danger"></i> <strong>Cost:</strong> pangkat negatif (-W<sub>j</sub>)</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Langkah 3: Hitung Vektor V</h6>
    <p>Vektor V merupakan preferensi relatif yang dihitung dari vektor S:</p>
    <p class="text-center"><strong>V<sub>i</sub> = S<sub>i</sub> / &Sigma; S<sub>i</sub></strong></p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-4-circle me-1"></i> Langkah 4: Ranking</h6>
    <p>Ranking ditentukan berdasarkan nilai Vektor V terbesar. Alternatif dengan nilai V terbesar merupakan alternatif terbaik.</p>
</div>
@endsection

@section('content')
@if(empty($hasil['ranking']))
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-calculator" style="font-size: 3rem; color: #cbd5e1;"></i>
        <p class="text-muted mt-2 mb-3">Belum ada data untuk diproses. Pastikan data Kriteria, Alternatif, dan Penilaian sudah diinput.</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('kriteria.index') }}" class="btn btn-outline-primary"><i class="bi bi-list-check me-1"></i> Kriteria</a>
            <a href="{{ route('alternatif.index') }}" class="btn btn-outline-success"><i class="bi bi-people me-1"></i> Alternatif</a>
            <a href="{{ route('penilaian.index') }}" class="btn btn-outline-warning"><i class="bi bi-clipboard-data me-1"></i> Penilaian</a>
        </div>
    </div>
</div>
@else

{{-- ============================================= --}}
{{-- LANGKAH 1: MATRIKS KEPUTUSAN --}}
{{-- ============================================= --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-table me-2"></i> Langkah 1: Matriks Keputusan
    </div>
    <div class="card-body">
        <p class="text-muted small">Tabel berikut menampilkan nilai penilaian setiap alternatif terhadap setiap kriteria.</p>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Alternatif</th>
                        @foreach($hasil['kriteria'] as $k)
                        <th class="text-center">
                            {{ $k->kode }} ({{ $k->nama }})
                            <br><small class="badge {{ $k->jenis == 'benefit' ? 'badge-benefit' : 'badge-cost' }}">{{ ucfirst($k->jenis) }}</small>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil['alternatif'] as $a)
                    <tr>
                        <td><strong>{{ $a->kode }}</strong> - {{ $a->nama }}</td>
                        @foreach($hasil['kriteria'] as $k)
                        <td class="text-center">{{ $hasil['matriks'][$a->id][$k->id] ?? '-' }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- LANGKAH 2: NORMALISASI BOBOT --}}
{{-- ============================================= --}}
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <i class="bi bi-sliders me-2"></i> Langkah 2: Normalisasi Bobot
    </div>
    <div class="card-body">
        <p class="text-muted small">Bobot setiap kriteria dinormalisasi: <strong>W<sub>j</sub> = W<sub>j</sub> / &Sigma;W<sub>j</sub></strong></p>
        <p class="small">Total Bobot (&Sigma;W) = <strong>{{ $hasil['total_bobot'] }}</strong></p>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Kriteria</th>
                        <th class="text-center">Bobot Awal (W<sub>j</sub>)</th>
                        <th class="text-center">Perhitungan</th>
                        <th class="text-center">Bobot Normal (W<sub>j</sub>)</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Pangkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil['kriteria'] as $k)
                    @php
                        $wNormal = $hasil['bobot_normal'][$k->id];
                        $pangkat = ($k->jenis === 'cost') ? -$wNormal : $wNormal;
                    @endphp
                    <tr>
                        <td><strong>{{ $k->kode }}</strong> - {{ $k->nama }}</td>
                        <td class="text-center">{{ $k->bobot }}</td>
                        <td class="text-center"><code>{{ $k->bobot }} / {{ $hasil['total_bobot'] }}</code></td>
                        <td class="text-center"><strong>{{ number_format($wNormal, 4) }}</strong></td>
                        <td class="text-center">
                            <span class="badge {{ $k->jenis == 'benefit' ? 'badge-benefit' : 'badge-cost' }}">
                                {{ ucfirst($k->jenis) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <strong class="{{ $k->jenis == 'cost' ? 'text-danger' : 'text-success' }}">
                                {{ number_format($pangkat, 4) }}
                            </strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td class="fw-bold">Total</td>
                        <td class="text-center fw-bold">{{ $hasil['total_bobot'] }}</td>
                        <td></td>
                        <td class="text-center fw-bold">1.0000</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- LANGKAH 3: PERHITUNGAN VEKTOR S --}}
{{-- ============================================= --}}
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <i class="bi bi-calculator me-2"></i> Langkah 3: Perhitungan Vektor S
    </div>
    <div class="card-body">
        <p class="text-muted small">Vektor S dihitung: <strong>S<sub>i</sub> = &Pi; X<sub>ij</sub><sup>W<sub>j</sub></sup></strong></p>

        @foreach($hasil['alternatif'] as $a)
        @if(isset($hasil['detail_perhitungan'][$a->id]))
        <div class="card mb-3 border">
            <div class="card-header bg-light py-2">
                <strong>{{ $a->kode }} - {{ $a->nama }}</strong>
            </div>
            <div class="card-body py-2">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-2">
                        <thead>
                            <tr class="table-light">
                                <th>Kriteria</th>
                                <th class="text-center">Nilai (X<sub>ij</sub>)</th>
                                <th class="text-center">Pangkat (W<sub>j</sub>)</th>
                                <th class="text-center">X<sub>ij</sub><sup>W<sub>j</sub></sup></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil['detail_perhitungan'][$a->id] as $d)
                            <tr>
                                <td>{{ $d['kriteria_kode'] }} - {{ $d['kriteria_nama'] }}
                                    <span class="badge {{ $d['jenis'] == 'benefit' ? 'badge-benefit' : 'badge-cost' }}" style="font-size: 0.6rem;">{{ ucfirst($d['jenis']) }}</span>
                                </td>
                                <td class="text-center">{{ $d['nilai'] }}</td>
                                <td class="text-center {{ $d['pangkat'] < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($d['pangkat'], 4) }}
                                </td>
                                <td class="text-center"><strong>{{ number_format($d['hasil'], 6) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="small mb-0">
                    <strong>S<sub>{{ $a->kode }}</sub></strong> =
                    @foreach($hasil['detail_perhitungan'][$a->id] as $index => $d)
                        {{ $d['nilai'] }}<sup>{{ number_format($d['pangkat'], 4) }}</sup>
                        @if($index < count($hasil['detail_perhitungan'][$a->id]) - 1) &times; @endif
                    @endforeach
                    = <strong class="text-primary">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</strong>
                </p>
            </div>
        </div>
        @endif
        @endforeach

        <!-- Ringkasan Vektor S -->
        <div class="alert alert-warning">
            <h6 class="fw-bold">Ringkasan Vektor S:</h6>
            @foreach($hasil['alternatif'] as $a)
            <span class="me-3">S<sub>{{ $a->kode }}</sub> = <strong>{{ number_format($hasil['vektor_s'][$a->id], 6) }}</strong></span>
            @endforeach
            <br><br>
            <strong>&Sigma; S = {{ number_format($hasil['total_s'], 6) }}</strong>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- LANGKAH 4: PERHITUNGAN VEKTOR V --}}
{{-- ============================================= --}}
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <i class="bi bi-graph-up me-2"></i> Langkah 4: Perhitungan Vektor V (Preferensi Relatif)
    </div>
    <div class="card-body">
        <p class="text-muted small">Vektor V dihitung: <strong>V<sub>i</sub> = S<sub>i</sub> / &Sigma;S</strong></p>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Alternatif</th>
                        <th class="text-center">Vektor S (S<sub>i</sub>)</th>
                        <th class="text-center">Perhitungan</th>
                        <th class="text-center">Vektor V (V<sub>i</sub>)</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil['alternatif'] as $a)
                    <tr>
                        <td><strong>{{ $a->kode }}</strong> - {{ $a->nama }}</td>
                        <td class="text-center">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
                        <td class="text-center">
                            <code>{{ number_format($hasil['vektor_s'][$a->id], 6) }} / {{ number_format($hasil['total_s'], 6) }}</code>
                        </td>
                        <td class="text-center"><strong>{{ number_format($hasil['vektor_v'][$a->id], 6) }}</strong></td>
                        <td class="text-center">{{ number_format($hasil['vektor_v'][$a->id] * 100, 2) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- LANGKAH 5: RANKING --}}
{{-- ============================================= --}}
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <i class="bi bi-trophy me-2"></i> Langkah 5: Ranking Hasil Akhir
    </div>
    <div class="card-body">
        <p class="text-muted small">Ranking ditentukan berdasarkan nilai Vektor V terbesar. Alternatif dengan V terbesar adalah alternatif terbaik.</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Ranking</th>
                        <th>Kode</th>
                        <th>Nama Alternatif</th>
                        <th class="text-center">Vektor S</th>
                        <th class="text-center">Vektor V</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil['ranking'] as $r)
                    <tr class="{{ $r['rank'] == 1 ? 'table-warning' : '' }}">
                        <td class="text-center">
                            @if($r['rank'] == 1)
                                <span class="badge bg-warning text-dark fs-6"><i class="bi bi-trophy-fill"></i> {{ $r['rank'] }}</span>
                            @elseif($r['rank'] == 2)
                                <span class="badge bg-secondary fs-6">{{ $r['rank'] }}</span>
                            @elseif($r['rank'] == 3)
                                <span class="badge fs-6" style="background: #cd7f32; color:#fff;">{{ $r['rank'] }}</span>
                            @else
                                <span class="badge bg-light text-dark fs-6">{{ $r['rank'] }}</span>
                            @endif
                        </td>
                        <td><code>{{ $r['kode'] }}</code></td>
                        <td>
                            <strong>{{ $r['nama'] }}</strong>
                            @if($r['rank'] == 1)
                                <span class="badge bg-success ms-2">Terbaik</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($r['vektor_s'], 6) }}</td>
                        <td class="text-center"><strong>{{ number_format($r['vektor_v'], 6) }}</strong></td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 22px;">
                                    <div class="progress-bar {{ $r['rank'] == 1 ? 'bg-success' : 'bg-primary' }}"
                                         style="width: {{ $r['persentase'] }}%">
                                        {{ $r['persentase'] }}%
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Kesimpulan -->
        @if(!empty($hasil['ranking']))
        @php $best = $hasil['ranking'][0]; @endphp
        <div class="alert alert-success mt-3">
            <h5><i class="bi bi-check-circle-fill me-2"></i> Kesimpulan</h5>
            <p class="mb-0">
                Berdasarkan perhitungan metode <strong>Weighted Product (WP)</strong>,
                alternatif terbaik adalah <strong>{{ $best['kode'] }} - {{ $best['nama'] }}</strong>
                dengan nilai Vektor V = <strong>{{ number_format($best['vektor_v'], 6) }}</strong>
                ({{ $best['persentase'] }}%).
            </p>
        </div>
        @endif
    </div>
</div>

@endif
@endsection
