<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateJemaatExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nik', 'no_kk', 'nama_lengkap', 'tempat_lahir', 'tgl_lahir', 
            'jenis_kelamin', 'gol_darah', 'no_whatsapp', 'alamat_domisili', 
            'status_keanggotaan', 'status_baptis', 'status_sidi', 'pekerjaan', 
            'minat_pelayanan', 'tampilkan_ultah', 'wilayah_id', 'rayon_id'
        ];
    }

    public function array(): array
    {
        return [
            // Baris ke-2: Kita berikan Data Contoh agar operator paham cara mengisinya
            [
                '1234567890123456',          // NIK (Wajib 16 Digit)
                '6543210987654321',          // No KK
                'Bpk. Contoh Jemaat',        // Nama Lengkap
                'Kupang',                    // Tempat Lahir
                '1980-08-17',                // Tanggal Lahir (YYYY-MM-DD)
                'L',                         // Jenis Kelamin (L / P)
                'O',                         // Gol Darah (A/B/AB/O)
                '081234567890',              // WhatsApp (Awali dengan 0)
                'Jl. El Tari No. 1, Oebobo', // Alamat Domisili
                'Aktif',                     // Status (Aktif / Pindah / Meninggal)
                'ya',                        // Baptis (ya / tidak)
                'ya',                        // Sidi (ya / tidak)
                'PNS',                       // Pekerjaan
                'Musik & Pujian, Diakonia',  // Minat Pelayanan (Pisahkan dengan koma)
                'ya',                        // Tampilkan Ultah (ya / tidak)
                '1',                         // ID Wilayah (Bisa dikosongkan)
                '1'                          // ID Rayon (Bisa dikosongkan)
            ]
        ];
    }
}