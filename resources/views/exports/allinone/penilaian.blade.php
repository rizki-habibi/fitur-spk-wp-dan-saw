<table>
    <thead>
        <tr>
            <th colspan="{{ count($kriteria) + 2 }}" style="background-color: #d97706; color: white; font-size: 14px; text-align: center;">
                <strong>MATRIKS PENILAIAN (DATA ASLI)</strong>
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($kriteria) + 2 }}" style="text-align: center;">
                <em>Nilai asli setiap alternatif pada masing-masing kriteria</em>
            </th>
        </tr>
        <tr><td></td></tr>
        <tr style="background-color: #e2e8f0;">
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Alternatif</th>
            @foreach($kriteria as $k)
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ $k->kode }} ({{ $k->nama }})</th>
            @endforeach
        </tr>
        {{-- Sub-header: Jenis kriteria --}}
        <tr style="background-color: #f1f5f9;">
            <th style="border: 1px solid #000;"></th>
            <th style="border: 1px solid #000; font-style: italic;">Jenis:</th>
            @foreach($kriteria as $k)
            <th style="border: 1px solid #000; font-style: italic; text-align: center;">{{ ucfirst($k->jenis) }}</th>
            @endforeach
        </tr>
        {{-- Sub-header: Bobot --}}
        <tr style="background-color: #f1f5f9;">
            <th style="border: 1px solid #000;"></th>
            <th style="border: 1px solid #000; font-style: italic;">Bobot:</th>
            @foreach($kriteria as $k)
            <th style="border: 1px solid #000; font-style: italic; text-align: center;">{{ $k->bobot }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($alternatif as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; font-weight: bold;">{{ $a->kode }} - {{ $a->nama }}</td>
            @foreach($kriteria as $k)
            <td style="border: 1px solid #000; text-align: center;">{{ $matriks[$a->id][$k->id] ?? '-' }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
