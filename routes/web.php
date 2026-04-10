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
// --- Rute Keuangan / Transaksi Kas ---
// ==========================================
Route::get('/keuangan', [KeuanganController::class, 'index']);
Route::post('/keuangan/store', [KeuanganController::class, 'store']);
Route::get('/keuangan/cetak', [KeuanganController::class, 'cetakLaporan']);


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