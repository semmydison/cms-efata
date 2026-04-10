<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jemaat extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi dari form
    protected $fillable = [
        'nik', 'no_kk', 'nama_lengkap', 'tempat_lahir', 'tgl_lahir',
        'jenis_kelamin', 'gol_darah', 'no_whatsapp', 'alamat_domisili',
        'wilayah_id', 'rayon_id', 'status_keanggotaan', 'status_baptis',
        'status_sidi', 'pekerjaan', 'minat_pelayanan', 'tampilkan_ultah'
    ];

    // Konversi tipe data otomatis (Sangat penting karena minat_pelayanan berbentuk array/checkbox)
    protected $casts = [
        'minat_pelayanan' => 'array',
        'status_baptis' => 'boolean',
        'status_sidi' => 'boolean',
        'tampilkan_ultah' => 'boolean',
    ];

    // Relasi: Jemaat ini berada di 1 Wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    // Relasi: Jemaat ini berada di 1 Rayon
    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }
}