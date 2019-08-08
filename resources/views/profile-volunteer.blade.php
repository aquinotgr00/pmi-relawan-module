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
        <td>: {{ $volunteer->birth_place }}, {{ $volunteer->dob }}</td>
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
        <td>: {{ $volunteer->blood }}</td>
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
    <tr>
        <td>1</td>
        <td>{{ $volunteer->awards }}</td>
    </tr>
</table>

<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Penugasan</b></th>
    </tr>
    <tr>
        <td>1</td>
        <td>{{ $volunteer->assignments }}</td>
    </tr>
</table>

<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Pelatihan</b></th>
    </tr>
    <tr>
        <td>1</td>
        <td>{{ $volunteer-> trainings }}</td>
    </tr>
</table>