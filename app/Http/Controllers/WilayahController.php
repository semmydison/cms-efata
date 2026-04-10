<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use App\Models\Rayon;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        // Mengambil semua data wilayah beserta rayon DAN jemaat di dalamnya (untuk menghitung KK)
        $wilayahs = Wilayah::with(['rayons.jemaats'])->get();
        
        $total_wilayah = Wilayah::count();
        $total_rayon = Rayon::count();

        return view('wilayah.index', compact('wilayahs', 'total_wilayah', 'total_rayon'));
    }

    // ==========================================
    // FUNGSI UNTUK WILAYAH
    // ==========================================

    public function storeWilayah(Request $request)
    {
        $request->validate(['nama_wilayah' => 'required|string|max:255']);
        
        Wilayah::create([
            'nama_wilayah' => $request->nama_wilayah
        ]);

        return redirect()->back()->with('success', 'Wilayah berhasil ditambahkan!');
    }

    public function updateWilayah(Request $request, $id)
    {
        $request->validate(['nama_wilayah' => 'required|string|max:255']);
        
        $wilayah = Wilayah::findOrFail($id);
        $wilayah->update([
            'nama_wilayah' => $request->nama_wilayah
        ]);

        return redirect()->back()->with('success', 'Wilayah berhasil diperbarui!');
    }

    public function destroyWilayah($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        // Ini akan memicu penghapusan rayon & jemaat otomatis (berkat model booted)
        $wilayah->delete(); 

        return redirect()->back()->with('success', 'Wilayah beserta isinya berhasil dihapus!');
    }

    // ==========================================
    // FUNGSI UNTUK RAYON
    // ==========================================

    public function storeRayon(Request $request)
    {
        $request->validate([
            'wilayah_id' => 'required|exists:wilayahs,id',
            'nama_rayon' => 'required|string|max:255',
        ]);

        Rayon::create([
            'wilayah_id' => $request->wilayah_id,
            'nama_rayon' => $request->nama_rayon,
            'nama_penatua' => $request->nama_penatua
        ]);

        return redirect()->back()->with('success', 'Rayon berhasil ditambahkan!');
    }

    public function updateRayon(Request $request, $id)
    {
        $request->validate([
            'wilayah_id' => 'required|exists:wilayahs,id',
            'nama_rayon' => 'required|string|max:255',
        ]);

        $rayon = Rayon::findOrFail($id);
        $rayon->update([
            'wilayah_id' => $request->wilayah_id,
            'nama_rayon' => $request->nama_rayon,
            'nama_penatua' => $request->nama_penatua
        ]);

        return redirect()->back()->with('success', 'Rayon berhasil diperbarui!');
    }

    public function destroyRayon($id)
    {
        $rayon = Rayon::findOrFail($id);
        // Ini akan memicu penghapusan jemaat otomatis
        $rayon->delete(); 

        return redirect()->back()->with('success', 'Rayon beserta jemaatnya berhasil dihapus!');
    }
}