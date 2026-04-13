@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="min-h-screen bg-[#F5F7F9] font-sans text-gray-800 pb-12 rounded-2xl">
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start mb-2">
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Saldo Gabungan</p>
                            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($saldoGabungan, 0, ',', '.') }}</h2>
                            <div class="flex items-center mt-2 space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $persenRutin >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fa-solid {{ $persenRutin >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                                    {{ round($persenRutin, 1) }}%
                                </span>
                                <span class="text-xs text-gray-500 font-medium">vs bulan lalu (Kas Rutin)</span>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0 flex space-x-1 bg-gray-100 p-1 rounded-lg">
                            <button class="px-3 py-1.5 text-xs font-semibold bg-white shadow-sm rounded-md text-gray-800">1M</button>
                            <button class="px-3 py-1.5 text-xs font-semibold text-gray-500 hover:text-gray-700">6M</button>
                            <button class="px-3 py-1.5 text-xs font-semibold text-gray-500 hover:text-gray-700">1Y</button>
                        </div>
                    </div>
                    <div class="h-64 mt-4 w-full">
                        <div id="saldoAreaChart"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700">Persentase Target RAB</span>
                            <span class="text-sm font-bold text-gray-900">Global</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden mb-2">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 65%"></div>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Sistem mendeteksi arus kas stabil.</p>
                    </div>
                    
                    <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-6 flex items-start space-x-4">
                        <div class="p-3 bg-emerald-100 rounded-xl text-emerald-600">
                            <i class="fa-solid fa-check-double text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-emerald-900 mb-1">Status Keuangan Sehat</h4>
                            <p class="text-xs text-emerald-700 leading-relaxed">Pencatatan buku kas terstruktur dengan baik. Saldo Rutin mencukupi operasional.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider mb-1">Kas Rutin</p>
                        <h3 class="text-lg font-extrabold text-gray-900">Rp {{ number_format($saldoRutin ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider mb-1">Kas Cadangan</p>
                        <h3 class="text-lg font-extrabold text-gray-900">Rp {{ number_format($saldoCadangan ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    
                    <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex justify-between items-center hover:bg-gray-50 transition cursor-pointer">
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider mb-1">Kesiapan Dana Darurat</p>
                            <h3 class="text-xl font-extrabold text-gray-900">Aman</h3>
                        </div>
                        <div class="h-10 w-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-500">
                            <i class="fa-solid fa-shield-halved text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-4 gap-4">
                        <a href="{{ route('keuangan.buku-kas') }}" class="flex flex-col items-center group">
                            <div class="w-12 h-12 bg-gray-50 group-hover:bg-emerald-50 rounded-2xl flex items-center justify-center text-gray-500 group-hover:text-emerald-600 transition-colors border border-gray-100 group-hover:border-emerald-200">
                                <i class="fa-solid fa-wallet text-lg"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-gray-600 mt-2">Buku Kas</span>
                        </a>
                        <a href="{{ route('keuangan.kategori') }}" class="flex flex-col items-center group">
                            <div class="w-12 h-12 bg-gray-50 group-hover:bg-emerald-50 rounded-2xl flex items-center justify-center text-gray-500 group-hover:text-emerald-600 transition-colors border border-gray-100 group-hover:border-emerald-200">
                                <i class="fa-solid fa-layer-group text-lg"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-gray-600 mt-2">Kategori</span>
                        </a>
                        <button class="flex flex-col items-center group">
                            <div class="w-12 h-12 bg-gray-50 group-hover:bg-emerald-50 rounded-2xl flex items-center justify-center text-gray-500 group-hover:text-emerald-600 transition-colors border border-gray-100 group-hover:border-emerald-200">
                                <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-gray-600 mt-2">Transfer</span>
                        </button>
                        <a href="{{ route('keuangan.laporan') }}" class="flex flex-col items-center group">
                            <div class="w-12 h-12 bg-gray-50 group-hover:bg-emerald-50 rounded-2xl flex items-center justify-center text-gray-500 group-hover:text-emerald-600 transition-colors border border-gray-100 group-hover:border-emerald-200">
                                <i class="fa-solid fa-file-invoice text-lg"></i>
                            </div>
                            <span class="text-[10px] font-semibold text-gray-600 mt-2">Laporan</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-sm">ANALISIS PENGELUARAN</h3>
                    <button class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-ellipsis"></i></button>
                </div>
                <div class="h-48 w-full">
                    <div id="barAnalisisChart"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center">
                <h3 class="font-bold text-gray-900 text-sm w-full text-left mb-2">Status Kas Rutin</h3>
                <div class="relative w-full flex justify-center mt-2">
                    <div id="radialStatusChart"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center mt-4">
                        <span class="text-3xl font-black text-gray-900">{{ round($persenRutin >= 0 && $persenRutin <= 100 ? $persenRutin : 100) }}%</span>
                        <span class="text-[10px] text-emerald-500 font-bold bg-emerald-50 px-2 py-0.5 rounded mt-1">On Track</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 text-center mt-2 font-medium">Rasio persentase stabilitas pemasukan bulan ini.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-sm">Riwayat Transaksi</h3>
                    <a href="{{ route('keuangan.buku-kas') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
                </div>
                <div class="space-y-4 h-56 overflow-y-auto pr-2">
                    @forelse($transaksiTerbaru as $tr)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-xl transition-colors -mx-2">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full {{ $tr->kategori->jenis == 'Pemasukan' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid {{ $tr->kategori->jenis == 'Pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900 line-clamp-1">{{ $tr->uraian }}</p>
                                <p class="text-[10px] text-gray-500 font-medium">{{ \Carbon\Carbon::parse($tr->tanggal)->format('d M, Y') }} &bull; {{ $tr->kategori->peruntukan_kas }}</p>
                            </div>
                        </div>
                        <p class="text-xs font-bold flex-shrink-0 {{ $tr->kategori->jenis == 'Pemasukan' ? 'text-emerald-600' : 'text-gray-900' }}">
                            {{ $tr->kategori->jenis == 'Pemasukan' ? '+' : '-' }}Rp {{ number_format($tr->nominal, 0, ',', '.') }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center text-gray-400 text-sm mt-10">Belum ada transaksi.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-6">Target & Realisasi Anggaran (RAB/RAP)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($targets as $t)
                @php $progress = $t->target_tahunan > 0 ? ($t->transaksi_kas_sum_nominal / $t->target_tahunan) * 100 : 0; @endphp
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <span class="text-xs font-bold text-gray-900">{{ $t->nama_kategori }}</span>
                            <p class="text-[10px] text-gray-500 font-medium mt-0.5">Rp {{ number_format($t->transaksi_kas_sum_nominal, 0, ',', '.') }} / Rp {{ number_format($t->target_tahunan, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-xs font-extrabold {{ $progress >= 100 ? 'text-emerald-600' : 'text-gray-900' }}">{{ round($progress) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="{{ $progress >= 100 ? 'bg-emerald-500' : 'bg-gray-800' }} h-full rounded-full transition-all duration-500" style="width: {{ $progress > 100 ? 100 : $progress }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. AREA CHART (SALDO GABUNGAN) - Fintech Style
        var saldoOptions = {
            series: [{
                name: 'Saldo Rutin',
                data: {!! json_encode($dataLineRutin) !!}
            }, {
                name: 'Saldo Cadangan',
                data: {!! json_encode($dataLineCadangan) !!}
            }],
            chart: {
                type: 'area',
                height: 256,
                parentHeightOffset: 0,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#10B981', '#3B82F6'], // Emerald untuk Rutin, Biru untuk Cadangan
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: {!! json_encode($labelsLine) !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#9CA3AF', fontSize: '12px', fontWeight: 500 }
                }
            },
            yaxis: { show: false },
            grid: {
                show: true,
                borderColor: '#F3F4F6',
                strokeDashArray: 4,
                position: 'back',
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return "Rp " + val } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };
        new ApexCharts(document.querySelector("#saldoAreaChart"), saldoOptions).render();

        // 2. BAR CHART (ANALISIS PENGELUARAN 4 KATEGORI TERATAS)
        var barOptions = {
            series: [{
                name: 'Total',
                data: [{{ implode(',', $topKategori->pluck('transaksi_kas_sum_nominal')->toArray()) }}]
            }],
            chart: {
                type: 'bar',
                height: 200,
                toolbar: { show: false }
            },
            colors: ['#10B981'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    columnWidth: '40%',
                },
            },
            dataLabels: { enabled: false },
            stroke: { width: 0 },
            xaxis: {
                // Mengambil nama kategori secara dinamis dari database
                categories: [{!! implode(',', $topKategori->pluck('nama_kategori')->map(fn($k) => "'".substr($k, 0, 10)."'")->toArray()) !!}],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#9CA3AF', fontSize: '11px', fontWeight: 600 } }
            },
            yaxis: { show: false },
            grid: { show: false },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return "Rp " + val } }
            }
        };
        new ApexCharts(document.querySelector("#barAnalisisChart"), barOptions).render();

        // 3. RADIAL STATUS CHART (STATUS KAS RUTIN)
        var persentase = {{ round($persenRutin >= 0 && $persenRutin <= 100 ? $persenRutin : 100) }};
        var radialOptions = {
            series: [persentase],
            chart: {
                height: 250,
                type: 'radialBar',
                sparkline: { enabled: true }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 15,
                        size: '65%',
                        background: 'transparent',
                    },
                    track: {
                        background: '#F3F4F6',
                        strokeWidth: '100%',
                        margin: 0,
                        dropShadow: { enabled: false }
                    },
                    dataLabels: { show: false }
                }
            },
            fill: {
                type: 'solid',
                colors: [persentase < 50 ? '#F59E0B' : '#10B981'] // Kuning jika dibawah 50%, Hijau jika bagus
            },
            stroke: { lineCap: 'round' }
        };
        new ApexCharts(document.querySelector("#radialStatusChart"), radialOptions).render();
    });
</script>
@endpush