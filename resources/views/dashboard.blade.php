@extends('layouts.app')

@section('title')
    Dashboard <span class="text-gray-400 font-normal mx-2">></span> Ringkasan
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Ringkasan Sistem</h2>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex items-center border-l-4 border-blue-500 border dark:border-gray-700">
        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 mr-4">
            <i class="fa-solid fa-users text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Jemaat Aktif</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">1.250 <span class="text-xs text-green-500 font-normal ml-1">+5 bln ini</span></p>
        </div>
    </div>
    
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex items-center border-l-4 border-green-500 border dark:border-gray-700">
        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 mr-4">
            <i class="fa-solid fa-rupiah-sign text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Saldo Kas Gereja</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">45,5 Jt</p>
        </div>
    </div>
    
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex items-center border-l-4 border-yellow-500 border dark:border-gray-700">
        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 mr-4">
            <i class="fa-solid fa-church text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Ibadah Terdekat</p>
            <p class="text-sm font-bold text-gray-800 dark:text-white mt-1">Minggu Raya</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">12 Apr, 08:00 WITA</p>
        </div>
    </div>
    
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex items-center border-l-4 border-purple-500 border dark:border-gray-700">
        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 mr-4">
            <i class="fa-solid fa-file-powerpoint text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Status Liturgi</p>
            <p class="text-sm font-bold text-green-600 mt-1"><i class="fa-solid fa-check-circle mr-1"></i> Sudah Diunggah</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4"><i class="fa-solid fa-chart-line text-green-500 mr-2"></i>Tren Pemasukan vs Pengeluaran</h3>
        <div class="flex-1 min-h-[250px]">
            <canvas id="keuanganDashboardChart"></canvas>
        </div>
    </div>

    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-users text-blue-500 mr-2"></i>Kehadiran Ibadah Minggu</h3>
            <span class="text-xs text-gray-500 dark:text-gray-400">4 Minggu Terakhir</span>
        </div>
        <div class="flex-1 min-h-[250px]">
            <canvas id="kehadiranChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:col-span-1">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4"><i class="fa-solid fa-cake-candles text-pink-500 mr-2"></i>Ulang Tahun Bulan Ini</h3>
        <div class="space-y-4 max-h-64 overflow-y-auto pr-2">
            <div class="flex items-start border-b dark:border-gray-700 pb-3">
                <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 flex items-center justify-center font-bold mr-3">15</div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-800 dark:text-white">Bpk. Yohanes</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Wilayah 1 - Rayon A</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Usia: 45 Tahun</p>
                </div>
            </div>
            <div class="flex items-start border-b dark:border-gray-700 pb-3">
                <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 flex items-center justify-center font-bold mr-3">22</div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-800 dark:text-white">Ibu Maria</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Wilayah 2 - Rayon C</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Usia: 38 Tahun</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logika Render Chart.js
    document.addEventListener("DOMContentLoaded", function() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        const gridColor = isDark ? '#374151' : '#f3f4f6';

        // 1. CHART KEUANGAN (LINE CHART)
        const ctxKeuangan = document.getElementById('keuanganDashboardChart').getContext('2d');
        
        let gradPemasukan = ctxKeuangan.createLinearGradient(0, 0, 0, 300);
        gradPemasukan.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); 
        gradPemasukan.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        let gradPengeluaran = ctxKeuangan.createLinearGradient(0, 0, 0, 300);
        gradPengeluaran.addColorStop(0, 'rgba(239, 68, 68, 0.4)'); 
        gradPengeluaran.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

        new Chart(ctxKeuangan, {
            type: 'line',
            data: {
                labels: ['Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [12.5, 18.2, 14.1, 13.8, 15.0, 15.2],
                        borderColor: '#10b981',
                        backgroundColor: gradPemasukan,
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: [8.2, 10.5, 6.4, 5.1, 7.2, 3.5],
                        borderColor: '#ef4444',
                        backgroundColor: gradPengeluaran,
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, font: {family: "'Inter', sans-serif"} } },
                    tooltip: { mode: 'index', intersect: false, callbacks: { label: function(c) { return c.dataset.label + ': Rp ' + c.parsed.y + ' Jt'; } } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor } },
                    y: { grid: { color: gridColor }, ticks: { color: textColor, callback: function(val) { return val + ' Jt'; } } }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });

        // 2. CHART KEHADIRAN JEMAAT (BAR CHART TUMPUMK/GROUPED)
        const ctxKehadiran = document.getElementById('kehadiranChart').getContext('2d');
        new Chart(ctxKehadiran, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [
                    {
                        label: 'Laki-laki',
                        data: [420, 450, 415, 460],
                        backgroundColor: '#3b82f6', // Biru
                        borderRadius: 4
                    },
                    {
                        label: 'Perempuan',
                        data: [480, 510, 490, 525],
                        backgroundColor: '#ec4899', // Pink
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, font: {family: "'Inter', sans-serif"} } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor } },
                    y: { grid: { color: gridColor }, ticks: { color: textColor }, beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush