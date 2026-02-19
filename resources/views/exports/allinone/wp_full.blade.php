@php
    $jumlahKriteria = count($hasil['kriteria']);
    $maxCol = max($jumlahKriteria + 2, 7);
@endphp
<table>
    {{-- ================================================================ --}}
    {{-- HEADER UTAMA --}}
    {{-- ================================================================ --}}
    <thead>
        <tr>
            <th colspan="{{ $maxCol }}" style="background-color: #1e3a5f; color: white; font-size: 16px; text-align: center; height: 40px;">
                <strong>SISTEM PENDUKUNG KEPUTUSAN</strong>
            </th>
        </tr>
        <tr>
            <th colspan="{{ $maxCol }}" style="background-color: #1e3a5f; color: white; font-size: 14px; text-align: center;">
                <strong>METODE WEIGHTED PRODUCT (WP)</strong>
            </th>
        </tr>
        <tr>
            <th colspan="{{ $maxCol }}" style="background-color: #2563eb; color: white; text-align: center;">
                Dokumen Perhitungan Lengkap - Digenerate {{ now()->format('d/m/Y H:i:s') }}
            </th>
        </tr>
    </thead>
    <tbody>
    @if(!empty($hasil['ranking']))

        {{-- ================================================================ --}}
        {{-- BAGIAN A: DATA KRITERIA --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #3b82f6; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                A. DATA KRITERIA
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Bobot</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Jenis</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Keterangan</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['kriteria'] as $index => $k)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $k->kode }}</td>
            <td style="border: 1px solid #000;">{{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            <td style="border: 1px solid #000;">{{ $k->keterangan ?? '-' }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="border: 1px solid #000; font-weight: bold;">Total Bobot</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #fef3c7;">{{ $hasil['total_bobot'] }}</td>
            <td colspan="{{ $maxCol - 4 }}" style="border: 1px solid #000;"></td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; background-color: #eff6ff; font-style: italic;">
                Keterangan: Benefit = semakin besar semakin baik | Cost = semakin kecil semakin baik
            </td>
        </tr>

        {{-- ================================================================ --}}
        {{-- BAGIAN B: DATA ALTERNATIF --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #3b82f6; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                B. DATA ALTERNATIF
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Keterangan</td>
            @for($i = 4; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['alternatif'] as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $a->kode }}</td>
            <td style="border: 1px solid #000;">{{ $a->nama }}</td>
            <td style="border: 1px solid #000;">{{ $a->keterangan ?? '-' }}</td>
            @for($i = 4; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-weight: bold;">Total Alternatif: {{ count($hasil['alternatif']) }}</td>
        </tr>

        {{-- ================================================================ --}}
        {{-- BAGIAN C: MATRIKS PENILAIAN --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #3b82f6; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                C. MATRIKS PENILAIAN (Xij) - Data Asli
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Alternatif</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ $k->kode }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        {{-- Sub header jenis --}}
        <tr style="background-color: #eff6ff;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; font-style: italic;">Jenis</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-style: italic; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        {{-- Sub header bobot --}}
        <tr style="background-color: #eff6ff;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; font-style: italic;">Bobot</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-style: italic; text-align: center;">{{ $k->bobot }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['alternatif'] as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; font-weight: bold;">{{ $a->kode }} - {{ $a->nama }}</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center;">{{ $hasil['matriks'][$a->id][$k->id] ?? '-' }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- ================================================================ --}}
        {{-- BAGIAN D: NORMALISASI BOBOT --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #3b82f6; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                D. NORMALISASI BOBOT (Wj = Wj / Total Bobot)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #eff6ff;">
                Rumus: Wj = Bobot Kriteria / Total Bobot ({{ $hasil['total_bobot'] }})
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Bobot Awal</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Perhitungan</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Bobot Normal</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Jenis</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Pangkat (W)</td>
            @for($i = 7; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['kriteria'] as $index => $k)
        @php
            $wn = $hasil['bobot_normal'][$k->id];
            $pangkat = ($k->jenis === 'cost') ? -$wn : $wn;
        @endphp
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $k->kode }} - {{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }} / {{ $hasil['total_bobot'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($wn, 4) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ number_format($pangkat, 4) }}</td>
            @for($i = 7; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach
        <tr style="background-color: #fef3c7;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $hasil['total_bobot'] }}</td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ number_format(array_sum($hasil['bobot_normal']), 4) }}</td>
            <td colspan="{{ $maxCol - 5 }}" style="border: 1px solid #000;"></td>
        </tr>

        {{-- ================================================================ --}}
        {{-- BAGIAN E: PERHITUNGAN VEKTOR S --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #3b82f6; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                E. PERHITUNGAN VEKTOR S
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #eff6ff;">
                Rumus: Si = X1^W1 x X2^W2 x ... x Xn^Wn (Benefit: +W, Cost: -W)
            </td>
        </tr>

        {{-- Detail per alternatif --}}
        @foreach($hasil['alternatif'] as $a)
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="font-weight: bold; background-color: #e0e7ff; border: 1px solid #000;">
                Vektor S untuk {{ $a->kode }} - {{ $a->nama }}
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nilai (Xij)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Pangkat (W)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Perhitungan</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Hasil (Xij^W)</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @if(isset($hasil['detail_perhitungan'][$a->id]))
        @foreach($hasil['detail_perhitungan'][$a->id] as $di => $detail)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $di + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $detail['kriteria_kode'] }} - {{ $detail['kriteria_nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['nilai'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['pangkat'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['nilai'] }} ^ ({{ $detail['pangkat'] }})</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['hasil'] }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach
        @endif
        <tr style="background-color: #fef3c7;">
            <td colspan="5" style="border: 1px solid #000; font-weight: bold; text-align: right;">S({{ $a->kode }}) = Perkalian semua hasil =</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- Ringkasan Vektor S --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #93c5fd; font-weight: bold; border: 1px solid #000;">
                RINGKASAN VEKTOR S
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Vektor S</td>
            @for($i = 4; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['alternatif'] as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $a->kode }}</td>
            <td style="border: 1px solid #000;">{{ $a->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
            @for($i = 4; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach
        <tr style="background-color: #fef3c7;">
            <td colspan="3" style="border: 1px solid #000; font-weight: bold; text-align: right;">Total S =</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ number_format($hasil['total_s'], 6) }}</td>
            @for($i = 4; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>

        {{-- ================================================================ --}}
        {{-- BAGIAN F: PERHITUNGAN VEKTOR V --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #3b82f6; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                F. PERHITUNGAN VEKTOR V (PREFERENSI RELATIF)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #eff6ff;">
                Rumus: Vi = Si / Total S (Total S = {{ number_format($hasil['total_s'], 6) }})
            </td>
        </tr>
        <tr style="background-color: #dbeafe;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Vektor S</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Perhitungan</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Vektor V</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Persentase</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['alternatif'] as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $a->kode }} - {{ $a->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }} / {{ number_format($hasil['total_s'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_v'][$a->id], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_v'][$a->id] * 100, 2) }}%</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- ================================================================ --}}
        {{-- BAGIAN G: RANKING AKHIR --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #16a34a; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                G. RANKING HASIL AKHIR METODE WP
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #dcfce7;">
                Ranking berdasarkan nilai Vektor V terbesar
            </td>
        </tr>
        <tr style="background-color: #bbf7d0;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Ranking</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Vektor S</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Vektor V</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Persentase (%)</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['ranking'] as $r)
        <tr style="background-color: {{ $r['rank'] == 1 ? '#fef3c7' : '#ffffff' }};">
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; font-size: 12px;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $r['kode'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold;">{{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_s'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_v'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['persentase'] }}%</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- ================================================================ --}}
        {{-- KESIMPULAN --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #16a34a; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                KESIMPULAN METODE WP
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; background-color: #dcfce7;">
                Berdasarkan perhitungan metode Weighted Product (WP), alternatif terbaik adalah "{{ $hasil['ranking'][0]['nama'] }}" ({{ $hasil['ranking'][0]['kode'] }}) dengan nilai Vektor V = {{ number_format($hasil['ranking'][0]['vektor_v'], 6) }} ({{ $hasil['ranking'][0]['persentase'] }}%).
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; background-color: #f0fdf4;">
                Alternatif ini memperoleh ranking tertinggi dibandingkan {{ count($hasil['ranking']) - 1 }} alternatif lainnya berdasarkan {{ $jumlahKriteria }} kriteria yang digunakan.
            </td>
        </tr>

    @else
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="text-align: center; font-style: italic;">Belum ada data perhitungan. Harap lengkapi data kriteria, alternatif, dan penilaian.</td>
        </tr>
    @endif
    </tbody>
</table>
