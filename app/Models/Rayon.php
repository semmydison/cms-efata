<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = ['wilayah_id', 'nama_rayon', 'nama_penatua'];

    // Relasi: Rayon ini milik 1 Wilayah tertentu
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    // Relasi: 1 Rayon memiliki banyak Jemaat
    public function jemaats()
    {
        return $this->hasMany(Jemaat::class);
    }

    // FITUR HAPUS OTOMATIS (CASCADING DELETE)
    protected static function booted()
    {
        static::deleting(function ($rayon) {
            // Jika Rayon dihapus, maka semua Jemaat di dalamnya otomatis dihapus
            $rayon->jemaats()->delete();
        });
    }
}