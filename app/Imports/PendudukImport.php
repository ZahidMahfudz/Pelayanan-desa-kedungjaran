<?php

namespace App\Imports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PendudukImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Penduduk([
            'NIK' => $row['nik'],
            'kk' => $row['kk'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']),
            'agama' => $row['agama'],
            'status_perkawinan' => $row['status_perkawinan'],
            'shdk' => $row['shdk'],
            'pendidikan' => $row['pendidikan'],
            'pekerjaan' => $row['pekerjaan'],
            'nama_ayah' => $row['nama_ayah'],
            'nama_ibu' => $row['nama_ibu'],
            'dusun' => $row['dusun'],
            'RT' => $row['rt'],
            'RW' => $row['rw'],
            'kewarganegaraan' => $row['kewarganegaraan'],
        ]);
    }
}
