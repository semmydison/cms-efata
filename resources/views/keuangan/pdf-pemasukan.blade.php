<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }} - {{ $sumberKas }}</title>
    <style>
        /* PENGATURAN KERTAS & TYPOGRAPHY */
        @page { size: A4 landscape; margin: 1cm 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #000; margin: 0; }

        /* HEADER LAPORAN (KOP) */
        .kop-surat { width: 100%; text-align: center; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .kop-surat h1 { margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-surat h2 { margin: 4px 0; font-size: 14px; font-weight: bold; }
        .kop-surat p { margin: 2px 0; font-size: 10px; font-weight: normal; }

        /* BOX RINGKASAN (SUMMARY) */
        .summary-box { width: 100%; margin-bottom: 15px; border: 1px solid #000; border-collapse: collapse; background-color: #f8f9fa; }
        .summary-box td { padding: 10px; font-size: 12px; text-align: center; font-weight: bold; text-transform: uppercase; }

        /* TABEL DATA UTAMA */
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        .table-data th { background-color: #e0e0e0; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9px; }
        
        /* EFEK ZEBRA */
        .table-data tbody tr:nth-child(even) { background-color: #f9f9f9; }

        /* UTILITY */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .nowrap { white-space: nowrap; }
        
        /* FORMAT RUPIAH */
        .rp-symbol { float: left; }
        .rp-amount { float: right; }

        /* TANDA TANGAN */
        .table-ttd { width: 100%; margin-top: 30px; border-collapse: collapse; border: none; page-break-inside: avoid; }
        .table-ttd td { border: none; padding: 0; text-align: center; width: 50%; }
        .ttd-space { height: 60px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h1>Majelis Jemaat GMIT Efata TBN</h1>
        <h2>{{ $judul }}</h2>
        <p><strong>Sumber Kas: KAS {{ strtoupper($sumberKas) }}</strong></p>
        <p>Periode: <strong>{{ $periode }}</strong> &nbsp;|&nbsp; Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="12%">No Bukti</th>
                <th width="25%">Uraian Penerimaan</th>
                <th width="12%">Keterangan</th>
                <th width="10%">Sumber Kas</th>
                <th width="15%">Kategori Anggaran</th>
                <th width="12%">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $row)
            <tr>
                <td class="text-center">{{ $row['no'] }}</td>
                <td class="text-center nowrap">{{ $row['tanggal'] }}</td>
                <td class="text-center nowrap">{{ $row['no_bukti'] }}</td>
                <td>{{ $row['uraian'] }}</td>
                <td>{{ $row['keterangan'] }}</td>
                <td class="text-center">{{ $row['sumber_kas'] }}</td>
                <td>{{ $row['kategori'] }}</td>
                <td class="nowrap font-bold">
                    <span class="rp-symbol">Rp</span> 
                    <span class="rp-amount">{{ number_format($row['pemasukan'], 0, ',', '.') }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">TIDAK ADA DATA PEMASUKAN PADA PERIODE INI.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #e0e0e0;">
                <td colspan="7" class="text-right font-bold uppercase">Total Pemasukan Keseluruhan</td>
                <td class="text-right font-bold nowrap">
                    <span class="rp-symbol">Rp</span> 
                    <span class="rp-amount">{{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                </td>
            </tr>
        </tfoot>
    </table>

    
    <table class="table-ttd">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua Majelis Jemaat</p>
                <div class="ttd-space"></div>
                <p class="font-bold underline uppercase">(..................................................)</p>
            </td>
            <td>
                <p>Batuputih, {{ $tanggal_cetak }}</p>
                <p class="font-bold">Bendahara Jemaat</p>
                <div class="ttd-space"></div>
                <p class="font-bold underline uppercase">(..................................................)</p>
            </td>
        </tr>
    </table>

</body>
</html>