<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayahs'; // Pastikan nama tabel di database adalah 'wilayahs'
    protected $guarded = ['id'];

    // Relasi Anak 1: Wilayah ini punya Rayon apa saja?
    public function rayons()
    {
        return $this->hasMany(Rayon::class, 'wilayah_id');
    }

    // Relasi Anak 2: Wilayah ini menampung Jemaat siapa saja?
    public function jemaats()
    {
        return $this->hasMany(Jemaat::class, 'wilayah_id');
    }

    // FITUR HAPUS OTOMATIS (CASCADING DELETE KELAS BERAT)
    protected static function booted()
    {
        static::deleting(function ($wilayah) {
            // 1. Hapus semua Jemaat yang ada di wilayah ini
            $wilayah->jemaats()->delete();
            // 2. Hapus semua Rayon yang ada di wilayah ini
            $wilayah->rayons()->delete();
        });
    }
}