<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Buat SK</x-slot>
        
        <form id="skForm" action="{{ route('edit_lain', ['id'=>$surat->id]) }}" method="POST">
            @csrf
            <fieldset>
                <legend>Nomor Surat</legend>
                <div>
                    <div class="col-sm-10">
                        <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="{{ $surat->nomor_surat }}"></input>
                    </div>
                </div>
            </fieldset>
            <legend>Tanggal Surat</legend>
                <div>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="tanggal_surat" id="tanggal_surat" value="{{ $surat->tanggal_surat }}">
                    </div>
                </div>
            </fieldset>
            <fieldset class="mt-2">
                <legend>Data Pemohon</legend>
                <div class="mt-2 mb-3 row">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_pemohon" id="nama_pemohon" value="{{ $surat->nama_pemohon }}">
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
                        <label for="perihal" class="form-label">Perihal</label>
                        <input type="text" name="perihal" id="perihal" cols="5" rows="5" class="form-control" value="{{ $surat->jenis_surat }}">
                    </div>
                </div>
            </fieldset>
            <!-- Lanjutkan dengan fieldset lain -->
            <button type="submit" class="btn btn-primary mt-3 addsk">Edit</button>
        </form>
    
        <script type="text/javascript">
            $(document).ready(function() {
                $(document).on('click', '.addsk', function(e) {
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