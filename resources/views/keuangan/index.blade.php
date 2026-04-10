@extends('layouts.app')

@section('title')
    Manajemen Keuangan <span class="text-gray-400 font-normal mx-2">></span> Transaksi Kas
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Buku Kas Utama</h2>
    <div class="flex space-x-3 w-full sm:w-auto">
        <button onclick="toggleModal('modalCetak')" class="flex-1 sm:flex-none bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-file-pdf text-red-500 mr-2"></i> Cetak Laporan
        </button>
        <button onclick="toggleModal('modalTransaksi')" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-plus mr-2"></i> Input Transaksi
        </button>
    </div>
</div>

<div class="flex border-b border-gray-300 dark:border-gray-700 mb-6 overflow-x-auto hide-scrollbar">
    <button onclick="switchTab('umum', this)" class="tab-btn px-6 py-3 font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 whitespace-nowrap transition">Kas Umum / Sentral</button>
    <button onclick="switchTab('lelang', this)" class="tab-btn px-6 py-3 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent whitespace-nowrap transition">Kas Lelang & Pembangunan</button>
    <button onclick="switchTab('kategorial', this)" class="tab-btn px-6 py-3 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent whitespace-nowrap transition">Kas Pelayanan (Kategorial)</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-xl shadow-sm p-5 text-white flex items-center justify-between border dark:border-gray-700">
        <div>
            <p class="text-sm text-blue-200 font-medium mb-1">Saldo Kas Saat Ini</p>
            <p id="teksSaldo" class="text-3xl font-bold">Rp 0</p>
        </div>
        <div class="p-4 rounded-full bg-white bg-opacity-20 text-white"><i class="fa-solid fa-vault text-2xl"></i></div>
    </div>
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 flex items-center justify-between transition-colors">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">Total Pemasukan</p>
            <p id="teksMasuk" class="text-2xl font-bold text-green-600 dark:text-green-400">+ Rp 0</p>
        </div>
        <div class="p-4 rounded-full bg-green-50 dark:bg-green-900/30 text-green-600"><i class="fa-solid fa-arrow-trend-up text-xl"></i></div>
    </div>
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 flex items-center justify-between transition-colors">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">Total Pengeluaran</p>
            <p id="teksKeluar" class="text-2xl font-bold text-red-600 dark:text-red-400">- Rp 0</p>
        </div>
        <div class="p-4 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600"><i class="fa-solid fa-arrow-trend-down text-xl"></i></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center">
            <p id="judulGrafik" class="text-sm text-gray-600 dark:text-gray-300 font-bold"><i class="fa-solid fa-chart-area text-blue-500 mr-2"></i>Tren Saldo (Kas Umum)</p>
        </div>
        <div class="p-4 h-72">
            <canvas id="keuanganChart"></canvas>
        </div>
    </div>

    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors flex flex-col">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <p id="judulTabel" class="text-sm text-gray-600 dark:text-gray-300 font-bold truncate">Transaksi Terbaru (Kas Umum)</p>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-[10px] text-gray-700 dark:text-gray-400 uppercase bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-4 py-3">Tanggal & Keterangan</th>
                        <th scope="col" class="px-4 py-3 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="tabelPreviewBody">
                    @foreach($transaksis as $trx)
                    <tr class="preview-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" data-kas="{{ $trx->buku_kas }}">
                        <td class="px-4 py-3">
                            <div class="font-bold text-gray-800 dark:text-white text-xs">{{ $trx->keterangan }}</div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ date('d M Y', strtotime($trx->tanggal)) }}</div>
                        </td>
                        <td class="px-4 py-3 text-right font-bold {{ $trx->tipe_transaksi == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $trx->tipe_transaksi == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t border-gray-100 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-800">
            <button onclick="toggleModal('modalSemuaData')" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Lihat Semua Data <i class="fa-solid fa-arrow-right ml-1"></i></button>
        </div>
    </div>
</div>

<div id="modalSemuaData" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4">
    <div class="bg-white dark:bg-gray-800 w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h3 id="judulModalSemua" class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-list text-blue-600 mr-2"></i> Semua Riwayat (Kas Umum)</h3>
            <button onclick="toggleModal('modalSemuaData')" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        
        <div class="p-4 border-b dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-wrap gap-4 justify-between items-center">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-solid fa-magnifying-glass text-gray-400"></i></div>
                <input type="text" id="cariSemua" onkeyup="filterSemuaTabel()" class="bg-gray-50 dark:bg-gray-900 w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 outline-none dark:text-white" placeholder="Cari transaksi (ID / Ket)...">
            </div>
            
            @php
                $months = [];
                foreach($transaksis as $t) {
                    $lbl = \Carbon\Carbon::parse($t->tanggal)->translatedFormat('F Y');
                    $val = \Carbon\Carbon::parse($t->tanggal)->format('Y-m');
                    $months[$val] = $lbl;
                }
            @endphp
            <select id="filterBulan" onchange="filterSemuaTabel()" class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm outline-none dark:text-white w-full sm:w-auto">
                <option value="all">Semua Bulan</option>
                @foreach($months as $val => $lbl)
                    <option value="{{ $val }}">{{ $lbl }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-y-auto flex-1">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-700 sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID TRX</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Keterangan</th>
                        <th scope="col" class="px-6 py-3 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="tabelSemuaBody">
                    @foreach($transaksis as $trx)
                    <tr class="semua-row hover:bg-gray-50 dark:hover:bg-gray-700/50" 
                        data-kas="{{ $trx->buku_kas }}" 
                        data-bulan="{{ date('Y-m', strtotime($trx->tanggal)) }}">
                        
                        <td class="px-6 py-4 font-mono text-xs font-bold text-gray-500 id-trx">TRX-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-medium dark:text-gray-300">{{ date('d M Y', strtotime($trx->tanggal)) }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-white ket-trx">{{ $trx->keterangan }}</td>
                        <td class="px-6 py-4 text-right font-bold {{ $trx->tipe_transaksi == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $trx->tipe_transaksi == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-right">
            <button onclick="toggleModal('modalSemuaData')" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">Tutup</button>
        </div>
    </div>
</div>

<div id="modalCetak" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-print text-red-500 mr-2"></i> Cetak Laporan Keuangan</h3>
            <button onclick="toggleModal('modalCetak')" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ url('/keuangan/cetak') }}" method="GET">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Buku Kas</label>
                    <select name="buku_kas" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition">
                        <option value="semua">Semua Buku Kas (Gabungan)</option>
                        <option value="umum">Buku Kas Umum / Sentral</option>
                        <option value="lelang">Buku Kas Lelang & Pembangunan</option>
                        <option value="kategorial">Buku Kas Pelayanan Kategorial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Jenis Transaksi</label>
                    <select name="jenis" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition">
                        <option value="semua">Semua Transaksi (Pemasukan & Pengeluaran)</option>
                        <option value="masuk">Hanya Pemasukan</option>
                        <option value="keluar">Hanya Pengeluaran</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 outline-none text-sm dark:text-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 outline-none text-sm dark:text-white transition">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-right flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalCetak')" class="px-5 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-md hover:bg-blue-700 flex items-center transition">
                    <i class="fa-solid fa-download mr-2"></i> Download Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalTransaksi" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl flex flex-col max-h-[95vh] overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-file-invoice-dollar text-blue-600 mr-2"></i> Input Transaksi Baru</h3>
            <button onclick="toggleModal('modalTransaksi')" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <div class="p-6 overflow-y-auto">
            <form action="{{ url('/keuangan/store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Tipe Transaksi *</label>
                    <div class="flex space-x-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="tipe_transaksi" value="masuk" id="radioMasuk" class="peer hidden" checked onchange="aturFormTipe()">
                            <div class="py-3 px-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 text-center transition-all bg-white dark:bg-gray-800">
                                <i class="fa-solid fa-arrow-trend-up text-green-600 text-xl mb-1 block"></i>
                                <span class="text-gray-700 dark:text-gray-300 font-bold text-sm">Pemasukan</span>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="tipe_transaksi" value="keluar" id="radioKeluar" class="peer hidden" onchange="aturFormTipe()">
                            <div class="py-3 px-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 text-center transition-all bg-white dark:bg-gray-800">
                                <i class="fa-solid fa-arrow-trend-down text-red-600 text-xl mb-1 block"></i>
                                <span class="text-gray-700 dark:text-gray-300 font-bold text-sm">Pengeluaran</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Pilih Buku Kas *</label>
                        <select id="selectBukuKas" name="buku_kas" required class="w-full bg-blue-50 dark:bg-gray-900 border border-blue-100 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white font-semibold transition disabled:opacity-50">
                            <option value="umum">Buku Kas Umum / Sentral</option>
                            <option value="lelang">Buku Kas Lelang & Pembangunan</option>
                            <option value="kategorial">Buku Kas Pelayanan Kategorial</option>
                        </select>
                        <input type="hidden" name="buku_kas_hidden" id="hiddenBukuKas" disabled value="umum">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Tanggal Transaksi *</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Kategori Kegiatan / Sumber *</label>
                    <select id="kategori_masuk" name="kategori_masuk" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none text-sm dark:text-white transition">
                        <option value="">-- Pilih Kategori Pemasukan --</option>
                        <option value="ibadah_kebaktian">Ibadah Kebaktian (Minggu)</option>
                        <option value="ibadah_rt">Ibadah Rumah Tangga</option>
                        <option value="lelang">Persembahan Lelang (Benda/Natura)</option>
                        <option value="lainnya">Pemasukan Lainnya...</option>
                    </select>

                    <select id="kategori_keluar" name="kategori_keluar" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 outline-none text-sm dark:text-white transition hidden">
                        <option value="">-- Pilih Kategori Pengeluaran --</option>
                        <option value="operasional">Biaya Operasional (Listrik, Air)</option>
                        <option value="konsumsi">Konsumsi Ibadah</option>
                        <option value="pembangunan">Biaya Material Pembangunan</option>
                        <option value="lainnya">Pengeluaran Lainnya...</option>
                    </select>
                    <input type="hidden" name="kategori" id="final_kategori">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nominal (Rupiah) *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><span class="text-gray-500 font-bold">Rp</span></div>
                        <input type="number" name="nominal" required class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl text-lg font-bold tracking-wider focus:ring-2 focus:ring-blue-500 outline-none dark:text-white transition" placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Keterangan Detail *</label>
                    <textarea name="keterangan" rows="3" required class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition" placeholder="Contoh: Persembahan Syukur..."></textarea>
                </div>
                
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                    <button type="button" onclick="toggleModal('modalTransaksi')" class="px-5 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit" onclick="siapkanKategori()" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-md hover:bg-blue-700 flex items-center transition">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Menyimpan JSON dari Backend
    const chartDataBackend = @json($chartData);
    const summaryData = @json($summary);
    
    let kasAktif = 'umum'; // Default kas
    let chartKeuangan;     // Variabel penampung chart

    // Inisialisasi Chart saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('keuanganChart').getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        const gridColor = isDark ? '#374151' : '#f3f4f6';

        let gradientMasuk = ctx.createLinearGradient(0, 0, 0, 400);
        gradientMasuk.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); 
        gradientMasuk.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        let gradientKeluar = ctx.createLinearGradient(0, 0, 0, 400);
        gradientKeluar.addColorStop(0, 'rgba(239, 68, 68, 0.4)'); 
        gradientKeluar.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

        chartKeuangan = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartDataBackend.labels,
                datasets: [
                    {
                        label: 'Total Pemasukan',
                        data: chartDataBackend[kasAktif].masuk,
                        borderColor: '#10b981', 
                        backgroundColor: gradientMasuk,
                        borderWidth: 3, pointBackgroundColor: '#ffffff', pointBorderColor: '#10b981',
                        pointBorderWidth: 2, pointRadius: 4, tension: 0.4, fill: true    
                    },
                    {
                        label: 'Total Pengeluaran',
                        data: chartDataBackend[kasAktif].keluar,
                        borderColor: '#ef4444', 
                        backgroundColor: gradientKeluar,
                        borderWidth: 3, pointBackgroundColor: '#ffffff', pointBorderColor: '#ef4444',
                        pointBorderWidth: 2, pointRadius: 4, tension: 0.4, fill: true    
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { color: textColor, font: {family: "'Inter', sans-serif"} } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false, drawBorder: false }, ticks: { color: textColor } },
                    y: { grid: { color: gridColor, drawBorder: false }, ticks: { color: textColor, callback: function(val) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(val); } } }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });

        // Render awal tampilan
        refreshTampilanTab();
    });

    // Logika Pengaturan Form Kategori & Paksaan Kas Umum
    function aturFormTipe() {
        const radioMasuk = document.getElementById('radioMasuk');
        const selectKas = document.getElementById('selectBukuKas');
        const hiddenKas = document.getElementById('hiddenBukuKas');
        
        if (radioMasuk.checked) {
            document.getElementById('kategori_keluar').classList.add('hidden');
            document.getElementById('kategori_masuk').classList.remove('hidden');
            
            // Buka kunci Buku Kas
            selectKas.disabled = false;
            hiddenKas.disabled = true; // Matikan input hidden
        } else {
            document.getElementById('kategori_masuk').classList.add('hidden');
            document.getElementById('kategori_keluar').classList.remove('hidden');
            
            // Kunci Buku Kas ke UMUM sesuai permintaan khusus!
            selectKas.value = 'umum';
            selectKas.disabled = true;
            hiddenKas.disabled = false; // Aktifkan hidden agar value "umum" tetap terkirim saat disubmit
        }
    }

    function siapkanKategori() {
        const finalKat = document.getElementById('final_kategori');
        finalKat.value = document.getElementById('radioMasuk').checked ? 
                         document.getElementById('kategori_masuk').value : 
                         document.getElementById('kategori_keluar').value;
    }

    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

    // --- LOGIKA FILTER TAB (KAS) & PREVIEW TERBARU ---
    function switchTab(jenisKas, elemen) {
        kasAktif = jenisKas;
        
        // Ubah style Tombol Tab
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.className = 'tab-btn px-6 py-3 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent whitespace-nowrap transition';
        });
        elemen.className = 'tab-btn px-6 py-3 font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 whitespace-nowrap transition';

        // Ganti Judul
        let namaKas = '';
        if (jenisKas === 'umum') namaKas = 'Kas Umum / Sentral';
        else if (jenisKas === 'lelang') namaKas = 'Kas Lelang & Pembangunan';
        else if (jenisKas === 'kategorial') namaKas = 'Kas Pelayanan Kategorial';
        
        document.getElementById('judulGrafik').innerHTML = `<i class="fa-solid fa-chart-area text-blue-500 mr-2"></i>Tren Saldo (${namaKas})`;
        document.getElementById('judulTabel').innerText = `Transaksi Terbaru (${namaKas})`;
        document.getElementById('judulModalSemua').innerHTML = `<i class="fa-solid fa-list text-blue-600 mr-2"></i> Semua Riwayat (${namaKas})`;

        refreshTampilanTab();
    }

    function refreshTampilanTab() {
        // 1. Update Kartu Angka Saldo (Format Rupiah)
        const formatRp = (angka) => new Intl.NumberFormat('id-ID').format(angka);
        document.getElementById('teksSaldo').innerText = 'Rp ' + formatRp(summaryData[kasAktif].saldo);
        document.getElementById('teksMasuk').innerText = '+ Rp ' + formatRp(summaryData[kasAktif].masuk);
        document.getElementById('teksKeluar').innerText = '- Rp ' + formatRp(summaryData[kasAktif].keluar);

        // 2. Update Grafik Line
        if(chartKeuangan) {
            chartKeuangan.data.datasets[0].data = chartDataBackend[kasAktif].masuk;
            chartKeuangan.data.datasets[1].data = chartDataBackend[kasAktif].keluar;
            chartKeuangan.update();
        }

        // 3. Update Tabel Preview (Hanya tampilkan kas terpilih, hitung manual sampai max 5)
        const rowsPreview = document.querySelectorAll('.preview-row');
        let count = 0;
        rowsPreview.forEach(row => {
            if (row.getAttribute('data-kas') === kasAktif && count < 5) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        // Panggil filter di Modal Semua Data (reset pencarian)
        document.getElementById('cariSemua').value = '';
        document.getElementById('filterBulan').value = 'all';
        filterSemuaTabel();
    }

    // --- LOGIKA FILTER MODAL SEMUA TRANSAKSI ---
    function filterSemuaTabel() {
        const cari = document.getElementById('cariSemua').value.toLowerCase();
        const bulan = document.getElementById('filterBulan').value;
        
        const rows = document.querySelectorAll('.semua-row');
        rows.forEach(row => {
            const rowKas = row.getAttribute('data-kas');
            const rowBulan = row.getAttribute('data-bulan');
            const teksID = row.querySelector('.id-trx').innerText.toLowerCase();
            const teksKet = row.querySelector('.ket-trx').innerText.toLowerCase();

            // Aturan tampil: Kas harus sesuai tab, bulan sesuai, ket/id sesuai
            if (rowKas === kasAktif && 
               (bulan === 'all' || rowBulan === bulan) &&
               (teksID.includes(cari) || teksKet.includes(cari))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush