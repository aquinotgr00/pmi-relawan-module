<table border="0">
    <tr>
        <td>Nama</td>
        <td>: {{ $volunteer->name }}</td>
    </tr>
    <tr>
        <td>No KTA</td>
        <td>: {{ $volunteer->id }}</td>
    </tr>
    <tr>
        <td>Tempat, Tanggal Lahir</td>
        <td>: {{ $volunteer->birthplace }}, {{ $volunteer->dob }}</td>
    </tr>
    <tr>
        <td>Umur</td>
        <td>: {{ $volunteer->age }} Tahun</td>
    </tr>
    <tr>
        <td>Jenis Kelamin</td>
        <td>: {{ $volunteer->gender }}</td>
    </tr>
    <tr>
        <td>Agama</td>
        <td>: {{ $volunteer->religion }}</td>
    </tr>
    <tr>
        <td>No Telepon</td>
        <td>: {{ $volunteer->phone }}</td>
    </tr>
    <tr>
        <td>Gol. Darah</td>
        <td>: {{ $volunteer->blood_type }}</td>
    </tr>
</table>

<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Penghargaan</b></th>
    </tr>
    @foreach($volunteer->qualifications as $key => $qualification)
        @if ($qualification->category === 1)
            @php $key++ @endphp
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $qualification->description }}</td>
            </tr>
        @endif
    @endforeach
</table>

<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Penugasan</b></th>
    </tr>
    @foreach($volunteer->qualifications as $key => $qualification)
        @if ($qualification->category === 2)
            @php $key++ @endphp
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $qualification->description }}</td>
            </tr>
        @endif
    @endforeach
</table>

<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Pelatihan</b></th>
    </tr>
    @foreach($volunteer->qualifications as $key => $qualification)
        @if ($qualification->category === 3)
            @php $key++ @endphp
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $qualification->description }}</td>
            </tr>
        @endif
    @endforeach
</table>