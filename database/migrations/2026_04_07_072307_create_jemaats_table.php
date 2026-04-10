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
        Schema::create('jemaats', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16);
            $table->string('nama_lengkap');
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('gol_darah', 5)->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->text('alamat_domisili')->nullable();
            
            // Relasi Gerejawi
            $table->foreignId('wilayah_id')->constrained('wilayahs')->onDelete('restrict');
            $table->foreignId('rayon_id')->constrained('rayons')->onDelete('restrict');
            $table->enum('status_keanggotaan', ['Aktif', 'Pindah', 'Meninggal Dunia'])->default('Aktif');
            $table->boolean('status_baptis')->default(false);
            $table->boolean('status_sidi')->default(false);
            
            // Preferensi
            $table->string('pekerjaan')->nullable();
            $table->json('minat_pelayanan')->nullable(); // Disimpan dalam bentuk JSON karena bisa pilih lebih dari 1
            $table->boolean('tampilkan_ultah')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemaats');
    }
};
