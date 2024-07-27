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
    
    public function suratsk()
    {
        return $this->hasMany(suratsk::class);
    }
    
    public function suratsktmsiswa()
    {
        return $this->hasMany(suratsktmsiswa::class);
    }

    public function suratsktm()
    {
        return $this->hasMany(suratsktm::class);
    }

    public function suratkehilangan()
    {
        return $this->hasMany(suratkehilangan::class);
    }

    public function suratwalinikah()
    {
        return $this->hasMany(suratwalinikah::class);
    }
    
    public function suratpenghasilan()
    {
        return $this->hasMany(suratpenghasilan::class);
    }

    public function suratskck()
    {
        return $this->hasMany(suratskck::class);
    }
}
