<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratskck extends Model
{
    use HasFactory;

    protected $table = 'suratskcks';

    protected $guarded = [
        'id'
    ];

    public function daftarsurat()
    {
        return $this->belongsTo(daftarsurat::class);
    }
}
