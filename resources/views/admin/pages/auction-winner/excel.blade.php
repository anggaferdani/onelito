<table>
    <thead>
    <tr>
        <th>Pemenang</th>
        <th>Alamat</th>
        <th>Tinggal</th>
        <th >Kategori Event</th>
        <th>Tanggal Event</th>
    </tr>
    </thead>
    <tbody>
    @foreach($winners as $winner)
        <tr>
            <td>{{ $winner->member->nama }}</td>
            <td>{{ $winner->member->alamat }}</td>
            <td>{{ $winner->city->city_name ?? '' }}</td>
            <td>{{ $winner->event->kategori_event }}</td>
            <td>{{ $winner->event->tgl_mulai }}</td>
        </tr>
    @endforeach
    </tbody>
</table>