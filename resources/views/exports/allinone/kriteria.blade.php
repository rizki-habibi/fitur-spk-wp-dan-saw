<table>
    <thead>
        <tr>
            <th colspan="6" style="background-color: #4f46e5; color: white; font-size: 14px; text-align: center;">
                <strong>DATA KRITERIA</strong>
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">
                <em>Daftar kriteria yang digunakan dalam proses pengambilan keputusan</em>
            </th>
        </tr>
        <tr><td></td></tr>
        <tr style="background-color: #e2e8f0;">
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Kode</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Nama Kriteria</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Bobot</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Jenis</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kriteria as $index => $k)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $k->kode }}</td>
            <td style="border: 1px solid #000;">{{ $k->nama }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $k->bobot }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ ucfirst($k->jenis) }}</td>
            <td style="border: 1px solid #000;">{{ $k->keterangan ?? '-' }}</td>
        </tr>
        @endforeach
        <tr><td></td></tr>
        <tr>
            <td colspan="3" style="font-weight: bold;">Total Bobot:</td>
            <td style="font-weight: bold; text-align: center;">{{ $kriteria->sum('bobot') }}</td>
            <td colspan="2"></td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="6" style="background-color: #fef3c7; border: 1px solid #000;">
                <strong>Keterangan Jenis Kriteria:</strong>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #000;">
                Benefit = Semakin besar nilai semakin baik | Cost = Semakin kecil nilai semakin baik
            </td>
        </tr>
    </tbody>
</table>
