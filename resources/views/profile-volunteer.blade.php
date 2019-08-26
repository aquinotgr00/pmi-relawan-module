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

@if(count($volunteer->achievements) > 0)
<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Penghargaan</b></th>
    </tr>
    @each('volunteer::qualification', array_combine(range(1, count($volunteer->achievements)), array_values($volunteer->achievements)), 'qualification')
</table>
@endif

@if(count($volunteer->assignments) > 0)
<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Penugasan</b></th>
    </tr>
    @each('volunteer::qualification', array_combine(range(1, count($volunteer->assignments)), array_values($volunteer->assignments)), 'qualification')
</table>
@endif

@if(count($volunteer->trainings) > 0)
<br />
<br />
<br />
<table border="1" cellpadding="10">
    <tr>
        <th><b>No</b></th>
        <th><b>Pelatihan</b></th>
    </tr>
    @each('volunteer::qualification', array_combine(range(1, count($volunteer->trainings)), array_values($volunteer->trainings)), 'qualification')
</table>
@endif