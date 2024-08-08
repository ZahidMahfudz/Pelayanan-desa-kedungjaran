<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Buat SKD Luar</x-slot>

    <div class="alert alert-warning mt-3 mb-1 col-sm-10" role="alert">
        <p>Nomor Surat Terakhir : {{ $nomor_surat }}</p>
    </div>

    <form id="SKDLuarForm" action="submit_skd_luar" method="POST">
        @csrf
        <legend>Perihal Surat</legend>
        <div class="col-sm-10 mb-2">
            <input type="text" name="perihal" id="perihal" class="form-control" required>
        </div>
        <legend>Nomor Surat</legend>
        <div class="col-sm-10 mb-2">
            <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" required>
        </div>
        <legend>Data Pemohon</legend>
        <div class="col-sm-10">
            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
            <input type="text" name="nama_pemohon" id="nama_pemohon" class="form-control" required>
        </div>
        <div class="col-sm-10 mt-2">
            <label for="nik_pemohon" class="form-label">NIK Pemohon</label>
            <input type="text" name="nik_pemohon" id="nik_pemohon" class="form-control">
        </div>
        <div class="mt-2 mb-1 row">
            <div class="col-md-5">
                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
            </div>
            <div class="col-md-5">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
            </div>
        </div>
        <div class="col-sm-10 mt-2">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class=" col-sm-10 mt-2">
            <label for="agama" class="form-label">Agama</label>
            <input type="text" class="form-control" id="agama" name="agama">
        </div>
        <div class=" col-sm-10 mt-2">
            <label for="kewarganegaraan" class="form-label">Kewarganegaraan</label>
            <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan">
        </div>
        <div class=" col-sm-10 mt-2">
            <label for="pekerjaan" class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan">
        </div>
        <div class=" col-sm-10 mt-2">
            <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
            <input type="text" class="form-control" id="status_perkawinan" name="status_perkawinan">
        </div>
        <div class=" col-sm-10 mt-2">
            <label for="pendidikan" class="form-label">Pendidikan</label>
            <input type="text" class="form-control" id="pendidikan" name="pendidikan">
        </div>
        <div class="col-sm-10">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" cols="5" rows="5" class="form-control"></textarea>
        </div>
        <legend class="mt-2">Keterangan Tambahan</legend>
        <div>
            <div class="col-sm-10">
                <label for="keterangan" class="form-label">Tulis keterangan Pemohon</label>
                <textarea name="keterangan" id="keterangan" cols="5" rows="5" class="form-control"></textarea>
            </div>
            <div class="col-sm-10 mt-2">
                <label for="keperluan" class="form-label">Tulis Keperluan Pemohon</label>
                <textarea name="keperluan" id="keperluan" cols="5" rows="5" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3 mb-4 addskd">Buat</button>
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

</x-layout>