<?php

namespace App\Http\Controllers;

use App\Models\Liturgi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LiturgiController extends Controller
{
    public function index()
    {
        $activeLiturgi = Liturgi::where('is_active', true)->first();
        $arsipLiturgi = Liturgi::where('is_active', false)->orderBy('created_at', 'desc')->get();
        
        // Menghitung total file untuk indikator di UI
        $totalFiles = Liturgi::count();

        return view('liturgi.index', compact('activeLiturgi', 'arsipLiturgi', 'totalFiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'tanggal' => 'required|date',
            'file_slide' => 'required|mimes:pdf,pptx,ppt|max:25600', 
        ]);

        $file = $request->file('file_slide');
        $originalName = $file->getClientOriginalName();
        $fileSize = $this->formatBytes($file->getSize());
        
        $safeFileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('liturgi', $safeFileName, 'public');

        // Jika ini adalah file pertama yang pernah diupload, paksa statusnya menjadi aktif
        $isFirstFile = Liturgi::count() === 0;
        $isActive = $request->has('langsung_tayang') || $isFirstFile;

        if ($isActive) {
            Liturgi::query()->update(['is_active' => false]);
        }

        Liturgi::create([
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'file_path' => $path,
            'file_name' => $originalName, 
            'file_size' => $fileSize,
            'is_active' => $isActive,
        ]);

        // JALANKAN FUNGSI PEMBERSIH OTOMATIS (Maksimal 10 File)
        $this->batasiArsip();

        return redirect()->back();
    }

    public function aktifkan($id)
    {
        Liturgi::query()->update(['is_active' => false]);
        $liturgi = Liturgi::findOrFail($id);
        $liturgi->update(['is_active' => true]);
        return redirect()->back();
    }

    // Fungsi hentikan() sudah dihapus dari sistem sesuai perintah Anda

    public function destroy($id)
    {
        $liturgi = Liturgi::findOrFail($id);
        if (Storage::disk('public')->exists($liturgi->file_path)) {
            Storage::disk('public')->delete($liturgi->file_path);
        }
        $liturgi->delete();

        // Jika tidak sengaja menghapus file aktif, otomatis aktifkan arsip terbaru (Fail-safe)
        if ($liturgi->is_active) {
            $latestArsip = Liturgi::orderBy('created_at', 'desc')->first();
            if ($latestArsip) {
                $latestArsip->update(['is_active' => true]);
            }
        }

        return redirect()->back();
    }

    public function downloadAdmin($id)
    {
        $liturgi = Liturgi::findOrFail($id);
        $path = storage_path('app/public/' . $liturgi->file_path);
        return response()->download($path, $liturgi->file_name);
    }

    public function downloadPublik()
    {
        $activeLiturgi = Liturgi::where('is_active', true)->first();
        
        if (!$activeLiturgi) {
            return abort(404, 'Saat ini tidak ada slide ibadah yang aktif untuk publik.');
        }

        $path = storage_path('app/public/' . $activeLiturgi->file_path);
        return response()->download($path, $activeLiturgi->file_name);
    }

    // --- FUNGSI PRIVATE: PEMBERSIH OTOMATIS ARSIP (MAKS. 10) ---
    private function batasiArsip()
    {
        $maxFiles = 10;
        $totalFiles = Liturgi::count();

        if ($totalFiles > $maxFiles) {
            // Ambil file-file paling lawas yang sedang TIDAK aktif
            $filesToDelete = Liturgi::where('is_active', false)
                                    ->orderBy('created_at', 'asc')
                                    ->take($totalFiles - $maxFiles)
                                    ->get();

            foreach ($filesToDelete as $oldFile) {
                if (Storage::disk('public')->exists($oldFile->file_path)) {
                    Storage::disk('public')->delete($oldFile->file_path);
                }
                $oldFile->delete();
            }
        }
    }

    private function formatBytes($bytes, $precision = 2) { 
        $units = ['B', 'KB', 'MB', 'GB', 'TB']; 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow]; 
    }
}