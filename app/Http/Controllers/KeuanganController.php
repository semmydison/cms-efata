<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiKas::orderBy('tanggal', 'desc')->get();

        // 1. Hitung Ringkasan Saldo per Buku Kas
        $summary = [
            'umum' => ['masuk' => 0, 'keluar' => 0, 'saldo' => 0],
            'lelang' => ['masuk' => 0, 'keluar' => 0, 'saldo' => 0],
            'kategorial' => ['masuk' => 0, 'keluar' => 0, 'saldo' => 0],
        ];

        foreach ($transaksis as $trx) {
            $kas = $trx->buku_kas;
            $tipe = $trx->tipe_transaksi;
            
            if(isset($summary[$kas])) {
                $summary[$kas][$tipe] += $trx->nominal;
            }
        }

        // Kalkulasi Saldo Akhir tiap kas
        foreach (['umum', 'lelang', 'kategorial'] as $kas) {
            $summary[$kas]['saldo'] = $summary[$kas]['masuk'] - $summary[$kas]['keluar'];
        }

        // 2. Siapkan Data Grafik (6 Bulan Terakhir)
        $chartData = [
            'labels' => [],
            'umum' => ['masuk' => [], 'keluar' => []],
            'lelang' => ['masuk' => [], 'keluar' => []],
            'kategorial' => ['masuk' => [], 'keluar' => []],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartData['labels'][] = $month->translatedFormat('M Y');
            
            foreach(['umum', 'lelang', 'kategorial'] as $kas) {
                $masuk = TransaksiKas::where('buku_kas', $kas)->where('tipe_transaksi', 'masuk')
                            ->whereMonth('tanggal', $month->month)->whereYear('tanggal', $month->year)->sum('nominal');
                $keluar = TransaksiKas::where('buku_kas', $kas)->where('tipe_transaksi', 'keluar')
                            ->whereMonth('tanggal', $month->month)->whereYear('tanggal', $month->year)->sum('nominal');
                
                $chartData[$kas]['masuk'][] = $masuk;
                $chartData[$kas]['keluar'][] = $keluar;
            }
        }

        return view('keuangan.index', compact('transaksis', 'summary', 'chartData'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // Aturan ketat: Jika keluar, paksa ke kas umum
        if($data['tipe_transaksi'] == 'keluar') {
            $data['buku_kas'] = 'umum';
        }
        
        TransaksiKas::create($data);
        return redirect()->back();
    }

    // Simulasi Cetak Laporan
    public function cetakLaporan(Request $request)
    {
        // Fitur cetak PDF akan dibuat menggunakan library DOMPDF di tahap lanjut
        // Untuk saat ini, kita kembalikan ke halaman agar tidak error 404
        return redirect()->back()->with('pesan', 'Fitur cetak PDF sedang dikembangkan.');
    }
}