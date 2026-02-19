<table>
    <thead>
        <tr>
            <th><strong>No</strong></th>
            <th><strong>Kode</strong></th>
            <th><strong>Nama</strong></th>
            <th><strong>Bobot</strong></th>
            <th><strong>Jenis</strong></th>
            <th><strong>Keterangan</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($kriteria as $index => $k)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $k->kode }}</td>
            <td>{{ $k->nama }}</td>
            <td>{{ $k->bobot }}</td>
            <td>{{ ucfirst($k->jenis) }}</td>
            <td>{{ $k->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
