<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Contoh: Kolekte Utama, Listrik
            $table->enum('jenis', ['Pemasukan', 'Pengeluaran']);
            $table->enum('peruntukan_kas', ['Rutin', 'Cadangan']); 
            $table->bigInteger('target_tahunan')->default(0); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_keuangans');
    }
};