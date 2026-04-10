<?php

namespace App\Exports;

use App\Models\Jemaat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JemaatExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil semua data jemaat beserta nama wilayah dan rayonnya
        return Jemaat::with(['wilayah', 'rayon'])->get();
    }

    public function headings(): array
    {
        // Judul baris paling atas di Excel nanti
        return [
            'NIK', 'No KK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 
            'L/P', 'Gol. Darah', 'WhatsApp', 'Alamat', 
            'Wilayah', 'Rayon', 'Status', 'Pekerjaan'
        ];
    }

    public function map($jemaat): array
    {
        // Memasukkan data dari database ke kolom Excel
        return [
            $jemaat->nik,
            $jemaat->no_kk,
            $jemaat->nama_lengkap,
            $jemaat->tempat_lahir,
            $jemaat->tgl_lahir,
            $jemaat->jenis_kelamin,
            $jemaat->gol_darah,
            $jemaat->no_whatsapp,
            $jemaat->alamat_domisili,
            $jemaat->wilayah->nama_wilayah ?? '-', // Mencegah error jika wilayah kosong
            $jemaat->rayon->nama_rayon ?? '-',
            $jemaat->status_keanggotaan,
            $jemaat->pekerjaan
        ];
    }
}