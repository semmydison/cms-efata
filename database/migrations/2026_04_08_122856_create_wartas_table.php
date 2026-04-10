<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
    {
        Schema::create('wartas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // pesan, surat, himbauan, duka, kehadiran
            $table->date('tanggal_tampil');
            $table->text('konten')->nullable(); // Untuk isi teks warta
            $table->string('gambar')->nullable(); // Kolom Baru untuk Foto Utama Landing Page
            $table->string('file_path')->nullable(); // Untuk file PDF jika ada
            $table->string('file_name')->nullable();
            
            // Kolom khusus untuk Kategori Laporan Kehadiran
            $table->integer('hadir_laki')->nullable();
            $table->integer('hadir_perempuan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wartas');
    }
};
