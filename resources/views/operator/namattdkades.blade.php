<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Kedungjaran-Nama dan Tanda Tangan Kades</x-slot>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnamattdkades">
        Tambah
    </button>

    <div class="modal fade" id="addnamattdkades" tabindex="-1" aria-labelledby="editnamattdLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editnamattdLabel">Tambah Nama dan Tanda Tangan Kades</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addnamattdkades" action="{{ route('addnamattdkades') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="nama_kades" class="form-label">Nama Kades</label>
                        <input type="text" class="form-control" id="nama_kades" name="nama_kades">
                        @error('nama_kades')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="nama_file" class="form-label">Unggah Tanda Tangan</label>
                        <input type="file" class="form-control" id="nama_file" name="nama_file" required>
                        @error('nama_file')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
          </div>
        </div>
      </div>

      <div class="alert alert-warning mt-3" role="alert">
        <strong>Perhatian!</strong>
        <ul>
            <li>Tambah Digunakan saat menginstal ulang saja</li>
            <li>Untuk pergantian tanda tangan dan nama dapat dilakukan melalui edit</li>
            <li>Gambar harus berorientasi Persegi dan sudah dilakukan penghapusan background</li>
        </ul>
      </div>

    <table class="table table-striped table-bordered mt-3">
        <tr>
            <th>Nama Kades</th>
            <th>Tanda Tangan Kades</th>
            <th>Aksi</th>
        </tr>
        <tr>
            @foreach ($ttd as $item)
                <td>{{ $item->nama_kades }}</td>
                <td>
                    <img src="{{ asset('image/'. $item->nama_gambar) }}" alt="ttd_kades">
                </td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editnamattd{{ $item->id }}">
                        Edit
                    </button>
                </td>

                <div class="modal fade" id="editnamattd{{ $item->id }}" tabindex="-1" aria-labelledby="editnamattdLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editnamattdLabel">Edit Nama dan Tanda Tangan Kades</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="edit_namattdkades" action="{{ route('edit_namattdkades', ['id'=>$item->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <label for="nama_kades" class="form-label">Nama Kades</label>
                                    <input type="text" class="form-control" id="nama_kades" name="nama_kades" value="{{ old('nama_kades', $item->nama_kades) }}">
                                    @error('nama_kades')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <img src="{{ asset('image/'. $item->nama_gambar) }}" alt="ttd_kades">
                                </div>
                                <div>
                                    <label for="file_baru" class="form-label">Unggah Tanda Tangan</label>
                                    <input type="file" class="form-control" id="file_baru" name="file_baru" required>
                                    @error('file_baru')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>
            @endforeach
        </tr>
    </table>
</x-layout>