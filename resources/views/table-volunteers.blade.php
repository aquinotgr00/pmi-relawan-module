<table border="1" cellpadding="10">
    <thead>
    <tr>
        <th><b>No</b></th>
        <th><b>Nama</b></th>
        <th><b>Jenis Kelamin</b></th>
        <th><b>Jenis Anggota</b></th>
        <th><b>Sub Jenis Anggota</b></th>
        <th><b>Kabupaten/Kota</b></th>
        <th><b>Unit</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($volunteers as $key => $volunteer)
    @php $key++ @endphp
        <tr>
            <td>{{ $key }}</td>
            <td>{{ $volunteer->name }}</td>
            <td>{{ $volunteer->gender === 'female' ? 'Perempuan':'Laki - laki' }}</td>
            <td>{{ $volunteer->unit->membership->parentMember ? $volunteer->unit->membership->parentMember->name:'' }}</td>
            <td>{{ $volunteer->unit->membership ? $volunteer->unit->membership->name:'' }}</td>
            <td>{{ $volunteer->city }}</td>
            <td>{{ $volunteer->unit ? $volunteer->unit->name:'' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
