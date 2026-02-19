<table>
    <thead>
        <tr>
            <th><strong>No</strong></th>
            <th><strong>Kode</strong></th>
            <th><strong>Nama</strong></th>
            <th><strong>Keterangan</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($alternatif as $index => $a)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $a->kode }}</td>
            <td>{{ $a->nama }}</td>
            <td>{{ $a->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
