<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class daftarsurat extends Model
{
    use HasFactory;

    protected $table = 'daftarsurats';

    protected $guarded = [
        'id'
    ];

    public function penduduk()
    {
        return $this->belongsTo(penduduk::class, 'NIK', 'NIK');
    }

    public function suratskd()
    {
        return $this->hasMany(suratskd::class);
    }
}
