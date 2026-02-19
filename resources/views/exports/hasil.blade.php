<table>
    <thead>
        <tr>
            <th colspan="{{ count($hasil['kriteria']) + 5 }}"><strong>HASIL PERHITUNGAN SPK METODE WEIGHTED PRODUCT (WP)</strong></th>
        </tr>
        <tr><td></td></tr>

        {{-- Matriks Keputusan --}}
        <tr>
            <th colspan="{{ count($hasil['kriteria']) + 3 }}"><strong>MATRIKS KEPUTUSAN</strong></th>
        </tr>
        <tr>
            <th><strong>Kode</strong></th>
            <th><strong>Alternatif</strong></th>
            @foreach($hasil['kriteria'] as $k)
            <th><strong>{{ $k->kode }} ({{ $k->nama }})</strong></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($hasil['alternatif'] as $a)
        <tr>
            <td>{{ $a->kode }}</td>
            <td>{{ $a->nama }}</td>
            @foreach($hasil['kriteria'] as $k)
            <td>{{ $hasil['matriks'][$a->id][$k->id] ?? '-' }}</td>
            @endforeach
        </tr>
        @endforeach

        <tr><td></td></tr>

        {{-- Normalisasi Bobot --}}
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}"><strong>NORMALISASI BOBOT</strong></td>
        </tr>
        <tr>
            <td><strong>Kriteria</strong></td>
            <td><strong>Bobot Awal</strong></td>
            <td><strong>Bobot Normal</strong></td>
            <td><strong>Jenis</strong></td>
            <td><strong>Pangkat</strong></td>
        </tr>
        @foreach($hasil['kriteria'] as $k)
        @php
            $wn = $hasil['bobot_normal'][$k->id];
            $pangkat = ($k->jenis === 'cost') ? -$wn : $wn;
        @endphp
        <tr>
            <td>{{ $k->kode }} - {{ $k->nama }}</td>
            <td>{{ $k->bobot }}</td>
            <td>{{ number_format($wn, 4) }}</td>
            <td>{{ ucfirst($k->jenis) }}</td>
            <td>{{ number_format($pangkat, 4) }}</td>
        </tr>
        @endforeach

        <tr><td></td></tr>

        {{-- Vektor S --}}
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}"><strong>VEKTOR S</strong></td>
        </tr>
        <tr>
            <td><strong>Alternatif</strong></td>
            <td><strong>Vektor S</strong></td>
        </tr>
        @foreach($hasil['alternatif'] as $a)
        <tr>
            <td>{{ $a->kode }} - {{ $a->nama }}</td>
            <td>{{ number_format($hasil['vektor_s'][$a->id], 6) }}</td>
        </tr>
        @endforeach
        <tr>
            <td><strong>Total S</strong></td>
            <td><strong>{{ number_format($hasil['total_s'], 6) }}</strong></td>
        </tr>

        <tr><td></td></tr>

        {{-- Ranking --}}
        <tr>
            <td colspan="{{ count($hasil['kriteria']) + 3 }}"><strong>RANKING HASIL AKHIR</strong></td>
        </tr>
        <tr>
            <td><strong>Ranking</strong></td>
            <td><strong>Kode</strong></td>
            <td><strong>Alternatif</strong></td>
            <td><strong>Vektor S</strong></td>
            <td><strong>Vektor V</strong></td>
            <td><strong>Persentase (%)</strong></td>
        </tr>
        @foreach($hasil['ranking'] as $r)
        <tr>
            <td>{{ $r['rank'] }}</td>
            <td>{{ $r['kode'] }}</td>
            <td>{{ $r['nama'] }}</td>
            <td>{{ number_format($r['vektor_s'], 6) }}</td>
            <td>{{ number_format($r['vektor_v'], 6) }}</td>
            <td>{{ $r['persentase'] }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
