<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Kedungjaran-Kesekretariatan</x-slot>

    <div class="mb-2">
        <a href="/rekapsurat" class="btn btn-primary" target="_blank">Rekap</a>
        <a href="/hapusdaftarsurat" class="btn btn-danger">Hapus Semua</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Tanggal</th>
                <th>Perihal</th>
                <th>Nama Pemohon</th>
                <th>NIK Pemohon</th>
                <th>Status surat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nomor_surat }}</td>
                    <td>{{ $item->tanggal_surat }}</td>
                    <td>{{ $item->jenis_surat }}</td>
                    <td>{{ $item->nama_pemohon }}</td>
                    <td>{{ $item->nik_pemohon }}</td>
                    <td>
                        @if ($item->status_surat == 'sudah_cetak')
                            <span class="badge bg-success">Sudah Cetak</span>
                        @elseif ($item->status_surat == 'belum_cetak')
                            <span class="badge rounded-pill bg-danger">Belum Cetak</span>
                        @else
                            {{ $item->status_surat }}
                        @endif
                    </td>
                    @if ($item->jenis_surat != 'SKD' && $item->jenis_surat != 'SK' && $item->jenis_surat != 'SKTMS' && $item->jenis_surat != 'SKTM' && $item->jenis_surat != 'SKK' && $item->jenis_surat != 'SKWN' && $item->jenis_surat != 'SKP' && $item->jenis_surat != 'SKCK')
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                                </button>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/showeditsurat/{{ $item->id }}">Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item hapussurat" href="/hapussurat/{{ $item->id }}" data-nomor-surat="{{ $item->nomor_surat }}">Hapus</a></li>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $(document).on('click', '.hapussurat', function(e) {
                                            e.preventDefault();
                                            var link = $(this).attr("href");
                                            var nomorsurat = $(this).data("nomor-surat");
                                    
                                            Swal.fire({
                                                title: "Apakah anda yakin?",
                                                text: "Untuk menghapus " + nomorsurat,
                                                icon: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#3085d6",
                                                cancelButtonColor: "#d33",
                                                confirmButtonText: "Ya, Hapus!",
                                                cancelButtonText: "Batal"
                                                }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = link;
                                                }
                                            });
                                        });
                                    });
                                </script>
                                </ul>
                            </div>
                        </td>
                    @else    
                        <td>
                            <!-- Example single danger button -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                                </button>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/showeditsurat/{{ $item->id }}">Edit</a></li>
                                <li><a class="dropdown-item" href="/cetaksurat/{{ $item->id }}" target="_blank">Cetak</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item hapussurat" href="/hapussurat/{{ $item->id }}" data-nomor-surat="{{ $item->nomor_surat }}">Hapus</a></li>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $(document).on('click', '.hapussurat', function(e) {
                                            e.preventDefault();
                                            var link = $(this).attr("href");
                                            var nomorsurat = $(this).data("nomor-surat");
                                    
                                            Swal.fire({
                                                title: "Apakah anda yakin?",
                                                text: "Untuk menghapus " + nomorsurat,
                                                icon: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#3085d6",
                                                cancelButtonColor: "#d33",
                                                confirmButtonText: "Ya, Hapus!",
                                                cancelButtonText: "Batal"
                                                }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = link;
                                                }
                                            });
                                        });
                                    });
                                </script>
                                </ul>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
</x-layout>