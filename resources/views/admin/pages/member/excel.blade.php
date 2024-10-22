<table>
    <thead>
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>No. Hp</th>
        <th >Alamat</th>
        <th>Verifikasi Email</th>
        <th>Status Aktif</th>
        <th>Provinsi</th>
        <th>Kota</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
    @php
        $verif = 'Belum verifikasi';
        if ($member->email_verified_at !== null) {
            $verif = "Sudah verifikasi";
        }

        $status = 'Tidak Aktif';
        if ($member->status_aktif === 1) {
            $status = "Aktif";
        }
    @endphp
        <tr>
            <td>{{ $member->nama }}</td>
            <td>{{ $member->email }}</td>
            <td>{{ $member->no_hp }}</td>
            <td>{{ $member->alamat }}</td>
            <td>{{ $verif }}</td>
            <td>{{ $status }}</td>
            <td>{{ $member->province->prov_name ?? '' }}</td>
            <td>{{ $member->city->city_name ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>