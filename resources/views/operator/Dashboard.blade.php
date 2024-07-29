<head>
    <style>
        table {
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th:nth-child(1), td:nth-child(1) {
            width: 40%;
        }
        th:nth-child(2), td:nth-child(2) {
            width: 20%;
        }
        th:nth-child(3), td:nth-child(3) {
            width: 20%;
        }
        th:nth-child(4), td:nth-child(4) {
            width: 20%;
        }
    </style>
</head>

<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <x-slot:tabs>Kedungjaran-Dashboard</x-slot>
    <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header">
                Kesekretariatan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Nomor Surat Terakhir :</h5>
                          <p class="card-text">{{ $nomor_surat_terakhir }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Total Surat yang dibuat : {{ $total_surat }}</h5>
                          <table>
                                @foreach($suratData as $surat => $total)
                                    <tr>
                                        <td>{{ $surat }}</td>
                                        <td>:</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                @endforeach
                            </table>
                            </ul>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="card-footer">
                <a href="/user/operator/kesekretariatan">Lihat...</a>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header">
                Data Penduduk
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Total Penduduk Yang Tercatat Dalam Sistem :</h5>
                          <strong class="card-text">{{ $total_penduduk }}</strong>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h6 class="card-title">Rincian:</h6>
                          <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Dusun</th>
                                    <th>L</th>
                                    <th>P</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dusunData as $dusun)
                                    <tr>
                                        <td>{{ $dusun->dusun }}</td>
                                        <td>{{ $dusun->L }}</td>
                                        <td>{{ $dusun->P }}</td>
                                        <td>{{ $dusun->Total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="card-footer">
                <a href="/user/operator/datapenduduk">Lihat...</a>
            </div>
          </div>
        </div>
      </div>
</x-layout>