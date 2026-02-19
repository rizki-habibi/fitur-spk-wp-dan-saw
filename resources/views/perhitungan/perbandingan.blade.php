@extends('layouts.app')

@section('title', 'Perbandingan WP & SAW')
@section('page-title', 'Perbandingan Metode WP & SAW')

@section('top-actions')
<button onclick="window.print()" class="btn btn-sm btn-outline-secondary no-print">
    <i class="bi bi-printer me-1"></i> Print
</button>
@endsection

@section('guide-title', 'Panduan Perbandingan Metode WP & SAW')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Apa itu Perbandingan Metode?</h6>
    <p>Halaman ini membandingkan hasil perhitungan dari dua metode SPK yang berbeda: <strong>Weighted Product (WP)</strong> dan <strong>Simple Additive Weighting (SAW)</strong>. Dengan membandingkan kedua metode, Anda bisa mendapatkan hasil keputusan yang lebih terpercaya.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Metode Weighted Product (WP)</h6>
    <p>Menggunakan <strong>perkalian</strong> untuk menghubungkan rating atribut. Setiap nilai dipangkatkan dengan bobot yang sudah dinormalisasi. Menghasilkan <strong>Vektor S</strong> dan <strong>Vektor V</strong>.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Metode Simple Additive Weighting (SAW)</h6>
    <p>Menggunakan <strong>penjumlahan terbobot</strong>. Matriks keputusan dinormalisasi terlebih dahulu, lalu setiap nilai dikalikan bobot dan dijumlahkan. Menghasilkan <strong>Nilai Preferensi (V<sub>i</sub>)</strong>.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Interpretasi Hasil</h6>
    <p>Jika kedua metode menghasilkan ranking yang <strong>sama atau serupa</strong>, maka keputusan tersebut lebih <strong>reliable</strong>. Jika berbeda, pertimbangkan faktor-faktor lain atau pilih metode yang lebih sesuai dengan konteks masalah.</p>
</div>
@endsection

@section('content')
@if(empty($hasilWP['ranking']) && empty($hasilSAW['ranking']))
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

{{-- Quick Links --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <a href="{{ route('perhitungan.wp') }}" class="text-decoration-none">
            <div class="stat-card" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <p>Metode WP</p>
                <h3>Weighted Product</h3>
                <small>Klik untuk lihat detail perhitungan WP</small>
            </div>
        </a>
    </div>
    <div class="col-md-6 mb-3">
        <a href="{{ route('perhitungan.saw') }}" class="text-decoration-none">
            <div class="stat-card" style="background: linear-gradient(135deg, #059669, #10b981);">
                <div class="stat-icon"><i class="bi bi-bar-chart-line"></i></div>
                <p>Metode SAW</p>
                <h3>Simple Additive Weighting</h3>
                <small>Klik untuk lihat detail perhitungan SAW</small>
            </div>
        </a>
    </div>
</div>

{{-- TABEL RANKING WP --}}
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <i class="bi bi-trophy me-2"></i> Ranking - Metode WP
            </div>
            <div class="card-body">
                @if(!empty($hasilWP['ranking']))
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Rank</th>
                                <th>Alternatif</th>
                                <th class="text-center">Vektor V</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilWP['ranking'] as $r)
                            <tr class="{{ $r['rank'] == 1 ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    @if($r['rank'] == 1)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> {{ $r['rank'] }}</span>
                                    @elseif($r['rank'] == 2)
                                        <span class="badge bg-secondary">{{ $r['rank'] }}</span>
                                    @elseif($r['rank'] == 3)
                                        <span class="badge" style="background: #cd7f32; color:#fff;">{{ $r['rank'] }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $r['rank'] }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $r['kode'] }}</strong> - {{ $r['nama'] }}</td>
                                <td class="text-center"><strong>{{ number_format($r['vektor_v'], 6) }}</strong></td>
                                <td class="text-center">{{ $r['persentase'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @php $bestWP = $hasilWP['ranking'][0]; @endphp
                <div class="alert alert-primary small mb-0">
                    <i class="bi bi-check-circle me-1"></i> Terbaik: <strong>{{ $bestWP['kode'] }} - {{ $bestWP['nama'] }}</strong>
                    (V = {{ number_format($bestWP['vektor_v'], 6) }})
                </div>
                @else
                <p class="text-muted text-center">Belum ada data.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- TABEL RANKING SAW --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #059669, #10b981);">
                <i class="bi bi-trophy me-2"></i> Ranking - Metode SAW
            </div>
            <div class="card-body">
                @if(!empty($hasilSAW['ranking']))
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Rank</th>
                                <th>Alternatif</th>
                                <th class="text-center">Preferensi</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilSAW['ranking'] as $r)
                            <tr class="{{ $r['rank'] == 1 ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    @if($r['rank'] == 1)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> {{ $r['rank'] }}</span>
                                    @elseif($r['rank'] == 2)
                                        <span class="badge bg-secondary">{{ $r['rank'] }}</span>
                                    @elseif($r['rank'] == 3)
                                        <span class="badge" style="background: #cd7f32; color:#fff;">{{ $r['rank'] }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $r['rank'] }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $r['kode'] }}</strong> - {{ $r['nama'] }}</td>
                                <td class="text-center"><strong>{{ number_format($r['preferensi'], 6) }}</strong></td>
                                <td class="text-center">{{ $r['persentase'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @php $bestSAW = $hasilSAW['ranking'][0]; @endphp
                <div class="alert alert-success small mb-0">
                    <i class="bi bi-check-circle me-1"></i> Terbaik: <strong>{{ $bestSAW['kode'] }} - {{ $bestSAW['nama'] }}</strong>
                    (V = {{ number_format($bestSAW['preferensi'], 6) }})
                </div>
                @else
                <p class="text-muted text-center">Belum ada data.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- TABEL PERBANDINGAN RANKING --}}
@if(!empty($hasilWP['ranking']) && !empty($hasilSAW['ranking']))
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-arrows-angle-expand me-2"></i> Tabel Perbandingan Ranking
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Alternatif</th>
                        <th class="text-center" style="background: #4f46e5; color: #fff;">Rank WP</th>
                        <th class="text-center" style="background: #4f46e5; color: #fff;">Nilai WP (V)</th>
                        <th class="text-center" style="background: #059669; color: #fff;">Rank SAW</th>
                        <th class="text-center" style="background: #059669; color: #fff;">Nilai SAW (V)</th>
                        <th class="text-center">Selisih Rank</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Build lookup maps
                        $wpRanks = collect($hasilWP['ranking'])->keyBy('alternatif_id');
                        $sawRanks = collect($hasilSAW['ranking'])->keyBy('alternatif_id');
                        $sameBest = (!empty($hasilWP['ranking'][0]) && !empty($hasilSAW['ranking'][0]))
                                    && $hasilWP['ranking'][0]['alternatif_id'] == $hasilSAW['ranking'][0]['alternatif_id'];
                    @endphp

                    @foreach($hasilWP['ranking'] as $r)
                    @php
                        $sawData = $sawRanks[$r['alternatif_id']] ?? null;
                        $selisih = $sawData ? abs($r['rank'] - $sawData['rank']) : '-';
                    @endphp
                    <tr>
                        <td><code>{{ $r['kode'] }}</code></td>
                        <td><strong>{{ $r['nama'] }}</strong></td>
                        <td class="text-center">
                            @if($r['rank'] == 1)
                                <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> {{ $r['rank'] }}</span>
                            @else
                                {{ $r['rank'] }}
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($r['vektor_v'], 6) }}</td>
                        <td class="text-center">
                            @if($sawData && $sawData['rank'] == 1)
                                <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> {{ $sawData['rank'] }}</span>
                            @else
                                {{ $sawData ? $sawData['rank'] : '-' }}
                            @endif
                        </td>
                        <td class="text-center">{{ $sawData ? number_format($sawData['preferensi'], 6) : '-' }}</td>
                        <td class="text-center">
                            @if($selisih === 0)
                                <span class="badge bg-success">Sama</span>
                            @elseif($selisih !== '-')
                                <span class="badge bg-warning text-dark">{{ $selisih }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($sawData && $r['rank'] == $sawData['rank'])
                                <i class="bi bi-check-circle-fill text-success"></i> Konsisten
                            @else
                                <i class="bi bi-exclamation-circle-fill text-warning"></i> Berbeda
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- KESIMPULAN --}}
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <i class="bi bi-check-circle-fill me-2"></i> Kesimpulan Perbandingan
    </div>
    <div class="card-body">
        @php
            $bestWP = $hasilWP['ranking'][0];
            $bestSAW = $hasilSAW['ranking'][0];
            $consistent = $bestWP['alternatif_id'] == $bestSAW['alternatif_id'];

            // Count how many ranks are consistent
            $totalConsistent = 0;
            $totalItems = count($hasilWP['ranking']);
            foreach ($hasilWP['ranking'] as $r) {
                $sawItem = $sawRanks[$r['alternatif_id']] ?? null;
                if ($sawItem && $r['rank'] == $sawItem['rank']) $totalConsistent++;
            }
            $konsistensiPersen = $totalItems > 0 ? round(($totalConsistent / $totalItems) * 100, 1) : 0;
        @endphp

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h6 class="text-primary">Terbaik WP</h6>
                        <h4>{{ $bestWP['kode'] }}</h4>
                        <p class="mb-0">{{ $bestWP['nama'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h6 class="text-success">Terbaik SAW</h6>
                        <h4>{{ $bestSAW['kode'] }}</h4>
                        <p class="mb-0">{{ $bestSAW['nama'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card {{ $consistent ? 'border-success' : 'border-warning' }}">
                    <div class="card-body text-center">
                        <h6 class="{{ $consistent ? 'text-success' : 'text-warning' }}">Konsistensi</h6>
                        <h4>{{ $konsistensiPersen }}%</h4>
                        <p class="mb-0">{{ $totalConsistent }}/{{ $totalItems }} rank sama</p>
                    </div>
                </div>
            </div>
        </div>

        @if($consistent)
        <div class="alert alert-success">
            <h5><i class="bi bi-check-circle-fill me-2"></i> Hasil Konsisten!</h5>
            <p class="mb-0">
                Kedua metode (WP dan SAW) menghasilkan alternatif terbaik yang <strong>sama</strong>, yaitu
                <strong>{{ $bestWP['kode'] }} - {{ $bestWP['nama'] }}</strong>.
                Ini menunjukkan bahwa keputusan sangat <strong>reliable</strong> dan dapat dipercaya.
                Tingkat konsistensi ranking keseluruhan: <strong>{{ $konsistensiPersen }}%</strong>.
            </p>
        </div>
        @else
        <div class="alert alert-warning">
            <h5><i class="bi bi-exclamation-triangle-fill me-2"></i> Hasil Berbeda</h5>
            <p class="mb-0">
                Metode WP merekomendasikan <strong>{{ $bestWP['kode'] }} - {{ $bestWP['nama'] }}</strong>,
                sedangkan metode SAW merekomendasikan <strong>{{ $bestSAW['kode'] }} - {{ $bestSAW['nama'] }}</strong>.
                Perbedaan ini terjadi karena pendekatan matematika yang berbeda antara kedua metode.
                Pertimbangkan konteks masalah dan pilih metode yang lebih sesuai.
                Tingkat konsistensi: <strong>{{ $konsistensiPersen }}%</strong>.
            </p>
        </div>
        @endif

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-graph-up-arrow text-primary me-1"></i> Metode WP</h6>
                        <ul class="small mb-0">
                            <li>Menggunakan <strong>perkalian</strong> (produk terbobot)</li>
                            <li>Setiap nilai dipangkatkan dengan bobot</li>
                            <li>Cocok ketika kriteria bersifat <strong>multiplikatif</strong></li>
                            <li>Lebih sensitif terhadap perbedaan nilai yang ekstrem</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-bar-chart-line text-success me-1"></i> Metode SAW</h6>
                        <ul class="small mb-0">
                            <li>Menggunakan <strong>penjumlahan</strong> (jumlah terbobot)</li>
                            <li>Matriks dinormalisasi, lalu dikalikan bobot</li>
                            <li>Cocok ketika kriteria bersifat <strong>aditif</strong></li>
                            <li>Lebih mudah dipahami dan diinterpretasikan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endif
@endsection
