<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepSigna extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function resep (){
    return $this->belongsTo(Resep::class);
    }

     public function signa(){
    return $this->belongsTo(Signa::class,'signa_id','signa_id');
    }
    public function obatResepSignas(){
    return $this->hasMany(ObatResepSigna::class,);
    }

}
