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
     Schema::create('penguruses', function (Blueprint $table) {
         $table->id();
         $table->string('foto')->nullable();
         $table->string('nama_lengkap');
         $table->string('jabatan');
         $table->year('periode_mulai')->nullable();
         $table->year('periode_selesai')->nullable();
         $table->timestamps();
     });
 }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penguruses');
    }
};
