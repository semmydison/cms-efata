<?php

namespace App\Imports;

use App\Models\Jemaat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class JemaatImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Tolak jika NIK kosong
        if (empty($row['nik'])) return null;

        // 2. Parser Tanggal Lahir (Dibiarkan kosong/null jika Excel kosong, tidak diubah jadi 1970)
        $tglLahir = null; 
        try {
            if (!empty($row['tgl_lahir'])) {
                if (is_numeric($row['tgl_lahir'])) {
                    $tglLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir'])->format('Y-m-d');
                } else {
                    $tglLahir = Carbon::parse($row['tgl_lahir'])->format('Y-m-d');
                }
            }
        } catch (\Exception $e) {}

        // 3. Konversi Minat Pelayanan
        $minat = [];
        if (!empty($row['minat_pelayanan'])) {
            $minat = array_map('trim', explode(',', $row['minat_pelayanan']));
        }

        // 4. Masukkan ke Database (Tanpa Wilayah Penampungan)
        return new Jemaat([
            'wilayah_id'         => $row['wilayah_id'] ?? null,
            'rayon_id'           => $row['rayon_id'] ?? null,
            'nik'                => trim($row['nik']),
            'no_kk'              => $row['no_kk'] ?? '-',
            'nama_lengkap'       => $row['nama_lengkap'] ?? 'Tanpa Nama',
            'tempat_lahir'       => $row['tempat_lahir'] ?? '-',
            'tgl_lahir'          => $tglLahir,
            'jenis_kelamin'      => strtoupper(trim($row['jenis_kelamin'] ?? 'L')),
            'gol_darah'          => strtoupper(trim($row['gol_darah'] ?? '-')),
            'no_whatsapp'        => $row['no_whatsapp'] ?? '-',
            'alamat_domisili'    => $row['alamat_domisili'] ?? '-',
            'status_keanggotaan' => ucwords(strtolower(trim($row['status_keanggotaan'] ?? 'Aktif'))),
            'status_baptis'      => (strtolower(trim($row['status_baptis'] ?? '')) == 'ya'),
            'status_sidi'        => (strtolower(trim($row['status_sidi'] ?? '')) == 'ya'),
            'pekerjaan'          => $row['pekerjaan'] ?? '-',
            'minat_pelayanan'    => $minat,
            'tampilkan_ultah'    => (strtolower(trim($row['tampilkan_ultah'] ?? '')) == 'ya'),
        ]);
    }
}