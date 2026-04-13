<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriKeuangan;
use App\Models\TransaksiKas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    // ==========================================
    // 1. HALAMAN DASHBOARD KEUANGAN
    // ==========================================
    public function index()
    {
        // 1. Saldo Saat Ini
        $saldoRutin = $this->getSaldo('Rutin');
        $saldoCadangan = $this->getSaldo('Cadangan');
        $saldoGabungan = $saldoRutin + $saldoCadangan;

        // 2. Data Grafik Line (Ikhtisar Saldo 7 Hari/Bulan Terakhir - Contoh statis untuk visual)
        $labelsLine = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $dataLineRutin = [5000, 7000, 6000, 9000, 8000, 11000, 10000]; // Ganti dengan query dinamis nanti
        $dataLineCadangan = [3000, 4000, 5500, 5000, 7000, 8500, 9000];

        // 3. Analisis Pengeluaran vs Pemasukan (Top 4 Kategori)
        $topKategori = \App\Models\KategoriKeuangan::withSum('transaksiKas', 'nominal')
                        ->take(4)->get();

        // 4. Persentase Kenaikan Kas Rutin (Bulan ini vs Bulan Lalu)
        $bulanIni = \App\Models\TransaksiKas::whereMonth('tanggal', date('m'))
                    ->whereHas('kategori', fn($q) => $q->where('peruntukan_kas', 'Rutin'))
                    ->sum('nominal');
        $bulanLalu = \App\Models\TransaksiKas::whereMonth('tanggal', date('m', strtotime('-1 month')))
                    ->whereHas('kategori', fn($q) => $q->where('peruntukan_kas', 'Rutin'))
                    ->sum('nominal');
        $persenRutin = $bulanLalu > 0 ? (($bulanIni - $bulanLalu) / $bulanLalu) * 100 : 100;

        // 5. Target Pagu (Contoh 3 Kategori)
        $targets = \App\Models\KategoriKeuangan::where('target_tahunan', '>', 0)->take(3)->get();

        $transaksiTerbaru = \App\Models\TransaksiKas::with('kategori')->orderBy('tanggal', 'desc')->take(6)->get();

        return view('keuangan.index', compact(
            'saldoRutin', 'saldoCadangan', 'saldoGabungan', 'labelsLine', 
            'dataLineRutin', 'dataLineCadangan', 'topKategori', 
            'persenRutin', 'targets', 'transaksiTerbaru'
        ));
    }

    private function getSaldo($tipe) {
        $masuk = \App\Models\TransaksiKas::whereHas('kategori', fn($q) => $q->where('peruntukan_kas', $tipe)->where('jenis', 'Pemasukan'))->sum('nominal');
        $keluar = \App\Models\TransaksiKas::whereHas('kategori', fn($q) => $q->where('peruntukan_kas', $tipe)->where('jenis', 'Pengeluaran'))->sum('nominal');
        return $masuk - $keluar;
    }

    // ==========================================
    // 2. MANAJEMEN KATEGORI (RAB/RAP)
    // ==========================================
    public function kategoriIndex()
    {
        // Mengambil semua data kategori, dipisah berdasarkan Kas Rutin & Cadangan
        $kategoriRutin = KategoriKeuangan::where('peruntukan_kas', 'Rutin')->get();
        $kategoriCadangan = KategoriKeuangan::where('peruntukan_kas', 'Cadangan')->get();

        return view('keuangan.kategori', compact('kategoriRutin', 'kategoriCadangan'));
    }

    public function kategoriStore(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:Pemasukan,Pengeluaran',
            'peruntukan_kas' => 'required|in:Rutin,Cadangan',
            'target_tahunan' => 'required|numeric|min:0'
        ]);

        KategoriKeuangan::create($request->all());

        return redirect()->back()->with('success', 'Kategori Pos Anggaran berhasil ditambahkan!');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:Pemasukan,Pengeluaran',
            'peruntukan_kas' => 'required|in:Rutin,Cadangan',
            'target_tahunan' => 'required|numeric|min:0'
        ]);

        $kategori = KategoriKeuangan::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->back()->with('success', 'Kategori Pos Anggaran berhasil diperbarui!');
    }

    public function kategoriDestroy($id)
    {
        $kategori = KategoriKeuangan::findOrFail($id);
        // Hati-hati: karena relasi cascade, jika kategori dihapus, transaksi di dalamnya akan ikut terhapus!
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori beserta transaksinya berhasil dihapus!');
    }

    // ==========================================
    // 3. BUKU KAS HARIAN (TRANSAKSI)
    // ==========================================
    public function bukuKasIndex(Request $request)
    {
        // Menampilkan daftar kategori untuk form input (Pemasukan & Pengeluaran)
        $kategoriPemasukan = KategoriKeuangan::where('jenis', 'Pemasukan')->get();
        $kategoriPengeluaran = KategoriKeuangan::where('jenis', 'Pengeluaran')->get();

        // Mengambil riwayat transaksi, diurutkan dari yang terbaru
        $transaksis = TransaksiKas::with('kategori')->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        return view('keuangan.buku-kas', compact('kategoriPemasukan', 'kategoriPengeluaran', 'transaksis'));
    }

    public function transaksiStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_id' => 'required|exists:kategori_keuangans,id',
            'uraian' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            // 'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Opsional jika mau upload bukti
        ]);

        TransaksiKas::create($request->all());

        return redirect()->back()->with('success', 'Transaksi Kas berhasil dicatat!');
    }

    public function transaksiUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_id' => 'required|exists:kategori_keuangans,id',
            'uraian' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1'
        ]);

        $transaksi = TransaksiKas::findOrFail($id);
        $transaksi->update($request->all());

        return redirect()->back()->with('success', 'Transaksi Kas berhasil diperbarui!');
    }

    public function transaksiDestroy($id)
    {
        $transaksi = TransaksiKas::findOrFail($id);
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi Kas berhasil dihapus!');
    }

    // ==========================================
    // 4. HALAMAN LAPORAN KEUANGAN
    // ==========================================
    public function laporanIndex(Request $request)
    {
        // 1. Ambil Parameter Filter (Bulan default ke bulan saat ini, tapi bisa 'Semua' untuk tahunan)
        $bulanPilih = $request->get('bulan', date('m'));
        $tahunPilih = $request->get('tahun', date('Y'));
        $kasPilih = $request->get('kas', 'Semua');

        // 2. Siapkan Query Dasar (Filter Tahun Pasti Aktif)
        $query = TransaksiKas::with('kategori')
                    ->whereYear('tanggal', $tahunPilih);

        // 3. Filter Bulan (Aktif HANYA jika TIDAK memilih 'Semua')
        if ($bulanPilih !== 'Semua') {
            $query->whereMonth('tanggal', $bulanPilih);
        }

        // 4. Filter Jenis Kamar Kas
        if ($kasPilih !== 'Semua') {
            $query->whereHas('kategori', function($q) use ($kasPilih) {
                $q->where('peruntukan_kas', $kasPilih);
            });
        }

        // 5. Eksekusi Query
        $transaksis = $query->orderBy('tanggal', 'asc')->get();

        // 6. Hitung Rekapitulasi
        $totalPemasukan = $transaksis->filter(function ($item) {
            return $item->kategori->jenis == 'Pemasukan';
        })->sum('nominal');

        $totalPengeluaran = $transaksis->filter(function ($item) {
            return $item->kategori->jenis == 'Pengeluaran';
        })->sum('nominal');

        $saldoBulanIni = $totalPemasukan - $totalPengeluaran;

        $tahunSekarang = date('Y');
        $daftarTahun = range(2024, $tahunSekarang + 1);

        return view('keuangan.laporan', compact(
            'transaksis', 'bulanPilih', 'tahunPilih', 'kasPilih', 
            'totalPemasukan', 'totalPengeluaran', 'saldoBulanIni', 'daftarTahun'
        ));
    }

    // ==========================================
    // 5. EKSPOR PDF (STANDAR AKUNTANSI STRICT)
    // ==========================================
    public function exportPdf(Request $request)
    {
        // 1. VALIDASI FILTER KAS (STRICT MODE)
        $kasPilih = $request->get('kas');
        if (!$kasPilih || $kasPilih === 'Semua') {
            return back()->with('error', 'Pilih sumber kas terlebih dahulu untuk mencetak laporan resmi.');
        }

        $jenisPilih = $request->get('jenis', 'bku');
        $bulanPilih = $request->get('bulan', date('m'));
        $tahunPilih = $request->get('tahun', date('Y'));

        // 2. SETUP PERIODE
        if ($bulanPilih === 'Semua') {
            $startDate = Carbon::create($tahunPilih, 1, 1)->startOfDay();
            $endDate = Carbon::create($tahunPilih, 12, 31)->endOfDay();
            $teksPeriode = 'Tahun ' . $tahunPilih;
        } else {
            $startDate = Carbon::create($tahunPilih, $bulanPilih, 1)->startOfMonth();
            $endDate = Carbon::create($tahunPilih, $bulanPilih, 1)->endOfMonth();
            $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
            $teksPeriode = $namaBulan[str_pad($bulanPilih, 2, '0', STR_PAD_LEFT)] . ' ' . $tahunPilih;
        }

        // 3. INISIALISASI VARIABEL GLOBAL
        $saldoAwal = 0;
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $dataLaporan = [];

        // 4. LOGIKA BERDASARKAN JENIS LAPORAN
        if ($jenisPilih === 'realisasi') {
            // A. LAPORAN REALISASI ANGGARAN (Tidak Tampil Transaksi)
            $judulLaporan = 'LAPORAN REALISASI ANGGARAN';
            $dataLaporan = KategoriKeuangan::where('peruntukan_kas', $kasPilih)
                ->withSum(['transaksiKas' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                }], 'nominal')
                ->get();

        } else {
            // B. LAPORAN TRANSAKSI (BKU, PEMASUKAN, PENGELUARAN)
            
            // Hitung Saldo Awal (Khusus BKU)
            if ($jenisPilih === 'bku') {
                $judulLaporan = 'BUKU KAS UMUM';
                $transaksiLama = TransaksiKas::with('kategori')
                    ->where('tanggal', '<', $startDate->format('Y-m-d'))
                    ->whereHas('kategori', fn($q) => $q->where('peruntukan_kas', $kasPilih))
                    ->get();
                
                foreach ($transaksiLama as $t) {
                    $saldoAwal += ($t->kategori->jenis === 'Pemasukan' ? $t->nominal : -$t->nominal);
                }
            } elseif ($jenisPilih === 'pemasukan') {
                $judulLaporan = 'LAPORAN PEMASUKAN';
            } else {
                $judulLaporan = 'LAPORAN PENGELUARAN';
            }

            // Query Transaksi Utama (Filter Dinamis & Terisolasi)
            $query = TransaksiKas::with('kategori')
                ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->whereHas('kategori', function($q) use ($kasPilih) {
                    $q->where('peruntukan_kas', $kasPilih);
                });

            // Filter Spesifik Jenis Laporan
            if ($jenisPilih === 'pemasukan') {
                $query->whereHas('kategori', fn($q) => $q->where('jenis', 'Pemasukan'));
            } elseif ($jenisPilih === 'pengeluaran') {
                $query->whereHas('kategori', fn($q) => $q->where('jenis', 'Pengeluaran'));
            }

            $dataLaporan = $query->orderBy('tanggal', 'asc')->get();

            // Hitung Total untuk Summary
        $transaksis = $query->orderBy('tanggal', 'asc')->get();
            $dataLaporan = []; // Kosongkan array untuk diisi ulang dengan format benar
            $saldoBerjalan = $saldoAwal;

            // FIX BUG LOGIKA NOMINAL DAN JSON KATEGORI
            foreach ($transaksis as $index => $item) {
                $isMasuk = $item->kategori->jenis === 'Pemasukan';
                
                // Mapping nominal yang benar
                $masuk = $isMasuk ? $item->nominal : 0;
                $keluar = !$isMasuk ? $item->nominal : 0;
                
                $saldoBerjalan += ($masuk - $keluar);
                $totalPemasukan += $masuk;
                $totalPengeluaran += $keluar;

                $dataLaporan[] = [
                    'no' => $index + 1,
                    'tanggal' => Carbon::parse($item->tanggal)->format('d/m/Y'),
                    'no_bukti' => 'BKT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                    'uraian' => $item->uraian,
                    'sumber_kas' => $item->kategori->peruntukan_kas,
                    
                    // FIX BUG JSON: Hanya simpan string nama_kategori
                    'kategori' => $item->kategori->nama_kategori, 
                    
                    'pemasukan' => $masuk,
                    'pengeluaran' => $keluar,
                    'saldo' => $saldoBerjalan,
                    'keterangan' => $item->keterangan ?? '-'
                ];
            }
        }

        // 5. PILIH TEMPLATE BLADE BERDASARKAN JENIS (CLEAN CODE)
        $view = match($jenisPilih) {
            'pemasukan'   => 'keuangan.pdf-pemasukan',
            'pengeluaran' => 'keuangan.pdf-pengeluaran',
            'realisasi'   => 'keuangan.pdf-realisasi',
            default       => 'keuangan.pdf-bku',
        };

        $data = [
            'judul' => $judulLaporan,
            'sumberKas' => $kasPilih, // Sudah dipastikan murni 1 kas
            'periode' => $teksPeriode,
            'laporan' => $dataLaporan,
            'saldoAwal' => $saldoAwal,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAwal + $totalPemasukan - $totalPengeluaran,
            'tanggal_cetak' => Carbon::now()->format('d/m/Y')
        ];

        // 6. RENDER PDF
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'landscape');
        return $pdf->stream(str_replace(' ', '_', $judulLaporan) . "_{$kasPilih}_{$bulanPilih}_{$tahunPilih}.pdf");
    }
}