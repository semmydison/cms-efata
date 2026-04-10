<?php

namespace App\Http\Controllers;

use App\Models\Warta;
use App\Models\Jemaat;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WartaController extends Controller
{
    public function index(Request $request)
    {
        $kategoriAktif = $request->get('kategori', 'all');
        $query = Warta::orderBy('tanggal_tampil', 'desc')->orderBy('created_at', 'desc');
        if ($kategoriAktif != 'all') { $query->where('kategori', $kategoriAktif); }
        
        $wartas = $query->get();
        return view('warta.index', compact('wartas', 'kategoriAktif'));
    }

    public function store(Request $request)
    {
        $data = $request->except(['file_dokumen', 'gambar']);

        if ($request->kategori != 'kehadiran') {
            $data['hadir_laki'] = null; $data['hadir_perempuan'] = null;
        } else {
            $data['konten'] = null;
        }

        // Upload PDF
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->storeAs('warta/dokumen', time() . '_' . Str::slug($data['file_name']), 'public');
        }

        // Upload Foto/Gambar Landing Page
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('warta/gambar', 'public');
        }

        Warta::create($data);
        return redirect()->back();
    }

    // FUNGSI BARU: UPDATE (EDIT) WARTA
    public function update(Request $request, $id)
    {
        $warta = Warta::findOrFail($id);
        $data = $request->except(['file_dokumen', 'gambar', '_method']);

        if ($request->kategori != 'kehadiran') {
            $data['hadir_laki'] = null; $data['hadir_perempuan'] = null;
        }

        if ($request->hasFile('file_dokumen')) {
            if ($warta->file_path) Storage::disk('public')->delete($warta->file_path);
            $file = $request->file('file_dokumen');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->storeAs('warta/dokumen', time() . '_' . Str::slug($data['file_name']), 'public');
        }

        if ($request->hasFile('gambar')) {
            if ($warta->gambar) Storage::disk('public')->delete($warta->gambar);
            $data['gambar'] = $request->file('gambar')->store('warta/gambar', 'public');
        }

        $warta->update($data);
        return redirect()->back();
    }

    public function destroy($id)
    {
        $warta = Warta::findOrFail($id);
        if ($warta->file_path) Storage::disk('public')->delete($warta->file_path);
        if ($warta->gambar) Storage::disk('public')->delete($warta->gambar);
        $warta->delete();
        return redirect()->back();
    }

    public function downloadPdf($id)
    {
        $warta = Warta::findOrFail($id);
        if(!$warta->file_path) return abort(404);
        return response()->download(storage_path('app/public/' . $warta->file_path), $warta->file_name);
    }

    public function layarPublik()
    {
        $today = Carbon::today();
        $pesanGembala = Warta::where('kategori', 'pesan')->orderBy('tanggal_tampil', 'desc')->first();
        $laporanKehadiran = Warta::where('kategori', 'kehadiran')->orderBy('tanggal_tampil', 'desc')->first();
        $ultahHariIni = Jemaat::whereMonth('tgl_lahir', $today->month)->whereDay('tgl_lahir', $today->day)->where('status_keanggotaan', 'Aktif')->get();
        $agendaTerdekat = Agenda::where('tanggal_mulai', '>=', $today->format('Y-m-d'))->orderBy('tanggal_mulai', 'asc')->first();

        return view('warta.layar', compact('pesanGembala', 'laporanKehadiran', 'ultahHariIni', 'agendaTerdekat'));
    }
}