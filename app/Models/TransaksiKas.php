<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai
    protected $table = 'transaksi_kas'; 

    protected $fillable = ['tanggal', 'kategori_id', 'uraian', 'nominal', 'bukti_foto']; // Tambahkan 'user_id' jika dipakai

    public function kategori()
    {
        return $this->belongsTo(KategoriKeuangan::class, 'kategori_id');
    }
}