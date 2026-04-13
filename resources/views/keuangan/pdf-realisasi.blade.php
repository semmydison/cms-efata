<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }} - {{ $sumberKas }}</title>
    <style>
        /* CSS PROFESIONAL UNTUK AUDIT */
        @page { size: A4 landscape; margin: 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        
        /* HEADER LEVEL RESMI */
        .kop-surat { width: 100%; text-align: center; border-bottom: 2.5px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h1 { margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase; color: #000; }
        .kop-surat p { margin: 2px 0; font-size: 11px; }
        .no-laporan { text-align: right; font-size: 9px; margin-bottom: 10px; font-style: italic; }

        /* TABEL DATA STANDAR AKUNTANSI */
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        .table-data th { background-color: #1e293b; color: #ffffff; padding: 10px 5px; text-transform: uppercase; font-size: 9px; border: 1px solid #000; }
        .table-data td { border: 1px solid #000; padding: 8px 6px; vertical-align: middle; }
        
        /* ZEBRA & HIGHLIGHT */
        .table-data tbody tr:nth-child(even) { background-color: #f8fafc; }
        .bg-subtotal { background-color: #e2e8f0 !important; font-weight: bold; }
        .over-budget { color: #dc2626; font-weight: bold; } /* Merah untuk Over-Budget */
        
        /* ALIGNMENT */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .nowrap { white-space: nowrap; }

        /* SECTION RINGKASAN AKHIR */
        .summary-final { width: 40%; margin-left: auto; border: 2px solid #000; margin-top: 10px; background: #f1f5f9; }
        .summary-final td { padding: 8px 12px; font-size: 11px; }
        .surplus-row { border-top: 2px double #000; background: #cbd5e1; font-weight: bold; }

        /* CATATAN & FOOTER */
        .catatan-section { margin-top: 20px; border: 1px dashed #64748b; padding: 10px; width: 60%; }
        .catatan-section h4 { margin: 0 0 5px 0; font-size: 10px; text-decoration: underline; }
        .catatan-section ul { margin: 0; padding-left: 20px; color: #475569; }

        /* TANDA TANGAN */
        .table-ttd { width: 100%; margin-top: 40px; border: none; page-break-inside: avoid; }
        .table-ttd td { border: none; text-align: center; width: 50%; vertical-align: bottom; }
        .ttd-space { height: 70px; }

        .system-footer { position: fixed; bottom: -20px; left: 0; font-size: 8px; color: #94a3b8; width: 100%; }
    </style>
</head>
<body>

    <div class="system-footer">
        Dicetak secara otomatis oleh Sistem Informasi Efata TBN pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} | Halaman 1
    </div>

    <div class="kop-surat">
        <h1>Majelis Jemaat GMIT Efata TBN</h1>
        <p>Alamat: Batuputih, Timor Tengah Selatan, NTT</p>
        <p style="font-size: 14px; font-weight: bold; margin-top: 10px;">{{ strtoupper($judul) }}</p>
        <p>Periode: <strong>{{ $periode }}</strong> &nbsp; | &nbsp; Sumber Kas: <strong>{{ strtoupper($sumberKas) }}</strong></p>
    </div>

    <div class="no-laporan">No: 001/LAP-ANG/EFATA/{{ date('Y') }}</div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="36%">Kategori Pos Anggaran</th>
                <th width="15%">Pagu Anggaran (Rp)</th>
                <th width="15%">Realisasi (Rp)</th>
                <th width="15%">Selisih (+/-)</th>
                <th width="15%">Capaian (%)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalPaguPemasukan = 0; $totalRealPemasukan = 0; 
                $totalPaguPengeluaran = 0; $totalRealPengeluaran = 0; 
            @endphp

            @foreach(['Pemasukan', 'Pengeluaran'] as $jenis)
                <tr style="background-color: #cbd5e1;">
                    <td colspan="6" class="font-bold uppercase tracking-widest">Grup: {{ $jenis }}</td>
                </tr>
                
                @php $subPagu = 0; $subReal = 0; $no = 1; @endphp
                @foreach($laporan->where('jenis', $jenis) as $item)
                    @php 
                        $realisasi = $item->transaksi_kas_sum_nominal ?? 0;
                        $selisih = $item->target_tahunan - $realisasi;
                        $capaian = $item->target_tahunan > 0 ? ($realisasi / $item->target_tahunan) * 100 : 0;
                        
                        $subPagu += $item->target_tahunan;
                        $subReal += $realisasi;
                        
                        if($jenis == 'Pemasukan') { $totalPaguPemasukan += $item->target_tahunan; $totalRealPemasukan += $realisasi; }
                        else { $totalPaguPengeluaran += $item->target_tahunan; $totalRealPengeluaran += $realisasi; }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td class="text-right">{{ number_format($item->target_tahunan, 0, ',', '.') }}</td>
                        <td class="text-right @if($jenis == 'Pengeluaran' && $realisasi > $item->target_tahunan) over-budget @endif">
                            {{ number_format($realisasi, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            @if($selisih < 0)
                                ({{ number_format(abs($selisih), 0, ',', '.') }})
                            @else
                                {{ number_format($selisih, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-center font-bold">{{ number_format($capaian, 2) }}%</td>
                    </tr>
                @endforeach
                <tr class="bg-subtotal">
                    <td colspan="2" class="text-right">SUBTOTAL {{ strtoupper($jenis) }}</td>
                    <td class="text-right">{{ number_format($subPagu, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($subReal, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @php $subSelisih = $subPagu - $subReal; @endphp
                        @if($subSelisih < 0) ({{ number_format(abs($subSelisih), 0, ',', '.') }}) @else {{ number_format($subSelisih, 0, ',', '.') }} @endif
                    </td>
                    <td class="text-center">{{ number_format($subPagu > 0 ? ($subReal/$subPagu)*100 : 0, 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-final">
        <tr>
            <td>Total Anggaran (Netto)</td>
            <td class="text-right font-bold">Rp {{ number_format($totalPaguPemasukan - $totalPaguPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Realisasi (Netto)</td>
            <td class="text-right font-bold">Rp {{ number_format($totalRealPemasukan - $totalRealPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr class="surplus-row">
            <td>SURPLUS / (DEFISIT) BERJALAN</td>
            <td class="text-right" style="font-size: 13px;">
                @php $finalResult = $totalRealPemasukan - $totalRealPengeluaran; @endphp
                @if($finalResult < 0)
                    (Rp {{ number_format(abs($finalResult), 0, ',', '.') }})
                @else
                    Rp {{ number_format($finalResult, 0, ',', '.') }}
                @endif
            </td>
        </tr>
    </table>

    <div class="catatan-section">
        <h4>Catatan Audit/Laporan:</h4>
        <ul>
            <li>Anggaran Pengeluaran yang melebihi pagu ditandai dengan teks <span style="color:red; font-weight:bold;">Merah</span>.</li>
            <li>Realisasi Pemasukan mencapai {{ number_format($totalPaguPemasukan > 0 ? ($totalRealPemasukan/$totalPaguPemasukan)*100 : 0, 2) }}% dari target tahunan.</li>
            <li>Saldo surplus berjalan akan dialokasikan sesuai dengan keputusan Majelis Jemaat.</li>
        </ul>
    </div>

    <table class="table-ttd">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Ketua Majelis Jemaat</strong>
                <div class="ttd-space"></div>
                <p class="font-bold underline">( Pdt. ................................. )</p>
                <p>NIP/NIDN. ....................</p>
            </td>
            <td>
                Batuputih, {{ $tanggal_cetak }}<br>
                Disusun oleh,<br>
                <strong>Bendahara Jemaat</strong>
                <div class="ttd-space"></div>
                <p class="font-bold underline">( ........................................ )</p>
                <p>NIK. ....................</p>
            </td>
        </tr>
    </table>

</body>
</html>