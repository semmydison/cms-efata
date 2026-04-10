<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warta extends Model
{
    use HasFactory;
    protected $fillable = ['judul', 'kategori', 'tanggal_tampil', 'konten', 'gambar', 'file_path', 'file_name', 'hadir_laki', 'hadir_perempuan'];
}