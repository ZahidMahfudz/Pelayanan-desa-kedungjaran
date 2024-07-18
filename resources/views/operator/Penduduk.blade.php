<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Kedungjaran-Penduduk</x-slot>
    <div class="d-flex flex-row-reverse bd-highlight">
        <div class="d-flex mt-2">
            <form method="GET" action="{{ route('showPenduduk') }}" class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari nama atau NIK..." name="cariPenduduk" id="searchInput" value="{{ request('cariPenduduk') }}">
                <button class="btn btn-primary" type="submit">Cari</button>
            </form>
        </div>
        <div class="p-2 bd-highlight">
            <a href="show_tambah_penduduk" class="btn btn-primary">Tambah Penduduk</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="table-light  ">
                    <th>No</th>
                    <th>NIK</th>
                    <th>KK</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Agama</th>
                    <th>Status Perkawinan</th>
                    <th>SHDK</th>
                    <th>Pendidikan</th>
                    <th>Pekerjaan</th>
                    <th>Nama Ayah</th>
                    <th>Nama Ibu</th>
                    <th>Dusun</th>
                    <th>RT</th>
                    <th>RW</th>
                    <th>Kewarganegaraan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pendudukTable">
                @forelse ($datapenduduk as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->NIK }}</td>
                        <td>{{ $item->kk }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->tempat_lahir }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->agama }}</td>
                        <td>{{ $item->status_perkawinan }}</td>
                        <td>{{ $item->shdk }}</td>
                        <td>{{ $item->pendidikan }}</td>
                        <td>{{ $item->pekerjaan }}</td>
                        <td>{{ $item->nama_ayah }}</td>
                        <td>{{ $item->nama_ibu }}</td>
                        <td>{{ $item->dusun }}</td>
                        <td>{{ $item->RT }}</td>
                        <td>{{ $item->RW }}</td>
                        <td>{{ $item->kewarganegaraan }}</td>
                        <td>
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#penduduk{{ $item->NIK }}">Edit</button>
                                <a href="/deletependuduk/{{ $item->NIK }}" class="btn btn-danger btn-sm hapuspenduduk" data-nik="{{ $item->NIK }}" data-nama="{{ $item->nama }}">Hapus</a>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $(document).on('click', '.hapuspenduduk', function(e) {
                                            e.preventDefault();
                                            var link = $(this).attr("href");
                                            var nama = $(this).data("nama");
                                            var nik = $(this).data("nik");
                                    
                                            Swal.fire({
                                                title: "Apakah anda yakin?",
                                                text: "Untuk menghapus " + nama + " dengan NIK " + nik,
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
                            </div>
                        </td>                        
                    </tr>

                    <div class="modal fade" id="penduduk{{ $item->NIK }}" tabindex="-1" data-bs-keyboard="false" aria-labelledby="pendudukLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="pendudukLabel">Edit Penduduk {{ $item->NIK }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editpenduduk" action="{{ route('editpenduduk', ['NIK'=>$item->NIK]) }}" method="POST">
                                        @csrf
                                        <fieldset>
                                            <div>
                                                <label for="NIK" class="form-label">NIK</label>
                                                <input type="text" class="form-control" id="NIK" name="NIK" value="{{ $item->NIK }}" disabled>
                                            </div>
                                            <div class="mb-1">
                                                <label for="kk" class="form-label">KK</label>
                                                <input type="text" class="form-control" id="kk" name="kk" value="{{ $item->kk }}">
                                            </div>
                                            <div class="mb-1">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $item->nama }}">
                                            </div>
                                            <div class="mt-2 mb-1">
                                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label> 
                                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                                        <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                        <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                    </select>
                                            </div>
                                            <div class="mt-2 mb-1 row g-2">
                                                <div class="col-md-5">
                                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ $item->tempat_lahir }}" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $item->tanggal_lahir }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label for="agama" class="form-label">agama</label>
                                                <input type="text" class="form-control" id="agama" name="agama" value="{{ $item->agama }}">
                                            </div>
                                            <div class="mt-2 mb-1">
                                                <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
                                                <select class="form-select" id="status_perkawinan" name="status_perkawinan" required>
                                                    <option value="kawin" {{ $item->status_perkawinan == 'kawin' ? 'selected' : '' }}>Kawin</option>
                                                    <option value="belum_kawin" {{ $item->status_perkawinan == 'belum_kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                </select>
                                            </div>
                                            <div class="mb-1">
                                                <label for="shdk" class="form-label">shdk</label>
                                                <input type="text" class="form-control" id="shdk" name="shdk" value="{{ $item->shdk }}">
                                            </div>
                                            <div class="mb-1">
                                                <label for="pendidikan" class="form-label">Pendidikan</label>
                                                <input type="text" class="form-control" id="pendidikan" name="pendidikan" value="{{ $item->pendidikan }}">
                                            </div>
                                            <div class="mt-2 mb-1">
                                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ $item->pekerjaan }}" required>
                                            </div>
                                            <div class="mt-2 mb-1">
                                                <label for="nama_ayah" class="form-label">Nama Ayah</label>
                                                <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" value="{{ $item->nama_ayah }}" required>
                                            </div>
                                            <div class="mt-2 mb-1">
                                                <label for="nama_ibu" class="form-label">Nama Ibu</label>
                                                <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" value="{{ $item->nama_ibu }}" required>
                                            </div>
                                            <div class="mt-2 mb-1 row g-2">
                                                <div class="col-md-5">
                                                    <label for="dusun" class="form-label">Dusun</label>
                                                    <input type="text" class="form-control" id="dusun" name="dusun" value="{{ $item->dusun }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="RT" class="form-label">RT</label>
                                                    <input type="number" class="form-control" id="RT" name="RT" value="{{ $item->RT }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="RW" class="form-label">RW</label>
                                                    <input type="number" class="form-control" id="RW" name="RW" value="{{ $item->RW }}" required>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-1">
                                                <label for="kewarganegaraan" class="form-label">Kewarganegaraan</label>
                                                <select class="form-select" id="kewarganegaraan" name="kewarganegaraan" required>
                                                    <option value="WNI" {{ $item->kewarganegaraan == 'WNI' ? 'selected' : '' }}>WNI</option>
                                                    <option value="WNA" {{ $item->kewarganegaraan == 'WNA' ? 'selected' : '' }}>WNA</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">Data tidak ditemukan</td>
                        </tr>
                @endforelse
            </tbody>
        </table>
        {{-- {{ $datapenduduk->links() }} --}}
        {!! $datapenduduk->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>

    {{-- <script type="text/javascript">
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                var isVisible = false;

                $("#pendudukTable tr").filter(function() {
                    var isMatch = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(isMatch);
                    if (isMatch) {
                        isVisible = true;
                    }
                });

                if (isVisible) {
                    $("#noData").hide();
                } else {
                    $("#noData").show();
                }
            });
        });
    </script> --}}
</x-layout>