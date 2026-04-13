<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        /* PENGATURAN KERTAS & TYPOGRAPHY */
        @page { size: A4 landscape; margin: 1cm 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #000; margin: 0; }

        /* HEADER LAPORAN */
        .kop-surat { width: 100%; text-align: center; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .kop-surat h1 { margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-surat h2 { margin: 4px 0; font-size: 14px; font-weight: bold; }
        .kop-surat p { margin: 2px 0; font-size: 10px; font-weight: normal; }

        /* RINGKASAN EKSEKUTIF (SUMMARY BOX) */
        .summary-box { width: 100%; margin-bottom: 15px; border: 1px solid #000; border-collapse: collapse; background-color: #f8f9fa; }
        .summary-box td { padding: 6px 12px; font-size: 11px; vertical-align: middle; border: 1px solid #000; }
        .summary-label { font-weight: bold; width: 120px; display: inline-block; }
        .summary-value { font-weight: bold; float: right; }
        .saldo-akhir-box { text-align: right; font-size: 14px; font-weight: bold; background-color: #e0e0e0; }

        /* TABEL UTAMA */
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: auto; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .table-data th { background-color: #e0e0e0; font-weight: bold; text-align: center; text-transform: uppercase; border-bottom: 2px solid #000; }
        
        /* EFEK ZEBRA AGAR MATA AUDITOR TIDAK LELAH */
        .table-data tbody tr:nth-child(even) { background-color: #f5f5f5; }

        /* UTILITY CLASSES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .nowrap { white-space: nowrap; }
        .wrap-text { word-wrap: break-word; }
        
        /* PEMISAHAN RUPIAH DAN ANGKA (AKUNTANSI) */
        .rp-symbol { float: left; text-align: left; }
        .rp-amount { float: right; text-align: right; }

        /* TANDA TANGAN */
        .table-ttd { width: 100%; margin-top: 30px; border-collapse: collapse; border: none; page-break-inside: avoid; }
        .table-ttd td { border: none; padding: 0; text-align: center; width: 50%; }
        .ttd-space { height: 60px; }

        /* FOOTER NOMOR HALAMAN & TANGGAL CETAK */
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; height: 20px; font-size: 8px; font-style: italic; color: #555; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 760; $y = 570; $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "italic"); $size = 8; $color = array(0.3, 0.3, 0.3);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>

    <div class="footer">
        Dicetak dari Sistem Keuangan Jemaat pada {{ date('d/m/Y H:i') }} WITA
    </div>

    <div class="kop-surat">
        <h1>Majelis Jemaat GMIT Efata TBN</h1>
        <h2>{{ $judul }}</h2>
        <p>Periode: <strong>{{ $periode }}</strong> &nbsp;|&nbsp; Cakupan Kas: <strong>{{ $kasPilih === 'Semua' ? 'Kas Rutin & Cadangan' : 'Kas '.$kasPilih }}</strong></p>
    </div>

    <table class="summary-box">
        <tr>
            <td width="35%">
                <div><span class="summary-label">Saldo Awal</span> <span class="summary-value">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span></div>
                <div><span class="summary-label">Total Pemasukan</span> <span class="summary-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span></div>
                <div><span class="summary-label">Total Pengeluaran</span> <span class="summary-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span></div>
            </td>
            <td width="65%" class="saldo-akhir-box">
                SALDO AKHIR PERIODE: &nbsp; Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="7%">Tanggal</th>
                <th width="9%">No Bukti</th>
                <th width="18%">Uraian Keterangan</th>
                <th width="8%">Sumber Kas</th>
                <th width="10%">Kategori Anggaran</th>
                
                @if($jenisPilih !== 'pengeluaran')
                <th width="12%">Pemasukan</th>
                @endif
                
                @if($jenisPilih !== 'pemasukan')
                <th width="12%">Pengeluaran</th>
                @endif
                
                <th width="12%">Saldo Berjalan</th>
                <th width="9%">Ket.</th>
            </tr>
        </thead>
        <tbody>
            @if(count($laporan) > 0)
                <tr style="background-color: #fdfdfd; font-style: italic;">
                    <td colspan="6" class="text-right font-bold">SALDO AWAL MEMINDAH</td>
                    
                    @if($jenisPilih !== 'pengeluaran')
                    <td></td>
                    @endif
                    
                    @if($jenisPilih !== 'pemasukan')
                    <td></td>
                    @endif

                    <td class="text-right font-bold nowrap">
                        <span class="rp-symbol">Rp</span> <span class="rp-amount">{{ number_format($saldoAwal, 0, ',', '.') }}</span>
                    </td>
                    <td></td>
                </tr>
            @endif

            @forelse($laporan as $row)
            <tr>
                <td class="text-center">{{ $row['no'] }}</td>
                <td class="text-center nowrap">{{ $row['tanggal'] }}</td>
                <td class="text-center nowrap">{{ $row['no_bukti'] }}</td>
                <td class="wrap-text">{{ $row['uraian'] }}</td>
                <td class="text-center">{{ $row['sumber_kas'] }}</td>
                <td>{{ $row['kategori'] }}</td>
                
                @if($jenisPilih !== 'pengeluaran')
                <td class="nowrap">
                    @if($row['pemasukan'] > 0)
                        <span class="rp-symbol">Rp</span> <span class="rp-amount">{{ number_format($row['pemasukan'], 0, ',', '.') }}</span>
                    @else
                        <span class="rp-amount">-</span>
                    @endif
                </td>
                @endif
                
                @if($jenisPilih !== 'pemasukan')
                <td class="nowrap">
                    @if($row['pengeluaran'] > 0)
                        <span class="rp-symbol">Rp</span> <span class="rp-amount">{{ number_format($row['pengeluaran'], 0, ',', '.') }}</span>
                    @else
                        <span class="rp-amount">-</span>
                    @endif
                </td>
                @endif

                <td class="nowrap font-bold">
                    <span class="rp-symbol">Rp</span> <span class="rp-amount">{{ number_format($row['saldo'], 0, ',', '.') }}</span>
                </td>
                <td class="wrap-text text-center">{{ $row['keterangan'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 20px 0;">TIDAK ADA DATA TRANSAKSI PADA PERIODE INI.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="table-ttd">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua Majelis Jemaat</p>
                <div class="ttd-space"></div>
                <p class="font-bold underline text-uppercase">(..................................................)</p>
            </td>
            <td>
                <p>Batuputih, {{ $tanggal_cetak }}</p>
                <p class="font-bold">Disusun Oleh, <br>Bendahara Jemaat</p>
                <div class="ttd-space"></div>
                <p class="font-bold underline text-uppercase">(..................................................)</p>
            </td>
        </tr>
    </table>

</body>
</html>