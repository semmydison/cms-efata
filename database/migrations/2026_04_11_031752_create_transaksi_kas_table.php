<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kategori_id')->constrained('kategori_keuangans')->onDelete('cascade');
            $table->string('uraian');
            $table->bigInteger('nominal');
            $table->string('bukti_foto')->nullable();
            
            // Opsional: Jika Anda belum mengaktifkan fitur login (users), 
            // baris user_id ini bisa di-comment dulu dengan menambahkan // di depannya
            // $table->foreignId('user_id')->constrained('users'); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_kas');
    }
};