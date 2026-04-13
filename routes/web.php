<?php

use Illuminate\Support\Facades\Route;

// Kumpulkan semua pemanggilan Controller di atas agar rapi dan tidak duplikat
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\ProfilGerejaController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\LiturgiController;
use App\Http\Controllers\WartaController;
// Catatan: RayonController tidak dipanggil karena fungsinya sudah kita satukan di WilayahController

Route::get('/', function () {
    return view('dashboard'); 
});

// ==========================================
// --- Rute Wilayah & Rayon ---
// ==========================================
Route::get('/wilayah', [WilayahController::class, 'index']);

// Rute Kelola Wilayah
Route::post('/wilayah/store', [WilayahController::class, 'storeWilayah']);
Route::put('/wilayah/update/{id}', [WilayahController::class, 'updateWilayah']);
Route::delete('/wilayah/delete/{id}', [WilayahController::class, 'destroyWilayah']);

// Rute Kelola Rayon
Route::post('/wilayah/rayon/store', [WilayahController::class, 'storeRayon']);
Route::put('/wilayah/rayon/update/{id}', [WilayahController::class, 'updateRayon']);
Route::delete('/wilayah/rayon/delete/{id}', [WilayahController::class, 'destroyRayon']);


// ==========================================
// --- Rute Profil Gereja ---
// ==========================================
Route::get('/profil-gereja', [ProfilGerejaController::class, 'index']);
Route::post('/profil-gereja/update', [ProfilGerejaController::class, 'update']);

// Rute Pengurus Majelis
Route::post('/profil-gereja/pengurus/store', [ProfilGerejaController::class, 'storePengurus']);
Route::put('/profil-gereja/pengurus/update/{id}', [ProfilGerejaController::class, 'updatePengurus']);
Route::delete('/profil-gereja/pengurus/delete/{id}', [ProfilGerejaController::class, 'deletePengurus']);


// ==========================================
// --- Rute Data Jemaat ---
// ==========================================
Route::get('/jemaat', [JemaatController::class, 'index']);
Route::post('/jemaat/store', [JemaatController::class, 'store']);
Route::put('/jemaat/update/{id}', [JemaatController::class, 'update']);
Route::delete('/jemaat/delete/{id}', [JemaatController::class, 'destroy']);

// Import & Export Jemaat
Route::get('/jemaat/export', [JemaatController::class, 'exportExcel']);
Route::post('/jemaat/import', [JemaatController::class, 'importExcel']);
Route::get('/jemaat/template', [JemaatController::class, 'downloadTemplate']);


// ==========================================
// MODUL KEUANGAN (KAS RUTIN & CADANGAN)
// ==========================================
Route::prefix('keuangan')->group(function () {
    // 1. Dashboard Keuangan
    Route::get('/', [App\Http\Controllers\KeuanganController::class, 'index'])->name('keuangan.index');

    // 2. Manajemen Kategori & Anggaran (RAB/RAP)
    Route::get('/kategori', [App\Http\Controllers\KeuanganController::class, 'kategoriIndex'])->name('keuangan.kategori');
    Route::post('/kategori/store', [App\Http\Controllers\KeuanganController::class, 'kategoriStore']);
    Route::put('/kategori/update/{id}', [App\Http\Controllers\KeuanganController::class, 'kategoriUpdate']);
    Route::delete('/kategori/delete/{id}', [App\Http\Controllers\KeuanganController::class, 'kategoriDestroy']);

    // 3. Buku Kas Harian (Transaksi)
    Route::get('/buku-kas', [App\Http\Controllers\KeuanganController::class, 'bukuKasIndex'])->name('keuangan.buku-kas');
    Route::post('/transaksi/store', [App\Http\Controllers\KeuanganController::class, 'transaksiStore']);
    Route::put('/transaksi/update/{id}', [App\Http\Controllers\KeuanganController::class, 'transaksiUpdate']);
    Route::delete('/transaksi/delete/{id}', [App\Http\Controllers\KeuanganController::class, 'transaksiDestroy']);

    // 4. Laporan
    Route::get('/laporan', [App\Http\Controllers\KeuanganController::class, 'laporanIndex'])->name('keuangan.laporan');
});


// ==========================================
// --- Rute Kalender Acara ---
// ==========================================
Route::get('/kalender', [KalenderController::class, 'index']);
Route::post('/kalender/store', [KalenderController::class, 'store']);
Route::put('/kalender/update/{id}', [KalenderController::class, 'update']);
Route::delete('/kalender/delete/{id}', [KalenderController::class, 'destroy']);


// ==========================================
// --- Rute Liturgi & Slide ---
// ==========================================
Route::get('/liturgi', [LiturgiController::class, 'index']);
Route::post('/liturgi/store', [LiturgiController::class, 'store']);
Route::put('/liturgi/aktifkan/{id}', [LiturgiController::class, 'aktifkan']);
Route::delete('/liturgi/delete/{id}', [LiturgiController::class, 'destroy']);

// Rute Download Liturgi
Route::get('/liturgi/download/{id}', [LiturgiController::class, 'downloadAdmin']);
Route::get('/liturgi/publik/download', [LiturgiController::class, 'downloadPublik']);


// ==========================================
// --- Rute Warta Jemaat ---
// ==========================================
Route::get('/warta', [WartaController::class, 'index']);
Route::post('/warta/store', [WartaController::class, 'store']);
Route::put('/warta/update/{id}', [WartaController::class, 'update']);
Route::delete('/warta/delete/{id}', [WartaController::class, 'destroy']);
Route::get('/warta/download/{id}', [WartaController::class, 'downloadPdf']);

// Rute khusus Layar Publik Warta
Route::get('/warta/publik', [WartaController::class, 'layarPublik']);

Route::get('/keuangan/laporan/pdf', [KeuanganController::class, 'exportPdf'])->name('keuangan.laporan.pdf');

// Rute untuk menampilkan halaman laporan web
Route::get('/keuangan/laporan', [\App\Http\Controllers\KeuanganController::class, 'laporanIndex'])->name('keuangan.laporan');

// Rute BARU untuk mencetak PDF DomPDF
Route::get('/keuangan/laporan/pdf', [\App\Http\Controllers\KeuanganController::class, 'exportPdf'])->name('keuangan.laporan.pdf');