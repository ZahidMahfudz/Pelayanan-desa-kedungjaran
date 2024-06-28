<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'NIK',
        'nama_depan',
        'nama_belakang',
        'tanggal_lahir',
        'desa',
        'RT',
        'RW',
        'Status',
    ];
}
