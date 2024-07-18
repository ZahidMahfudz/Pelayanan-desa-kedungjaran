<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduks';
    protected $primaryKey = 'NIK';
    public $incrementing = false;

    protected $fillable = [
        'NIK',
        'kk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'shdk',
        'pendidikan',
        'pekerjaan',
        'nama_ayah',
        'nama_ibu',
        'dusun',
        'RT',
        'RW',
        'kewarganegaraan',
    ];

    public function daftarsurat()
    {
        return $this->hasMany(daftarsurat::class, 'NIK', 'NIK');
    }

    
}
