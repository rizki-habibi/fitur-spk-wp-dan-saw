<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color: #059669; color: white; font-size: 14px; text-align: center;">
                <strong>DATA ALTERNATIF</strong>
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">
                <em>Daftar alternatif (kandidat) yang akan dinilai</em>
            </th>
        </tr>
        <tr><td></td></tr>
        <tr style="background-color: #e2e8f0;">
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Alternatif</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alternatif as $index => $a)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $a->kode }}</td>
            <td style="border: 1px solid #000;">{{ $a->nama }}</td>
            <td style="border: 1px solid #000;">{{ $a->keterangan ?? '-' }}</td>
        </tr>
        @endforeach
        <tr><td></td></tr>
        <tr>
            <td colspan="4" style="font-weight: bold;">Total Alternatif: {{ $alternatif->count() }}</td>
        </tr>
    </tbody>
</table>
