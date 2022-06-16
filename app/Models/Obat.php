<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obatalkes_m';
    protected $primaryKey = 'obatalkes_id';
    public $timestamps = false;

    protected $guarded = [];

    public function  obatResep(){
    return $this->hasMany(ObatResepSigna::class,'obat_id','obatalkes_id');
    }
}
