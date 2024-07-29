<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Kedungjaran-Tambah Penduduk</x-slot>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penduduk</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <form action="addpenduduk" method="POST" id="pendudukForm">
            @csrf
            <div class="mb-3">
                <label for="NIK" class="form-label">NIK</label>
                <input type="text" class="form-control" id="NIK" name="NIK" required>
            </div>
            <div class="mb-3">
                <label for="kk" class="form-label">KK</label>
                <input type="text" class="form-control" id="kk" name="kk" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mt-2 mb-1 row">
                <div class="col-md-5">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                </div>
                <div class="col-md-5">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="agama" class="form-label">Agama</label>
                <input type="text" class="form-control" id="agama" name="agama" required>
            </div>
            <div class="mb-3">
                <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
                <input type="text" class="form-control" id="status_perkawinan" name="status_perkawinan" required>
            </div>
            <div class="mb-3">
                <label for="shdk" class="form-label ">Status Hubungan Dalam Keluarga</label>
                <input type="text" class="form-control" id="shdk" name="shdk" required>
            </div>
            <div class="mb-3">
                <label for="pendidikan" class="form-label ">Pendidikan</label>
                <input type="text" class="form-control" id="pendidikan" name="pendidikan" required>
            </div>
            <div class="mb-3">
                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
            </div>
            <div class="mb-3">
                <label for="nama_ayah" class="form-label">Nama Ayah</label>
                <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" required>
            </div>
            <div class="mb-3">
                <label for="nama_ibu" class="form-label">Nama Ibu</label>
                <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" required>
            </div>
            <div class="mb-1 row">
                <div class="col-md-5">
                    <label for="dusun" class="form-label">Dusun</label>
                    <input type="text" class="form-control" id="dusun" name="dusun" required>
                </div>
                <div class="col-md-3">
                    <label for="RT" class="form-label">RT</label>
                    <input type="number" class="form-control" id="RT" name="RT" required>
                </div>
                <div class="col-md-3">
                    <label for="RW" class="form-label">RW</label>
                    <input type="number" class="form-control" id="RW" name="RW" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="kewarganegaraan" class="form-label">Kewarganegaraan</label>
                <select class="form-select" id="kewarganegaraan" name="kewarganegaraan" required>
                    <option value="" disabled selected>-- Pilih Kewarganegaraan --</option>
                    <option value="WNI">WNI</option>
                    <option value="WNA">WNA</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary addpenduduk mt-3 mb-4">Tambah</button>

            <script type="text/javascript">
                $(document).ready(function() {
                    $(document).on('click', '.addpenduduk', function(e) {
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
        </form>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

</x-layout>