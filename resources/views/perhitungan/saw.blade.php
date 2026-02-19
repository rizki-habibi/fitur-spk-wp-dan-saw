@extends('layouts.app')

@section('title', 'Perhitungan SAW')
@section('page-title', 'Perhitungan Simple Additive Weighting (SAW)')

@section('top-actions')
@if(!empty($hasil['ranking']))
<button onclick="window.print()" class="btn btn-sm btn-outline-secondary no-print">
    <i class="bi bi-printer me-1"></i> Print
</button>
@endif
@endsection

@section('guide-title', 'Panduan Perhitungan Metode SAW')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Tentang Metode SAW</h6>
    <p>Metode <strong>Simple Additive Weighting (SAW)</strong> dikenal juga dengan metode penjumlahan terbobot. Konsep dasarnya adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Langkah 1: Normalisasi Bobot</h6>
    <p>Bobot setiap kriteria dinormalisasi:</p>
    <p class="text-center"><strong>W<sub>j</sub> = W<sub>j</sub> / &Sigma; W<sub>j</sub></strong></p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Langkah 2: Normalisasi Matriks (R<sub>ij</sub>)</h6>
    <p>Normalisasi matriks keputusan:</p>
    <p><i class="bi bi-arrow-right text-success"></i> <strong>Benefit:</strong> R<sub>ij</sub> = X<sub>ij</sub> / Max(X<sub>ij</sub>)</p>
    <p><i class="bi bi-arrow-right text-danger"></i> <strong>Cost:</strong> R<sub>ij</sub> = Min(X<sub>ij</sub>) / X<sub>ij</sub></p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Langkah 3: Hitung Nilai Preferensi (V<sub>i</sub>)</h6>
    <p>Nilai preferensi dihitung:</p>
    <p class="text-center"><strong>V<sub>i</sub> = &Sigma; (W<sub>j</sub> &times; R<sub>ij</sub>)</strong></p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-4-circle me-1"></i> Langkah 4: Ranking</h6>
    <p>Ranking ditentukan berdasarkan nilai V<sub>i</sub> terbesar. Alternatif dengan V terbesar merupakan alternatif terbaik.</p>
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

{{-- LANGKAH 1: MATRIKS KEPUTUSAN --}}
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

{{-- LANGKAH 2: NORMALISASI BOBOT --}}
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
                        <th class="text-center">Bobot Awal</th>
                        <th class="text-center">Perhitungan</th>
                        <th class="text-center">Bobot Normal</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Max</th>
                        <th class="text-center">Min</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil['kriteria'] as $k)
                    @php $wNormal = $hasil['bobot_normal'][$k->id]; @endphp
                    <tr>
                        <td><strong>{{ $k->kode }}</strong> - {{ $k->nama }}</td>
                        <td class="text-center">{{ $k->bobot }}</td>
                        <td class="text-center"><code>{{ $k->bobot }} / {{ $hasil['total_bobot'] }}</code></td>
                        <td class="text-center"><strong>{{ number_format($wNormal, 4) }}</strong></td>
                        <td class="text-center">
                            <span class="badge {{ $k->jenis == 'benefit' ? 'badge-benefit' : 'badge-cost' }}">{{ ucfirst($k->jenis) }}</span>
                        </td>
                        <td class="text-center">{{ $hasil['max_kriteria'][$k->id] }}</td>
                        <td class="text-center">{{ $hasil['min_kriteria'][$k->id] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td class="fw-bold">Total</td>
                        <td class="text-center fw-bold">{{ $hasil['total_bobot'] }}</td>
                        <td></td>
                        <td class="text-center fw-bold">1.0000</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- LANGKAH 3: NORMALISASI MATRIKS --}}
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <i class="bi bi-calculator me-2"></i> Langkah 3: Normalisasi Matriks (R<sub>ij</sub>)
    </div>
    <div class="card-body">
        <p class="text-muted small">
            <strong>Benefit:</strong> R<sub>ij</sub> = X<sub>ij</sub> / Max(X<sub>ij</sub>) &nbsp;|&nbsp;
            <strong>Cost:</strong> R<sub>ij</sub> = Min(X<sub>ij</sub>) / X<sub>ij</sub>
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Alternatif</th>
                        @foreach($hasil['kriteria'] as $k)
                        <th class="text-center">{{ $k->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil['alternatif'] as $a)
                    <tr>
                        <td><strong>{{ $a->kode }}</strong> - {{ $a->nama }}</td>
                        @foreach($hasil['kriteria'] as $k)
                        @php $dn = $hasil['detail_normalisasi'][$a->id][$k->id]; @endphp
                        <td class="text-center" title="{{ $dn['rumus'] }}">
                            <strong>{{ number_format($dn['rij'], 4) }}</strong>
                            <br><small class="text-muted">{{ $dn['rumus'] }}</small>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- LANGKAH 4: NILAI PREFERENSI --}}
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <i class="bi bi-graph-up me-2"></i> Langkah 4: Perhitungan Nilai Preferensi (V<sub>i</sub>)
    </div>
    <div class="card-body">
        <p class="text-muted small">V<sub>i</sub> = &Sigma; (W<sub>j</sub> &times; R<sub>ij</sub>)</p>

        @foreach($hasil['alternatif'] as $a)
        @if(isset($hasil['detail_preferensi'][$a->id]))
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
                                <th class="text-center">W<sub>j</sub></th>
                                <th class="text-center">R<sub>ij</sub></th>
                                <th class="text-center">W<sub>j</sub> &times; R<sub>ij</sub></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil['detail_preferensi'][$a->id] as $d)
                            <tr>
                                <td>{{ $d['kriteria_kode'] }} - {{ $d['kriteria_nama'] }}</td>
                                <td class="text-center">{{ number_format($d['bobot_normal'], 4) }}</td>
                                <td class="text-center">{{ number_format($d['rij'], 4) }}</td>
                                <td class="text-center"><strong>{{ number_format($d['hasil'], 6) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="small mb-0">
                    <strong>V<sub>{{ $a->kode }}</sub></strong> =
                    @foreach($hasil['detail_preferensi'][$a->id] as $index => $d)
                        ({{ number_format($d['bobot_normal'], 4) }} &times; {{ number_format($d['rij'], 4) }})
                        @if($index < count($hasil['detail_preferensi'][$a->id]) - 1) + @endif
                    @endforeach
                    = <strong class="text-primary">{{ number_format($hasil['preferensi'][$a->id], 6) }}</strong>
                </p>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>

{{-- LANGKAH 5: RANKING --}}
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <i class="bi bi-trophy me-2"></i> Langkah 5: Ranking Hasil Akhir
    </div>
    <div class="card-body">
        <p class="text-muted small">Ranking ditentukan berdasarkan nilai Preferensi (V<sub>i</sub>) terbesar.</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Ranking</th>
                        <th>Kode</th>
                        <th>Nama Alternatif</th>
                        <th class="text-center">Nilai Preferensi (V<sub>i</sub>)</th>
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
                        <td class="text-center"><strong>{{ number_format($r['preferensi'], 6) }}</strong></td>
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

        @if(!empty($hasil['ranking']))
        @php $best = $hasil['ranking'][0]; @endphp
        <div class="alert alert-success mt-3">
            <h5><i class="bi bi-check-circle-fill me-2"></i> Kesimpulan</h5>
            <p class="mb-0">
                Berdasarkan perhitungan metode <strong>Simple Additive Weighting (SAW)</strong>,
                alternatif terbaik adalah <strong>{{ $best['kode'] }} - {{ $best['nama'] }}</strong>
                dengan nilai preferensi = <strong>{{ number_format($best['preferensi'], 6) }}</strong>
                ({{ $best['persentase'] }}%).
            </p>
        </div>
        @endif
    </div>
</div>

@endif
@endsection
