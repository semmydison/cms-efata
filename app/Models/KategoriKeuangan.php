<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKeuangan extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori', 'jenis', 'peruntukan_kas', 'target_tahunan'];

    public function transaksiKas()
    {
        return $this->hasMany(TransaksiKas::class, 'kategori_id');
    }
}