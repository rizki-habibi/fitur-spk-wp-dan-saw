<table>
    <thead>
        <tr>
            <th colspan="8" style="background-color: #dc2626; color: white; font-size: 14px; text-align: center;">
                <strong>PERBANDINGAN HASIL METODE WP &amp; SAW</strong>
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                <em>Analisis konsistensi dan perbandingan ranking kedua metode</em>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($hasilWP['ranking']) && !empty($hasilSAW['ranking']))

        {{-- ================================================ --}}
        {{-- RANKING WP --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="background-color: #dbeafe; font-weight: bold; border: 1px solid #000;">
                RANKING METODE WEIGHTED PRODUCT (WP)
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
        @foreach($hasilWP['ranking'] as $r)
        <tr style="{{ $r['rank'] == 1 ? 'background-color: #fef3c7;' : '' }}">
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['kode'] }}</td>
            <td style="border: 1px solid #000;">{{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_s'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_v'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['persentase'] }}%</td>
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- RANKING SAW --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="background-color: #d1fae5; font-weight: bold; border: 1px solid #000;">
                RANKING METODE SIMPLE ADDITIVE WEIGHTING (SAW)
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Ranking</td>
            <td style="border: 1px solid #000; font-weight: bold;">Kode</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nama Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nilai Preferensi (Vi)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Persentase (%)</td>
        </tr>
        @foreach($hasilSAW['ranking'] as $r)
        <tr style="{{ $r['rank'] == 1 ? 'background-color: #fef3c7;' : '' }}">
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['kode'] }}</td>
            <td style="border: 1px solid #000;">{{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['preferensi'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['persentase'] }}%</td>
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- TABEL PERBANDINGAN --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="background-color: #fef3c7; font-weight: bold; border: 1px solid #000;">
                TABEL PERBANDINGAN RANKING WP vs SAW
            </td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td style="border: 1px solid #000; font-weight: bold;">Alternatif</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rank WP</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nilai WP (Vektor V)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Rank SAW</td>
            <td style="border: 1px solid #000; font-weight: bold;">Nilai SAW (Vi)</td>
            <td style="border: 1px solid #000; font-weight: bold;">Selisih Rank</td>
            <td style="border: 1px solid #000; font-weight: bold;">Status</td>
        </tr>
        @php
            $wpRanks = [];
            foreach ($hasilWP['ranking'] as $r) {
                $wpRanks[$r['alternatif_id']] = $r;
            }
            $sawRanks = [];
            foreach ($hasilSAW['ranking'] as $r) {
                $sawRanks[$r['alternatif_id']] = $r;
            }
            $konsisten = 0;
            $total = 0;
        @endphp
        @foreach($hasilWP['ranking'] as $r)
        @php
            $sawR = $sawRanks[$r['alternatif_id']] ?? null;
            $selisih = $sawR ? abs($r['rank'] - $sawR['rank']) : '-';
            $status = ($selisih === 0) ? 'Konsisten' : 'Berbeda';
            if ($selisih === 0) $konsisten++;
            $total++;
        @endphp
        <tr>
            <td style="border: 1px solid #000;">{{ $r['kode'] }} - {{ $r['nama'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $r['rank'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ number_format($r['vektor_v'], 6) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $sawR ? $sawR['rank'] : '-' }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $sawR ? number_format($sawR['preferensi'], 6) : '-' }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $selisih }}</td>
            <td style="border: 1px solid #000; text-align: center; background-color: {{ $status === 'Konsisten' ? '#dcfce7' : '#fee2e2' }};">{{ $status }}</td>
        </tr>
        @endforeach

        {{-- ================================================ --}}
        {{-- KESIMPULAN AKHIR --}}
        {{-- ================================================ --}}
        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="background-color: #dcfce7; font-weight: bold; border: 1px solid #000;">
                KESIMPULAN PERBANDINGAN
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold;">Tingkat Konsistensi:</td>
            <td colspan="6" style="border: 1px solid #000;">{{ $total > 0 ? round(($konsisten / $total) * 100, 1) : 0 }}% ({{ $konsisten }} dari {{ $total }} alternatif memiliki ranking sama)</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold;">Terbaik Metode WP:</td>
            <td colspan="6" style="border: 1px solid #000;">{{ $hasilWP['ranking'][0]['nama'] }} ({{ $hasilWP['ranking'][0]['kode'] }}) - Vektor V = {{ number_format($hasilWP['ranking'][0]['vektor_v'], 6) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold;">Terbaik Metode SAW:</td>
            <td colspan="6" style="border: 1px solid #000;">{{ $hasilSAW['ranking'][0]['nama'] }} ({{ $hasilSAW['ranking'][0]['kode'] }}) - Vi = {{ number_format($hasilSAW['ranking'][0]['preferensi'], 6) }}</td>
        </tr>
        @php
            $samaBest = ($hasilWP['ranking'][0]['alternatif_id'] === $hasilSAW['ranking'][0]['alternatif_id']);
        @endphp
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold;">Keputusan:</td>
            <td colspan="6" style="border: 1px solid #000; background-color: {{ $samaBest ? '#dcfce7' : '#fef3c7' }};">
                @if($samaBest)
                Kedua metode WP dan SAW menghasilkan keputusan yang SAMA. Alternatif terbaik adalah "{{ $hasilWP['ranking'][0]['nama'] }}" ({{ $hasilWP['ranking'][0]['kode'] }}). Hasil ini memperkuat validitas keputusan.
                @else
                Kedua metode menghasilkan keputusan yang BERBEDA. WP merekomendasikan "{{ $hasilWP['ranking'][0]['nama'] }}" sedangkan SAW merekomendasikan "{{ $hasilSAW['ranking'][0]['nama'] }}". Disarankan untuk melakukan analisis lebih lanjut.
                @endif
            </td>
        </tr>

        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000;">
                CATATAN METODOLOGI
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold;">Metode WP:</td>
            <td colspan="6" style="border: 1px solid #000;">Weighted Product menggunakan perkalian untuk menghubungkan rating atribut, dimana rating dipangkatkan dengan bobot. Cocok untuk data dengan rentang nilai yang bervariasi.</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold;">Metode SAW:</td>
            <td colspan="6" style="border: 1px solid #000;">Simple Additive Weighting menggunakan penjumlahan terbobot dari rating yang sudah dinormalisasi. Cocok untuk data yang sudah dalam skala yang sama atau dapat dinormalisasi dengan mudah.</td>
        </tr>

        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="font-style: italic; text-align: center;">
                Dokumen ini digenerate otomatis oleh Sistem Pendukung Keputusan (SPK) Multi Metode pada {{ now()->format('d/m/Y H:i:s') }}
            </td>
        </tr>

        @else
        <tr><td></td></tr>
        <tr>
            <td colspan="8" style="text-align: center; font-style: italic;">Belum ada data perhitungan. Harap lengkapi data kriteria, alternatif, dan penilaian.</td>
        </tr>
        @endif
    </tbody>
</table>
