<table>
    <thead>
        <tr>
            <th colspan="{{ count($hasil['kriteria']) + 6 }}" style="background-color: #4f46e5; color: white; font-size: 14px; text-align: center;">
                <strong>PERHITUNGAN METODE WEIGHTED PRODUCT (WP)</strong>
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($hasil['kriteria']) + 6 }}" style="text-align: center;">
                <em>Langkah-langkah perhitungan lengkap dengan rumus</em>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($hasil['ranking']))
        {{-- ================================================ --}}
        {{-- LANGKAH 1: MATRIKS KEPUTUSAN --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dbeafe; font-weight: bold; border: 1px solid #000;">
                LANGKAH 1: MATRIKS KEPUTUSAN (Xij)
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Matriks keputusan berisi nilai asli setiap alternatif pada masing-masing kriteria
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold;">Alternatif</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ $k->kode }} ({{ ucfirst($k->jenis) }})</td>
            @endforeach
        </tr>
        @foreach($hasil['alternatif'] as $a)
        <tr>
            <td style="border: 1px solid #000;">{{ $a->kode }}</td>
            <td style="border: 1px solid #000;">{{ $a->nama }}</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center;">{{ $hasil['matriks'][$a->id][$k->id] ?? '-' }}</td>
            @endforeach
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- LANGKAH 2: NORMALISASI BOBOT --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dbeafe; font-weight: bold; border: 1px solid #000;">
                LANGKAH 2: NORMALISASI BOBOT (Wj)
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Rumus: Wj = Wj / ΣWj (Total Bobot = {{ $hasil['total_bobot'] }})
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold;">Bobot Awal (Wj)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rumus</td>
            <td style="border: 1px solid #000; font-weight: bold;">Bobot Normal</td>
            <td style="border: 1px solid #000; font-weight: bold;">Jenis</td>
            <td style="border: 1px solid #000; font-weight: bold;">Pangkat (±Wj)</td>
        </tr>
        @foreach($hasil['kriteria'] as $k)
        @php
            $wn = $hasil['bobot_normal'][$k->id];
            $pangkat = ($k->jenis === 'cost') ? -$wn : $wn;
        @endphp
        <tr>
            <td style="border: 1px solid #000;">{{ $k->kode }} - {{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }} / {{ $hasil['total_bobot'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($wn, 4) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($pangkat, 4) }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid #000; font-weight: bold;">Total</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $hasil['total_bobot'] }}</td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ number_format(array_sum($hasil['bobot_normal']), 4) }}</td>
            <td colspan="2"></td>
        </tr>

        {{-- ================================================ --}}
        {{-- LANGKAH 3: PERHITUNGAN VEKTOR S --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dbeafe; font-weight: bold; border: 1px solid #000;">
                LANGKAH 3: PERHITUNGAN VEKTOR S
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Rumus: Si = Π (Xij ^ Wj) | Benefit: pangkat +Wj | Cost: pangkat -Wj
            </td>
        </tr>
        {{-- Detail per alternatif --}}
        @foreach($hasil['alternatif'] as $a)
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-weight: bold; background-color: #f1f5f9; border: 1px solid #000;">
                {{ $a->kode }} - {{ $a->nama }}
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nilai (Xij)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Pangkat (Wj)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rumus</td>
            <td style="border: 1px solid #000; font-weight: bold;">Hasil (Xij^Wj)</td>
        </tr>
        @if(isset($hasil['detail_perhitungan'][$a->id]))
        @foreach($hasil['detail_perhitungan'][$a->id] as $detail)
        <tr>
            <td style="border: 1px solid #000;">{{ $detail['kriteria_kode'] }} - {{ $detail['kriteria_nama'] }} ({{ ucfirst($detail['jenis']) }})</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['nilai'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['pangkat'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['nilai'] }} ^ ({{ $detail['pangkat'] }})</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $detail['hasil'] }}</td>
        </tr>
        @endforeach
        @endif
        <tr>
            <td colspan="4" style="border: 1px solid #000; font-weight: bold; text-align: right;">Vektor S ({{ $a->kode }}) =</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #fef3c7;">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
        </tr>
        @endforeach

        {{-- Ringkasan Vektor S --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dbeafe; font-weight: bold; border: 1px solid #000;">
                RINGKASAN VEKTOR S
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold;">Vektor S</td>
        </tr>
        @foreach($hasil['alternatif'] as $a)
        <tr>
            <td style="border: 1px solid #000;">{{ $a->kode }} - {{ $a->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid #000; font-weight: bold;">Total S (ΣSi)</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #fef3c7;">{{ number_format($hasil['total_s'], 6) }}</td>
        </tr>

        {{-- ================================================ --}}
        {{-- LANGKAH 4: PERHITUNGAN VEKTOR V --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dbeafe; font-weight: bold; border: 1px solid #000;">
                LANGKAH 4: PERHITUNGAN VEKTOR V (PREFERENSI RELATIF)
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Rumus: Vi = Si / ΣSi (Total S = {{ number_format($hasil['total_s'], 6) }})
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold;">Vektor S</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rumus</td>
            <td style="border: 1px solid #000; font-weight: bold;">Vektor V</td>
            <td style="border: 1px solid #000; font-weight: bold;">Persentase (%)</td>
        </tr>
        @foreach($hasil['alternatif'] as $a)
        <tr>
            <td style="border: 1px solid #000;">{{ $a->kode }} - {{ $a->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_s'][$a->id], 6) }} / {{ number_format($hasil['total_s'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_v'][$a->id], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['vektor_v'][$a->id] * 100, 2) }}%</td>
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- LANGKAH 5: RANKING AKHIR --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dcfce7; font-weight: bold; border: 1px solid #000;">
                LANGKAH 5: RANKING HASIL AKHIR METODE WP
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Ranking berdasarkan nilai Vektor V terbesar
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Ranking</td>
            <td style="border: 1px solid #000; font-weight: bold;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold;">Vektor S</td>
            <td style="border: 1px solid #000; font-weight: bold;">Vektor V</td>
            <td style="border: 1px solid #000; font-weight: bold;">Persentase (%)</td>
        </tr>
        @foreach($hasil['ranking'] as $r)
        <tr style="{{ $r['rank'] == 1 ? 'background-color: #fef3c7;' : '' }}">
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['kode'] }}</td>
            <td style="border: 1px solid #000;">{{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_s'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_v'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['persentase'] }}%</td>
        </tr>
        @endforeach

        {{-- Kesimpulan --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="6" style="background-color: #dcfce7; border: 1px solid #000; font-weight: bold;">
                KESIMPULAN METODE WP:
            </td>
        </tr>
        @if(!empty($hasil['ranking']))
        <tr>
            <td colspan="6" style="border: 1px solid #000;">
                Berdasarkan perhitungan metode Weighted Product (WP), alternatif terbaik adalah "{{ $hasil['ranking'][0]['nama'] }}" ({{ $hasil['ranking'][0]['kode'] }}) dengan nilai Vektor V = {{ number_format($hasil['ranking'][0]['vektor_v'], 6) }} ({{ $hasil['ranking'][0]['persentase'] }}%).
            </td>
        </tr>
        @endif

        @else
        <tr><td></td></tr>
        <tr>
            <td colspan="6" style="text-align: center; font-style: italic;">Belum ada data perhitungan. Harap lengkapi data kriteria, alternatif, dan penilaian.</td>
        </tr>
        @endif
    </tbody>
</table>
