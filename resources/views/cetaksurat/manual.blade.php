<style>
    .judul-surat {
        text-decoration: underline; /* Membuat teks menjadi bergaris bawah */
        font-size: 1.2em; /* Ukuran teks judul */
        margin-bottom: 5px; /* Jarak antara judul dan nomor surat */
        line-height: 1; /* Mengatur jarak antarbaris menjadi lebih dekat */
    }
    .nomor-surat {
        font-size: 1em; /* Ukuran teks nomor surat */
        line-height: 1; /* Mengatur jarak antarbaris menjadi lebih dekat */
    }
    .isi-surat{
        font-size: 1.1em;
        text-align: justify;
    }
    .indent{
        text-indent: 3.5em;
    }
    /* .table{
        margin-left: 3.5em;
    } */
    .table-borderless td:nth-child(1) {
        width: 20%;
    }

    .table-borderless td:nth-child(2) {
        width: 50%;
    }
</style>

<x-layoutsurat>
    <x-slot:tabs>Cetak: Manual</x-slot>
    <x-slot:judulsurat>{{ strtoupper($judulsurat) }}</x-slot>
    <x-slot:nomorsurat>{{ $daftarsurat->nomor_surat }}</x-slot>
    

    <div class="isi-surat">
        <p class="indent justify">Yang bertanda tangan dibawah ini, kami Kepala Desa Kedungjaran Kecamatan Sragi Kabupaten Pekalongan Menerangkan bahwa:</p>
        <table class="table table-borderless table-sm" style="width: 100%;">
            <tr>
                <td style="width: 25%;">Nama</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;"><strong>{{ $daftarsurat->nama_pemohon }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%;">NIK</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $daftarsurat->nik_pemohon }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Tempat/Tanggal Lahir</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $manual->tempat_lahir_pemohon }}, {{ \Carbon\Carbon::parse($manual->tanggal_lahir_pemohon)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Jenis Kelamin</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">
                    @if($manual->jenis_kelamin_pemohon == 'P')
                        Perempuan
                    @elseif($manual->jenis_kelamin_pemohon == 'L')
                        Laki-laki
                    @else
                        {{ $manual->jenis_kelamin_pemohon }} <!-- Tampilkan nilai langsung jika tidak ada kecocokan -->
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">Agama</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $manual->agama_pemohon }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Kewarganegaraan</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $manual->kewarganegaraan_pemohon }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Pekerjaan</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $manual->pekerjaan_pemohon }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Status Perkawinan</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">
                    @if($manual->status_perkawinan_pemohon == 'kawin')
                        Kawin
                    @elseif($manual->status_perkawinan_pemohon == 'belum_kawin')
                        Belum kawin
                    @else
                        {{ $manual->status_perkawinan_pemohon }} <!-- Tampilkan nilai langsung jika tidak ada kecocokan -->
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">Pendidikan Terakhir</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $manual->pendidikan_pemohon }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Alamat</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;" class="justify">{{ $manual->alamat_pemohon }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Keterangan</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;" class="justify">{{ $manual->keterangan }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Keperluan</td>
                <td style="width: 3%;">: </td>
                <td style="width: 72%;">{{ $manual->keperluan }}</td>
            </tr>
        </table>
        <p style="margin-top: 0">
            Demikian surat ini kami buat agar dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>
    

    <x-ttdkades>
        <x-slot:tanggalsurat>{{ $tanggal_surat }}</x-slot>
        @foreach ($ttd as $ttd)
            <x-slot:nama_gambar>{{ $ttd->nama_gambar }}</x-slot>
            <x-slot:nama_kades>{{ $ttd->nama_kades }}</x-slot>
        @endforeach
        <x-slot:ttdlain></x-slot>
    </x-ttdkades>

</x-layoutsurat>