<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratskd extends Model
{
    use HasFactory;

    protected $table = 'suratskds';

    protected $guarded = [
        'id'
    ];

    public function daftarsurat()
    {
        return $this->belongsTo(daftarsurat::class);
    }
}
