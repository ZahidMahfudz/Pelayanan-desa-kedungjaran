<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Buat SKD</x-slot>

    
    <form id="editSKTMS" action="{{ route('edit_SKTMS', ['id'=>$surat->id]) }}" method="POST">
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
            <legend>Data Wali Murid</legend>
            <div class="mt-2 mb-3 row">
                <label for="searchWaliMurid" class="form-label">Cari Wali Murid</label>
                <div class="col-sm-10">
                    <select id="searchWaliMurid" class="form-select">
                        @if($surat)
                            <option value="{{ $surat->nik_pemohon }}" selected>{{ $surat->nama_pemohon }} | {{ $surat->nik_pemohon }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="selectedNIKWaliMurid" name="selectedNIKWaliMurid"  value="{{ $surat->nik_pemohon }}">
                </div>
            </div>
            <div class="card col-sm-10" style="display: none;" id="waliMuridDetails">
                <div class="card-body">
                    <h5 class="card-title">Detail Penduduk</h5>
                    <p class="card-text">NIK: <span id="nikWaliMurid"></span></p>
                    <p class="card-text">Nama: <span id="namaWaliMurid"></span></p>
                    <p class="card-text">Jenis Kelamin: <span id="jenisKelaminWaliMurid"></span></p>
                    <p class="card-text">Tempat Tanggal Lahir: <span id="tempatLahirWaliMurid"></span>, <span id="tanggalLahirWaliMurid"></span></p>
                </div>
            </div>
        </fieldset>
        <fieldset class="mt-2">
            <legend>Data Murid</legend>
            <div class="mt-2 mb-3 row">
                <label for="searchMurid" class="form-label">Cari Murid</label>
                <div class="col-sm-10">
                    <select id="searchMurid" class="form-select">
                        @if($sktms)
                            <option value="{{ $sktms->nik_murid }}" selected>{{ $siswa->nama }} | {{ $sktms->nik_murid }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="selectedNIKMurid" name="selectedNIKMurid"  value="{{ $sktms->nik_murid }}">
                </div>
            </div>
            <div class="card col-sm-10" style="display: none;" id="muridDetails">
                <div class="card-body">
                    <h5 class="card-title">Detail Penduduk</h5>
                    <p class="card-text">NIK: <span id="nikMurid"></span></p>
                    <p class="card-text">Nama: <span id="namaMurid"></span></p>
                    <p class="card-text">Jenis Kelamin: <span id="jenisKelaminMurid"></span></p>
                    <p class="card-text">Tempat Tanggal Lahir: <span id="tempatLahirMurid"></span>, <span id="tanggalLahirMurid"></span></p>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Keterangan Tambahan</legend>
            <div>
                <div class="col-sm-10">
                    <label for="asal_sekolah" class="form-label">Asal Sekolah </label>
                    <textarea name="asal_sekolah" id="asal_sekolah" cols="5" rows="5" class="form-control">{{ $sktms->asal_sekolah }}</textarea>
                </div>
                <div class="col-sm-10">
                    <label for="keperluan" class="form-label">Tulis Keperluan Surat</label>
                    <textarea name="keperluan" id="keperluan" cols="5" rows="5" class="form-control">{{ $sktms->keperluan }}</textarea>
                </div>
            </div>
        </fieldset>
        <!-- Lanjutkan dengan fieldset lain -->
        <button type="submit" class="btn btn-primary mt-3 addsktm">Edit</button>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.addsktm', function(e) {
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

        function initializeSelect2(selectId, detailsCardId, selectedNIKId, nikId, namaId, jenisKelaminId, tempatLahirId, tanggalLahirId) {
            $(selectId).select2({
                placeholder: 'Pilih Pemohon',
                ajax: {
                    url: "{{ route('Caripemohon') }}",
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
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

            $(selectId).on('select2:select', function(e) {
                var data = e.params.data;
                $(nikId).text(data.id);
                $(namaId).text(data.additionalData.nama);
                $(jenisKelaminId).text(data.additionalData.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
                $(tempatLahirId).text(data.additionalData.tempat_lahir);
                var formattedTanggalLahir = new Date(data.additionalData.tanggal_lahir).toLocaleDateString('id-ID');
                $(tanggalLahirId).text(formattedTanggalLahir);
                $(selectedNIKId).val(data.id);
                // Populate other fields here
                $(detailsCardId).show();
            });
        }

        initializeSelect2('#searchWaliMurid', '#waliMuridDetails', '#selectedNIKWaliMurid', '#nikWaliMurid', '#namaWaliMurid', '#jenisKelaminWaliMurid', '#tempatLahirWaliMurid', '#tanggalLahirWaliMurid');
        initializeSelect2('#searchMurid', '#muridDetails', '#selectedNIKMurid', '#nikMurid', '#namaMurid', '#jenisKelaminMurid', '#tempatLahirMurid', '#tanggalLahirMurid');
    });
</script>
</x-layout>
