<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Rekap Surat</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-pekalongan.png') }}">

    <style>
        body {
        font-family: 'Times New Roman', Times, serif; 
    }
    </style>
</head>

<div>
    <p style="text-align: center;">Rekap Surat Pemdes Kedungjaran</p>
</div>

<table class="table table-striped">
    <tr>
        <th>No</th>
        <th>Nomor Surat</th>
        <th>Tanggal</th>
        <th>Perihal</th>
        <th>Nama Pemohon</th>                
        <th>NIK Pemohon</th>
        <th>Status surat</th>
    </tr>
    @foreach ($surat as $item)
    <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nomor_surat }}</td>
            <td>{{ $item->tanggal_surat }}</td>
            <td>{{ $item->jenis_surat }}</td>
            <td>{{ $item->nama_pemohon }}</td>
            <td>{{ $item->nik_pemohon }}</td>
            <td>
                @if ($item->status_surat == 'sudah_cetak')
                    <p>Sudah Cetak</p>
                @elseif ($item->status_surat == 'belum_cetak')
                    <p>Belum Cetak</p>
                @else
                    {{ $item->status_surat }}
                @endif
            </td>
        </tr>
        @endforeach
</table>

    <script type="text/javascript">
        window.print();
    </script>