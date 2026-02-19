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
            <th colspan="{{ $maxCol }}" style="background-color: #064e3b; color: white; font-size: 16px; text-align: center; height: 40px;">
                <strong>SISTEM PENDUKUNG KEPUTUSAN</strong>
            </th>
        </tr>
        <tr>
            <th colspan="{{ $maxCol }}" style="background-color: #064e3b; color: white; font-size: 14px; text-align: center;">
                <strong>METODE SIMPLE ADDITIVE WEIGHTING (SAW)</strong>
            </th>
        </tr>
        <tr>
            <th colspan="{{ $maxCol }}" style="background-color: #059669; color: white; text-align: center;">
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
            <td colspan="{{ $maxCol }}" style="background-color: #059669; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                A. DATA KRITERIA
            </td>
        </tr>
        <tr style="background-color: #d1fae5;">
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
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; background-color: #ecfdf5; font-style: italic;">
                Keterangan: Benefit = semakin besar semakin baik | Cost = semakin kecil semakin baik
            </td>
        </tr>

        {{-- ================================================================ --}}
        {{-- BAGIAN B: DATA ALTERNATIF --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #059669; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                B. DATA ALTERNATIF
            </td>
        </tr>
        <tr style="background-color: #d1fae5;">
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
        {{-- BAGIAN C: MATRIKS PENILAIAN + MAX/MIN --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #059669; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                C. MATRIKS PENILAIAN (Xij) - Data Asli
            </td>
        </tr>
        <tr style="background-color: #d1fae5;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Alternatif</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ $k->kode }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        {{-- Sub header jenis --}}
        <tr style="background-color: #ecfdf5;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; font-style: italic;">Jenis</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-style: italic; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        {{-- Sub header bobot --}}
        <tr style="background-color: #ecfdf5;">
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
        {{-- Max row --}}
        <tr style="background-color: #fef3c7;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; font-weight: bold;">MAX</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $hasil['max_kriteria'][$k->id] }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        {{-- Min row --}}
        <tr style="background-color: #fef3c7;">
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; font-weight: bold;">MIN</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $hasil['min_kriteria'][$k->id] }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>

        {{-- ================================================================ --}}
        {{-- BAGIAN D: NORMALISASI BOBOT --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #059669; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                D. NORMALISASI BOBOT (Wj = Wj / Total Bobot)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #ecfdf5;">
                Rumus: Wj = Bobot Kriteria / Total Bobot ({{ $hasil['total_bobot'] }})
            </td>
        </tr>
        <tr style="background-color: #d1fae5;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Bobot Awal</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Perhitungan</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Bobot Normal (Wj)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Jenis</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['kriteria'] as $index => $k)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $k->kode }} - {{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }} / {{ $hasil['total_bobot'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['bobot_normal'][$k->id], 4) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
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
        {{-- BAGIAN E: NORMALISASI MATRIKS --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #059669; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                E. NORMALISASI MATRIKS KEPUTUSAN (Rij)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #ecfdf5;">
                Benefit: Rij = Xij / Max(Xij) | Cost: Rij = Min(Xij) / Xij
            </td>
        </tr>

        {{-- Detail normalisasi per alternatif --}}
        @foreach($hasil['alternatif'] as $a)
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="font-weight: bold; background-color: #d1fae5; border: 1px solid #000;">
                Normalisasi untuk {{ $a->kode }} - {{ $a->nama }}
            </td>
        </tr>
        <tr style="background-color: #ecfdf5;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nilai (Xij)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Jenis</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Perhitungan</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Hasil (Rij)</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['kriteria'] as $di => $k)
        @if(isset($hasil['detail_normalisasi'][$a->id][$k->id]))
        @php $dn = $hasil['detail_normalisasi'][$a->id][$k->id]; @endphp
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $di + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $k->kode }} - {{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dn['nilai'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($dn['jenis']) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dn['rumus'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($dn['rij'], 6) }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endif
        @endforeach
        @endforeach

        {{-- Matriks Ternormalisasi --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #6ee7b7; font-weight: bold; border: 1px solid #000;">
                MATRIKS TERNORMALISASI (R)
            </td>
        </tr>
        <tr style="background-color: #d1fae5;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Alternatif</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ $k->kode }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['alternatif'] as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $a->kode }} - {{ $a->nama }}</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['matriks_normal'][$a->id][$k->id] ?? 0, 6) }}</td>
            @endforeach
            @for($i = $jumlahKriteria + 2; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- ================================================================ --}}
        {{-- BAGIAN F: PERHITUNGAN PREFERENSI --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #059669; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                F. PERHITUNGAN NILAI PREFERENSI (Vi)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #ecfdf5;">
                Rumus: Vi = (W1 x R1) + (W2 x R2) + ... + (Wn x Rn)
            </td>
        </tr>

        @foreach($hasil['alternatif'] as $a)
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="font-weight: bold; background-color: #d1fae5; border: 1px solid #000;">
                Preferensi untuk {{ $a->kode }} - {{ $a->nama }}
            </td>
        </tr>
        <tr style="background-color: #ecfdf5;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">No</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Wj (Bobot)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Rij (Normal)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Perhitungan</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Wj x Rij</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @if(isset($hasil['detail_preferensi'][$a->id]))
        @foreach($hasil['detail_preferensi'][$a->id] as $di => $dp)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $di + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $dp['kriteria_kode'] }} - {{ $dp['kriteria_nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['bobot_normal'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['rij'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['bobot_normal'] }} x {{ $dp['rij'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['hasil'] }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach
        @endif
        <tr style="background-color: #fef3c7;">
            <td colspan="5" style="border: 1px solid #000; font-weight: bold; text-align: right;">V({{ $a->kode }}) = Jumlah semua =</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ number_format($hasil['preferensi'][$a->id], 6) }}</td>
            @for($i = 6; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- ================================================================ --}}
        {{-- BAGIAN G: RANKING AKHIR --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #15803d; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                G. RANKING HASIL AKHIR METODE SAW
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; font-style: italic; background-color: #dcfce7;">
                Ranking berdasarkan nilai preferensi (Vi) terbesar
            </td>
        </tr>
        <tr style="background-color: #bbf7d0;">
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Ranking</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Nilai Preferensi (Vi)</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">Persentase (%)</td>
            @for($i = 5; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @foreach($hasil['ranking'] as $r)
        <tr style="background-color: {{ $r['rank'] == 1 ? '#fef3c7' : '#ffffff' }};">
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; font-size: 12px;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $r['kode'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold;">{{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['preferensi'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['persentase'] }}%</td>
            @for($i = 5; $i < $maxCol; $i++)<td style="border: 1px solid #000;"></td>@endfor
        </tr>
        @endforeach

        {{-- ================================================================ --}}
        {{-- KESIMPULAN --}}
        {{-- ================================================================ --}}
        <tr><td colspan="{{ $maxCol }}"></td></tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="background-color: #15803d; color: white; font-weight: bold; font-size: 12px; border: 1px solid #000;">
                KESIMPULAN METODE SAW
            </td>
        </tr>
        <tr>
            <td colspan="{{ $maxCol }}" style="border: 1px solid #000; background-color: #dcfce7;">
                Berdasarkan perhitungan metode Simple Additive Weighting (SAW), alternatif terbaik adalah "{{ $hasil['ranking'][0]['nama'] }}" ({{ $hasil['ranking'][0]['kode'] }}) dengan nilai preferensi Vi = {{ number_format($hasil['ranking'][0]['preferensi'], 6) }} ({{ $hasil['ranking'][0]['persentase'] }}%).
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
