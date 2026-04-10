<?php
 namespace App\Models;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;

 class ProfilGereja extends Model
 {
     use HasFactory;
     protected $fillable = ['logo', 'nama_gereja', 'alamat', 'no_telepon', 'email', 'visi', 'misi'];
 }