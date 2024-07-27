<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratsktm extends Model
{
    use HasFactory;

    protected $table = 'suratsktms';

    protected $guarded = [
        'id'
    ];

    public function daftarsurat()
    {
        return $this->belongsTo(daftarsurat::class);
    }
}
