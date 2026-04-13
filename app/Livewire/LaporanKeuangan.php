<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiKas;
use Carbon\Carbon;

class LaporanKeuangan extends Component
{
    // Navigasi & Filter
    public $step = 1; 
    public $kas = ''; 
    public $jenisLaporan = 'bku'; 
    public $periode = 'bulanan'; 
    public $bulan, $tahun;

    public function mount() {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }

    public function pilihKas($jenis) {
        $this->kas = $jenis;
        $this->step = 2;
    }

    public function kembali() {
        $this->step = 1;
        $this->kas = '';
    }

    public function render() {
        // Bypass Query Jika Masih di Layar Utama
        if ($this->step != 2) {
            return view('livewire.laporan-keuangan', [
                'transaksi' => [], 'totalMasuk' => 0, 'totalKeluar' => 0,
                'daftarTahun' => range(2024, date('Y') + 1)
            ]);
        }

        // Query Ambil Data
        $query = TransaksiKas::with('kategori');
        
        // Filter Kas
        if ($this->kas !== 'Semua' && $this->kas !== '') {
            $query->whereHas('kategori', fn($q) => $q->where('peruntukan_kas', $this->kas));
        }

        // Filter Periode
        if ($this->periode === 'bulanan') {
            $query->whereMonth('tanggal', $this->bulan)->whereYear('tanggal', $this->tahun);
        } else {
            $query->whereYear('tanggal', $this->tahun);
        }

        // Filter Jenis Laporan (Pemasukan/Pengeluaran Khusus)
        if ($this->jenisLaporan === 'pemasukan') {
            $query->whereHas('kategori', fn($q) => $q->where('jenis', 'Pemasukan'));
        } elseif ($this->jenisLaporan === 'pengeluaran') {
            $query->whereHas('kategori', fn($q) => $q->where('jenis', 'Pengeluaran'));
        }

        // Eksekusi Data
        $data = $query->orderBy('tanggal', 'asc')->get();

        // Hitung Total Secara Akurat
        $totalMasuk = $data->filter(fn($t) => $t->kategori->jenis === 'Pemasukan')->sum('nominal');
        $totalKeluar = $data->filter(fn($t) => $t->kategori->jenis === 'Pengeluaran')->sum('nominal');

        return view('livewire.laporan-keuangan', [
            'transaksi' => $data,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'daftarTahun' => range(2024, date('Y') + 1)
        ]);
    }
}