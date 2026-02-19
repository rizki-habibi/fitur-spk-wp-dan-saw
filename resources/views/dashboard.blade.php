@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('guide-title', 'Panduan Dashboard')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Tentang Dashboard</h6>
    <p>Dashboard menampilkan ringkasan data sistem SPK dengan dua metode: <strong>Weighted Product (WP)</strong> dan <strong>Simple Additive Weighting (SAW)</strong>. Anda dapat melihat total kriteria, alternatif, penilaian, serta hasil ranking dari kedua metode.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-bar-chart me-1"></i> Cara Memulai</h6>
    <ol>
        <li>Tambahkan <strong>Kriteria</strong> terlebih dahulu (menu Data Kriteria)</li>
        <li>Tambahkan <strong>Alternatif</strong> yang akan dinilai</li>
        <li>Isi <strong>Penilaian</strong> untuk setiap alternatif pada setiap kriteria</li>
        <li>Lihat <strong>Perhitungan WP</strong> dan <strong>Perhitungan SAW</strong> untuk hasil analisis</li>
        <li>Gunakan <strong>Perbandingan WP & SAW</strong> untuk membandingkan kedua metode</li>
    </ol>
</div>
<div class="guide-step">
    <h6><i class="bi bi-lightbulb me-1"></i> Tips</h6>
    <p>Anda bisa mengimpor data dari file Excel (.xlsx). Semua operasi CRUD menggunakan popup modal. Klik tombol <strong>Panduan</strong> di setiap halaman untuk melihat bantuan.</p>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
            <div class="stat-icon"><i class="bi bi-list-check"></i></div>
            <p>Total Kriteria</p>
            <h3>{{ $totalKriteria }}</h3>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #059669, #10b981);">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <p>Total Alternatif</p>
            <h3>{{ $totalAlternatif }}</h3>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <div class="stat-icon"><i class="bi bi-clipboard-data"></i></div>
            <p>Total Penilaian</p>
            <h3>{{ $totalPenilaian }}</h3>
        </div>
    </div>
</div>

<!-- Ranking Tables -->
<div class="row">
    {{-- WP Ranking --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up-arrow me-2"></i> Ranking WP</span>
                @if(!empty($hasilWP['ranking']))
                <a href="{{ route('perhitungan.wp') }}" class="btn btn-sm btn-outline-primary">
                    Detail <i class="bi bi-arrow-right"></i>
                </a>
                @endif
            </div>
            <div class="card-body">
                @if(!empty($hasilWP['ranking']))
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Alternatif</th>
                                <th>Vektor V</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilWP['ranking'] as $r)
                            <tr>
                                <td>
                                    @if($r['rank'] == 1)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> {{ $r['rank'] }}</span>
                                    @elseif($r['rank'] <= 3)
                                        <span class="badge bg-secondary">{{ $r['rank'] }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $r['rank'] }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $r['kode'] }}</strong> - {{ $r['nama'] }}</td>
                                <td><strong>{{ number_format($r['vektor_v'], 4) }}</strong></td>
                                <td>
                                    <div class="progress" style="height: 20px; min-width: 80px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $r['persentase'] }}%">
                                            {{ $r['persentase'] }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-2 small">Belum ada data perhitungan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SAW Ranking --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart-line me-2"></i> Ranking SAW</span>
                @if(!empty($hasilSAW['ranking']))
                <a href="{{ route('perhitungan.saw') }}" class="btn btn-sm btn-outline-success">
                    Detail <i class="bi bi-arrow-right"></i>
                </a>
                @endif
            </div>
            <div class="card-body">
                @if(!empty($hasilSAW['ranking']))
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Alternatif</th>
                                <th>Preferensi</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilSAW['ranking'] as $r)
                            <tr>
                                <td>
                                    @if($r['rank'] == 1)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> {{ $r['rank'] }}</span>
                                    @elseif($r['rank'] <= 3)
                                        <span class="badge bg-secondary">{{ $r['rank'] }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $r['rank'] }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $r['kode'] }}</strong> - {{ $r['nama'] }}</td>
                                <td><strong>{{ number_format($r['preferensi'], 4) }}</strong></td>
                                <td>
                                    <div class="progress" style="height: 20px; min-width: 80px;">
                                        <div class="progress-bar bg-success" style="width: {{ $r['persentase'] }}%">
                                            {{ $r['persentase'] }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-2 small">Belum ada data perhitungan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Info & Quick Actions -->
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up-arrow me-2 text-primary"></i> Metode WP
            </div>
            <div class="card-body">
                <p class="small"><strong>Weighted Product</strong> menggunakan perkalian untuk menghubungkan rating atribut, dimana rating dipangkatkan dengan bobot.</p>
                <ol class="small">
                    <li>Normalisasi Bobot: W<sub>j</sub> = W<sub>j</sub> / &Sigma;W<sub>j</sub></li>
                    <li>Vektor S = &Pi; X<sub>ij</sub><sup>W<sub>j</sub></sup></li>
                    <li>Vektor V = S<sub>i</sub> / &Sigma;S<sub>i</sub></li>
                </ol>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-bar-chart-line me-2 text-success"></i> Metode SAW
            </div>
            <div class="card-body">
                <p class="small"><strong>Simple Additive Weighting</strong> menggunakan penjumlahan terbobot dari rating kinerja yang sudah dinormalisasi.</p>
                <ol class="small">
                    <li>Normalisasi: Benefit R<sub>ij</sub> = X<sub>ij</sub>/Max | Cost R<sub>ij</sub> = Min/X<sub>ij</sub></li>
                    <li>Preferensi V<sub>i</sub> = &Sigma;(W<sub>j</sub> &times; R<sub>ij</sub>)</li>
                    <li>Ranking berdasarkan V terbesar</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i> Aksi Cepat
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('kriteria.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-list-check me-1"></i> Kelola Kriteria
                </a>
                <a href="{{ route('alternatif.index') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-people me-1"></i> Kelola Alternatif
                </a>
                <a href="{{ route('penilaian.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-clipboard-data me-1"></i> Input Penilaian
                </a>
                <a href="{{ route('perhitungan.perbandingan') }}" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-arrows-angle-expand me-1"></i> Perbandingan WP & SAW
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
