<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Kedungjaran-Buat Surat</x-slot>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col-sm-2">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">SK</h5>
              <p class="card-text">Surat Keterangan</p>
              <a href="/buatformSK" class="btn btn-primary">Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SKD</h5>
              <p class="card-text">Surat Keterangan Domisili</p>
              <a href="/buatformSKD" class="btn btn-primary">Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SKTM</h5>
              <p class="card-text">Surat Keterangan Tidak Mampu Untuk Siswa</p>
              <a href="/buatformSKTMsiswa" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SKTM</h5>
              <p class="card-text">Surat Keterangan Tidak Mampu</p>
              <a href="/buatformSKTM" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SKK</h5>
              <p class="card-text">Surat Keterangan Kehilangan</p>
              <a href="/buatformSKK" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SK</h5>
              <p class="card-text">Surat Keterangan Menjadi Wali Nikah</p>
              <a href="/buatformwali" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SKP</h5>
              <p class="card-text">Surat Keterangan Penghasilan</p>
              <a href="/buatformskp" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SP</h5>
              <p class="card-text">Surat Pengantar SKCK</p>
              <a href="/buatformSKCK" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Lain-lain</h5>
              <p class="card-text">Surat yang dibuat diluar aplikasi</p>
              <a href="/buatformlain" class="btn btn-primary" >Buat Surat</a>
            </div>
          </div>
        </div>
    </div>

</x-layout>
