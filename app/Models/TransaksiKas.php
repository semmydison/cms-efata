<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    use HasFactory;
    protected $table = 'transaksi_kas';
    protected $fillable = ['buku_kas', 'tipe_transaksi', 'tanggal', 'kategori', 'nominal', 'keterangan'];
}