<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratpenghasilan extends Model
{
    use HasFactory;

    protected $table = 'suratpenghasilans';

    protected $guarded = [
        'id'
    ];

    public function daftarsurat()
    {
        return $this->belongsTo(daftarsurat::class);
    }
}
