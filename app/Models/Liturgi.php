<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liturgi extends Model
{
    use HasFactory;
    protected $fillable = ['judul', 'tanggal', 'file_path', 'file_name', 'file_size', 'is_active'];
}