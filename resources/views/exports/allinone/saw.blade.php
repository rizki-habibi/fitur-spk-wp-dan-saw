<table>
    <thead>
        <tr>
            <th colspan="{{ count($hasil['kriteria']) + 6 }}" style="background-color: #059669; color: white; font-size: 14px; text-align: center;">
                <strong>PERHITUNGAN METODE SIMPLE ADDITIVE WEIGHTING (SAW)</strong>
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
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #d1fae5; font-weight: bold; border: 1px solid #000;">
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
        {{-- Max/Min row --}}
        <tr style="background-color: #fef3c7;">
            <td style="border: 1px solid #000;" colspan="2"><strong>Max per Kriteria</strong></td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $hasil['max_kriteria'][$k->id] }}</td>
            @endforeach
        </tr>
        <tr style="background-color: #fef3c7;">
            <td style="border: 1px solid #000;" colspan="2"><strong>Min per Kriteria</strong></td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $hasil['min_kriteria'][$k->id] }}</td>
            @endforeach
        </tr>

        {{-- ================================================ --}}
        {{-- LANGKAH 2: NORMALISASI BOBOT --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #d1fae5; font-weight: bold; border: 1px solid #000;">
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
            <td style="border: 1px solid #000; font-weight: bold;">Bobot Awal</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rumus</td>
            <td style="border: 1px solid #000; font-weight: bold;">Bobot Normal (Wj)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Jenis</td>
        </tr>
        @foreach($hasil['kriteria'] as $k)
        <tr>
            <td style="border: 1px solid #000;">{{ $k->kode }} - {{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }} / {{ $hasil['total_bobot'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['bobot_normal'][$k->id], 4) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($k->jenis) }}</td>
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- LANGKAH 3: NORMALISASI MATRIKS --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #d1fae5; font-weight: bold; border: 1px solid #000;">
                LANGKAH 3: NORMALISASI MATRIKS KEPUTUSAN (Rij)
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Benefit: Rij = Xij / Max(Xij) | Cost: Rij = Min(Xij) / Xij
            </td>
        </tr>

        {{-- Detail normalisasi per alternatif --}}
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
            <td style="border: 1px solid #000; font-weight: bold;">Jenis</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rumus</td>
            <td style="border: 1px solid #000; font-weight: bold;">Hasil (Rij)</td>
        </tr>
        @foreach($hasil['kriteria'] as $k)
        @if(isset($hasil['detail_normalisasi'][$a->id][$k->id]))
        @php $dn = $hasil['detail_normalisasi'][$a->id][$k->id]; @endphp
        <tr>
            <td style="border: 1px solid #000;">{{ $k->kode }} - {{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dn['nilai'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($dn['jenis']) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dn['rumus'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($dn['rij'], 6) }}</td>
        </tr>
        @endif
        @endforeach
        @endforeach

        {{-- Matriks Ternormalisasi --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #d1fae5; font-weight: bold; border: 1px solid #000;">
                MATRIKS TERNORMALISASI (R)
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold;">Alternatif</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ $k->kode }}</td>
            @endforeach
        </tr>
        @foreach($hasil['alternatif'] as $a)
        <tr>
            <td style="border: 1px solid #000;">{{ $a->kode }}</td>
            <td style="border: 1px solid #000;">{{ $a->nama }}</td>
            @foreach($hasil['kriteria'] as $k)
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($hasil['matriks_normal'][$a->id][$k->id] ?? 0, 6) }}</td>
            @endforeach
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- LANGKAH 4: PERHITUNGAN PREFERENSI --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #d1fae5; font-weight: bold; border: 1px solid #000;">
                LANGKAH 4: PERHITUNGAN NILAI PREFERENSI (Vi)
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Rumus: Vi = Σ (Wj × Rij)
            </td>
        </tr>

        @foreach($hasil['alternatif'] as $a)
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-weight: bold; background-color: #f1f5f9; border: 1px solid #000;">
                {{ $a->kode }} - {{ $a->nama }}
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Kriteria</td>
            <td style="border: 1px solid #000; font-weight: bold;">Wj (Bobot Normal)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rij (Normal)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rumus</td>
            <td style="border: 1px solid #000; font-weight: bold;">Wj × Rij</td>
        </tr>
        @if(isset($hasil['detail_preferensi'][$a->id]))
        @foreach($hasil['detail_preferensi'][$a->id] as $dp)
        <tr>
            <td style="border: 1px solid #000;">{{ $dp['kriteria_kode'] }} - {{ $dp['kriteria_nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['bobot_normal'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['rij'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['bobot_normal'] }} × {{ $dp['rij'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $dp['hasil'] }}</td>
        </tr>
        @endforeach
        @endif
        <tr>
            <td colspan="4" style="border: 1px solid #000; font-weight: bold; text-align: right;">Vi ({{ $a->kode }}) =</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #fef3c7;">{{ number_format($hasil['preferensi'][$a->id], 6) }}</td>
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- LANGKAH 5: RANKING AKHIR --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="background-color: #dcfce7; font-weight: bold; border: 1px solid #000;">
                LANGKAH 5: RANKING HASIL AKHIR METODE SAW
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}" style="font-style: italic;">
                Ranking berdasarkan nilai preferensi (Vi) terbesar
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Ranking</td>
            <td style="border: 1px solid #000; font-weight: bold;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nilai Preferensi (Vi)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Persentase (%)</td>
        </tr>
        @foreach($hasil['ranking'] as $r)
        <tr style="{{ $r['rank'] == 1 ? 'background-color: #fef3c7;' : '' }}">
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['kode'] }}</td>
            <td style="border: 1px solid #000;">{{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['preferensi'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['persentase'] }}%</td>
        </tr>
        @endforeach

        {{-- Kesimpulan --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="5" style="background-color: #dcfce7; border: 1px solid #000; font-weight: bold;">
                KESIMPULAN METODE SAW:
            </td>
        </tr>
        @if(!empty($hasil['ranking']))
        <tr>
            <td colspan="5" style="border: 1px solid #000;">
                Berdasarkan perhitungan metode Simple Additive Weighting (SAW), alternatif terbaik adalah "{{ $hasil['ranking'][0]['nama'] }}" ({{ $hasil['ranking'][0]['kode'] }}) dengan nilai preferensi Vi = {{ number_format($hasil['ranking'][0]['preferensi'], 6) }} ({{ $hasil['ranking'][0]['persentase'] }}%).
            </td>
        </tr>
        @endif

        @else
        <tr><td></td></tr>
        <tr>
            <td colspan="5" style="text-align: center; font-style: italic;">Belum ada data perhitungan. Harap lengkapi data kriteria, alternatif, dan penilaian.</td>
        </tr>
        @endif
    </tbody>
</table>
