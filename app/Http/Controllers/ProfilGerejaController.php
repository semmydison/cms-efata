<?php

namespace App\Http\Controllers;

use App\Models\ProfilGereja;
use App\Models\Pengurus;
use Illuminate\Http\Request;

class ProfilGerejaController extends Controller
{
    // 1. Menampilkan Halaman Profil
    public function index()
    {
        $profil = ProfilGereja::first();
        
        // Memisahkan Ketua dan Anggota agar struktur bagannya rapi
        $ketua = Pengurus::where('jabatan', 'Ketua Majelis Jemaat')->first();
        $anggotas = Pengurus::where('jabatan', '!=', 'Ketua Majelis Jemaat')->get();

        return view('profil-gereja.index', compact('profil', 'ketua', 'anggotas'));
    }

// 2. Menyimpan/Update Identitas & Logo Gereja
    public function update(Request $request)
    {
        // Cari data profil, kalau belum ada sama sekali, buatkan wadah baru
        $profil = ProfilGereja::first();
        if (!$profil) {
            $profil = new ProfilGereja();
        }

        // Simpan data teks satu per satu dengan tegas
        $profil->nama_gereja = $request->nama_gereja;
        $profil->alamat = $request->alamat;
        $profil->no_telepon = $request->no_telepon;
        $profil->email = $request->email;
        $profil->visi = $request->visi;
        $profil->misi = $request->misi;

        // Logika Simpan Gambar Logo
        if ($request->hasFile('logo')) {
            // Simpan ke folder 'storage/app/public/logos'
            $path = $request->file('logo')->store('logos', 'public');
            $profil->logo = $path;
        }
        
        $profil->save();
        
        return redirect()->back();
    }

    // 3. Menambah Pengurus Baru
    public function storePengurus(Request $request)
    {
        $data = $request->all();

        // Logika Simpan Gambar Foto Pengurus Baru
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengurus', 'public');
        }

        Pengurus::create($data);
        return redirect()->back();
    }

    // 4. MENGEDIT PENGURUS (Ini fungsi yang tadi hilang/menyebabkan error)
    public function updatePengurus(Request $request, $id)
    {
        $pengurus = Pengurus::findOrFail($id);
        
        // Ambil semua data form kecuali file foto dan token keamanan
        $data = $request->except(['foto', '_method', '_token']);

        // Jika user mengupload foto baru saat mengedit
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengurus', 'public');
        }

        $pengurus->update($data);
        return redirect()->back();
    }

    // 5. MENGHAPUS PENGURUS
    public function deletePengurus($id)
    {
        $pengurus = Pengurus::findOrFail($id);
        $pengurus->delete();
        return redirect()->back();
    }
}