<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rayon;

class RayonController extends Controller
{
    // Fungsi untuk Menyimpan Rayon Baru
    public function store(Request $request)
    {
        $request->validate([
            'wilayah_id' => 'required|exists:wilayahs,id',
            'nama_rayon' => 'required|string|max:255',
            'nama_penatua' => 'nullable|string|max:255',
        ]);

        Rayon::create([
            'wilayah_id' => $request->wilayah_id,
            'nama_rayon' => $request->nama_rayon,
            'nama_penatua' => $request->nama_penatua,
        ]);

        return redirect()->back()->with('success', 'Rayon berhasil ditambahkan!');
    }

    // Fungsi untuk Mengupdate Rayon
    public function update(Request $request, $id)
    {
        $request->validate([
            'wilayah_id' => 'required|exists:wilayahs,id',
            'nama_rayon' => 'required|string|max:255',
            'nama_penatua' => 'nullable|string|max:255',
        ]);

        $rayon = Rayon::findOrFail($id);
        $rayon->update([
            'wilayah_id' => $request->wilayah_id,
            'nama_rayon' => $request->nama_rayon,
            'nama_penatua' => $request->nama_penatua,
        ]);

        return redirect()->back()->with('success', 'Data Rayon berhasil diperbarui!');
    }

    // Fungsi untuk Menghapus Rayon
    public function destroy($id)
    {
        $rayon = Rayon::findOrFail($id);
        
        // Catatan: Jemaat di dalam rayon ini akan otomatis terhapus 
        // karena kita sudah memasang fungsi di Model Rayon.php sebelumnya.
        $rayon->delete();

        return redirect()->back()->with('success', 'Rayon dan seluruh jemaatnya berhasil dihapus!');
    }
}