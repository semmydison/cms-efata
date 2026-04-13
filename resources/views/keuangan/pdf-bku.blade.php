<!DOCTYPE html>
<html>
<head>
    <title>{{ $judul }}</title>
<style>
    @page { size: A4 landscape; margin: 1cm; }
    body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10px; margin: 0; }
    .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 12px; }
    .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
    .header h2 { margin: 5px 0; font-size: 14px; }
    .summary-box { width: 100%; border-collapse: collapse; margin-bottom: 12px; background: #f8f9fa; border: 1px solid #000; }
    .summary-box td { padding: 6px 10px; border: 1px solid #000; font-weight: bold; }
    .main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .main-table th, .main-table td { border: 1px solid #000; padding: 5px; vertical-align: top; }
    .main-table th { background: #e0e0e0; text-transform: uppercase; font-size: 9px; }
    .main-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
    .text-center { text-align: center; } .text-right { text-align: right; } .nowrap { white-space: nowrap; }
    .ttd-table { width: 100%; margin-top: 30px; border: none; page-break-inside: avoid; }
    .ttd-table td { text-align: center; border: none; width: 50%; }
</style>
</head>
<body>
    <div class="kop">
        <h1>Majelis Jemaat GMIT Efata TBN</h1>
        <h2>{{ $judul }}</h2>
        <p><strong>Sumber Kas: KAS {{ strtoupper($sumberKas) }}</strong> | Periode: {{ $periode }}</p>
    </div>

       <table class="table-data">
        <thead>
            <tr>
                <th width="3%">No</th><th width="7%">Tanggal</th><th width="9%">No Bukti</th>
                <th width="18%">Uraian</th><th width="8%">Sumber Kas</th><th width="12%">Kategori</th>
                <th width="11%">Pemasukan (Rp)</th><th width="11%">Pengeluaran (Rp)</th>
                <th width="12%">Saldo (Rp)</th><th width="9%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" class="text-right font-bold">SALDO AWAL MEMINDAH</td>
                <td class="text-right font-bold nowrap">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            @forelse($laporan as $row)
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td class="text-center nowrap">{{ $row['tanggal'] }}</td>
                    <td class="text-center nowrap">{{ $row['no_bukti'] }}</td>
                    <td class="wrap-text">{{ $row['uraian'] }}</td>
                    <td class="text-center">{{ $row['sumber_kas'] }}</td>
                    <td>{{ $row['kategori'] }}</td>
                    
                    <td class="text-right nowrap">
                        {{ $row['pemasukan'] > 0 ? 'Rp ' . number_format($row['pemasukan'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right nowrap">
                        {{ $row['pengeluaran'] > 0 ? 'Rp ' . number_format($row['pengeluaran'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right font-bold nowrap">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                    <td class="wrap-text text-center">{{ $row['keterangan'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px;">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-box">
    <tr>
        <td width="35%">
            <div><span class="summary-label">Saldo Awal</span> <span class="summary-value">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span></div>
            <div><span class="summary-label">Total Pemasukan</span> <span class="summary-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span></div>
            <div><span class="summary-label">Total Pengeluaran</span> <span class="summary-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span></div>
        </td>
        <td width="65%" class="saldo-akhir-box text-right">
            SALDO AKHIR PERIODE: &nbsp; Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
        </td>
    </tr>
    </table>

    <table class="ttd">
        <tr>
            <td>Mengetahui,<br><b>Ketua Majelis Jemaat</b><br><br><br><br>(........................................)</td>
            <td>Batuputih, {{ $tanggal_cetak }}<br><b>Bendahara Jemaat</b><br><br><br><br>(........................................)</td>
        </tr>
    </table>
</body>
</html>