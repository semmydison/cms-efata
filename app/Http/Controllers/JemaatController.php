<?php

namespace App\Http\Controllers;
use App\Exports\TemplateJemaatExport;

use App\Models\Jemaat;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Exports\JemaatExport;
use App\Imports\JemaatImport;
use Maatwebsite\Excel\Facades\Excel;

class JemaatController extends Controller
{
    public function index()
{
    $jemaats = \App\Models\Jemaat::with(['wilayah', 'rayon'])->orderBy('created_at', 'desc')->get();
    $wilayahs = \App\Models\Wilayah::with('rayons')->get();
    
    // Ambil data Profil Gereja untuk Kop Surat PDF
    $profilGereja = \App\Models\ProfilGereja::first(); 
    
    return view('jemaat.index', compact('jemaats', 'wilayahs', 'profilGereja'));
}

    public function store(Request $request)
    {
        $data = $request->all();
        
        // Pastikan checkbox boolean diatur dengan benar (jika tidak dicentang, anggap false/0)
        $data['status_baptis'] = $request->has('status_baptis');
        $data['status_sidi'] = $request->has('status_sidi');
        $data['tampilkan_ultah'] = $request->has('tampilkan_ultah');

        Jemaat::create($data);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $jemaat = Jemaat::findOrFail($id);
        $data = $request->all();

        $data['status_baptis'] = $request->has('status_baptis');
        $data['status_sidi'] = $request->has('status_sidi');
        $data['tampilkan_ultah'] = $request->has('tampilkan_ultah');
        
        // Jika minat pelayanan dikosongkan saat edit, pastikan datanya menjadi null
        if(!$request->has('minat_pelayanan')) {
            $data['minat_pelayanan'] = null;
        }

        $jemaat->update($data);
        return redirect()->back();
    }

    public function destroy($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        $jemaat->delete();
        return redirect()->back();
    }

// Fungsi Unduh Excel
    public function exportExcel()
    {
        return Excel::download(new JemaatExport, 'Data_Jemaat_EFATA.xlsx');
    }

    // Fungsi Unggah Excel
    public function importExcel(Request $request)
    {
        // Validasi agar yang diupload wajib file Excel
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new JemaatImport, $request->file('file_excel'));
        
        return redirect()->back();
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new TemplateJemaatExport, 'Template_Import_Jemaat.xlsx');
    }
}