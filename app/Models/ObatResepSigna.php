<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatResepSigna extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function resepSigna()
    {
        return $this->belongsTo(ResepSigna::class);
    }
    public function obat()
    {
        return $this->belongsTo(Obat::class,'obat_id','obatalkes_id');
    }
}
