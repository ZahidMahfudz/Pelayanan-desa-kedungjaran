<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Edit SKD</x-slot>
    
    <form id="editdomisili" action="{{ route('edit_domisili', ['id'=>$surat->id]) }}" method="POST">
        @csrf
        <fieldset>
            <legend>Nomor Surat</legend>
            <div>
                <div class="col-sm-10">
                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="{{ $surat->nomor_surat }}" disabled></input>
                </div>
            </div>
        </fieldset>
        <fieldset class="mt-2">
            <legend>Data Pemohon</legend>
            <div class="mt-2 mb-3 row">
                <label for="searchPemohon" class="form-label">Cari Pemohon</label>
                <div class="col-sm-10">
                    <select id="searchPemohon" class="form-select">
                        @if($surat)
                            <option value="{{ $surat->nik_pemohon }}" selected>{{ $surat->nama_pemohon }} | {{ $surat->nik_pemohon }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="selectedNIK" name="selectedNIK" value="{{ $surat->nik_pemohon }}">
                </div>
            </div>
            <div class="card col-sm-10" style="display: none;" id="pemohonDetails">
                <div class="card-body">
                    <h5 class="card-title">Detail Penduduk</h5>
                    <p class="card-text">NIK: <span id="nik"></span></p>
                    <p class="card-text">Nama: <span id="nama"></span></p>
                    <p class="card-text">Jenis Kelamin: <span id="jenis_kelamin"></span></p>
                    <p class="card-text">Tempat Tanggal Lahir: <span id="tempat_lahir"></span>, <span id="tanggal_lahir"></span></p>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Keterangan Tambahan</legend>
            <div>
                <div class="col-sm-10">
                    <label for="alamat" class="form-label">Tulis alamat Pemohon</label>
                    <textarea name="alamat" id="alamat" cols="5" rows="5" class="form-control">{{ $skd->alamat }}</textarea>
                </div>
                <div class="col-sm-10">
                    <label for="keperluan" class="form-label">Tulis Keperluan Pemohon</label>
                    <textarea name="keperluan" id="keperluan" cols="5" rows="5" class="form-control">{{ $skd->keperluan }}</textarea>
                </div>
            </div>
        </fieldset>
        <!-- Lanjutkan dengan fieldset lain -->
        <button type="submit" class="btn btn-primary mt-3 addskd">Edit</button>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.addskd', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
        
                Swal.fire({
                    title: "Pastikan Data Sudah Benar!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, sudah benar",
                    cancelButtonText: "Batal"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                    });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#searchPemohon').select2({
                placeholder:'Pilih Pemohon',
                ajax: {
                    url: "{{ route('Caripemohon') }}",
                    processResults: function(data){
                        return {
                            results: $.map(data, function(item){
                                return {
                                    id: item.NIK,
                                    text: item.nama + ' | ' + item.NIK,
                                    additionalData: {
                                        jenis_kelamin: item.jenis_kelamin,
                                        tempat_lahir: item.tempat_lahir,
                                        tanggal_lahir: item.tanggal_lahir,
                                        nama: item.nama,
                                        // Add other fields here
                                    }
                                }
                            })
                        }
                    }
                }
            });
            $('#searchPemohon').on('select2:select', function(e) {
                var data = e.params.data;
                $('#nik').text(data.id);
                $('#nama').text(data.additionalData.nama);
                $('#jenis_kelamin').text(data.additionalData.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
                $('#tempat_lahir').text(data.additionalData.tempat_lahir);
                var formattedTanggalLahir = new Date(data.additionalData.tanggal_lahir).toLocaleDateString('id-ID');
                $('#tanggal_lahir').text(formattedTanggalLahir);
                $('#selectedNIK').val(data.id);
                // Populate other fields here
                $('#pemohonDetails').show();
            });
        });
    </script>
</x-layout>
