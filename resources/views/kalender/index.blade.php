@extends('layouts.app')

@section('title')
    Media & Ibadah <span class="text-gray-400 font-normal mx-2">></span> Kalender
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* CSS Grid Kalender */
    .calendar-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 0.35rem; }
    
    .date-box { transition: all 0.2s ease; cursor: pointer; position: relative; }
    .date-box:hover { transform: scale(1.02); z-index: 20; border-color: #3b82f6 !important; }
    
    .date-box:hover .date-num { transform: scale(1.15); font-weight: 900; color: #3b82f6; }
    .sepia-mode .date-box:hover .date-num { color: #433422; }

    /* Custom Shadow untuk Bulan Aktif (Terang / Menonjol) */
    .shadow-aktif {
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.08), 0 0 8px rgba(0, 0, 0, 0.04);
    }
    .dark .shadow-aktif {
        box-shadow: 0 4px 14px rgba(255, 255, 255, 0.03), 0 0 8px rgba(0, 0, 0, 0.15);
    }

    /* Custom Shadow untuk Bulan Inaktif (Gelap / Tenggelam) */
    .shadow-inaktif {
        box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.08);
    }
    .dark .shadow-inaktif {
        box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.3);
    }

    /* Tooltip Melayang pada Tanggal (Hover) */
    .date-tooltip {
        visibility: hidden; position: absolute; bottom: 105%; left: 50%; transform: translateX(-50%);
        background-color: #1f2937; color: #fff; text-align: left; padding: 10px 14px; border-radius: 10px;
        font-size: 11px; font-weight: normal; white-space: nowrap; z-index: 50; opacity: 0; transition: all 0.2s ease;
        pointer-events: none; margin-bottom: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        min-width: 140px;
    }
    .date-tooltip::after {
        content: ""; position: absolute; top: 100%; left: 50%; margin-left: -6px; border-width: 6px;
        border-style: solid; border-color: #1f2937 transparent transparent transparent;
    }
    .date-box:hover .date-tooltip { visibility: visible; opacity: 1; bottom: 100%; }
    .dark .date-tooltip { background-color: #374151; }
    .dark .date-tooltip::after { border-color: #374151 transparent transparent transparent; }
</style>
@endpush

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight">Kalender Gerejawi</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Atur jadwal ibadah, terhubung otomatis dengan hari raya & Ultah Jemaat.</p>
    </div>
    <button onclick="bukaModalTambah()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center">
        <i class="fa-solid fa-calendar-plus mr-2"></i> Tambah Agenda
    </button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    
    <div class="xl:col-span-2 bg-card bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 lg:p-6 flex flex-col">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg lg:text-xl font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ $currentDate->translatedFormat('F Y') }}</h2>
            <div class="flex space-x-1">
                <a href="{{ url('/kalender?month='.$prevMonth->month.'&year='.$prevMonth->year) }}" class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 transition"><i class="fa-solid fa-chevron-left w-5 text-center"></i></a>
                <a href="{{ url('/kalender') }}" class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-xs font-bold text-gray-600 dark:text-gray-300 transition flex items-center">Bulan Ini</a>
                <a href="{{ url('/kalender?month='.$nextMonth->month.'&year='.$nextMonth->year) }}" class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 transition"><i class="fa-solid fa-chevron-right w-5 text-center"></i></a>
            </div>
        </div>

        <div class="calendar-grid mb-2 border-b dark:border-gray-700 pb-2">
            <div class="text-center font-bold text-red-500 text-[10px] uppercase">Min</div>
            <div class="text-center font-bold text-gray-500 dark:text-gray-400 text-[10px] uppercase">Sen</div>
            <div class="text-center font-bold text-gray-500 dark:text-gray-400 text-[10px] uppercase">Sel</div>
            <div class="text-center font-bold text-gray-500 dark:text-gray-400 text-[10px] uppercase">Rab</div>
            <div class="text-center font-bold text-gray-500 dark:text-gray-400 text-[10px] uppercase">Kam</div>
            <div class="text-center font-bold text-gray-500 dark:text-gray-400 text-[10px] uppercase">Jum</div>
            <div class="text-center font-bold text-gray-500 dark:text-gray-400 text-[10px] uppercase">Sab</div>
        </div>

        <div class="calendar-grid flex-1">
            @php
                $firstDayOfMonth = $currentDate->copy()->startOfMonth();
                $daysInMonth = $firstDayOfMonth->daysInMonth;
                $startDayOfWeek = $firstDayOfMonth->dayOfWeek; 
            @endphp

            @for($i = 0; $i < $startDayOfWeek; $i++)
                @php $tglLalu = $firstDayOfMonth->copy()->subDays($startDayOfWeek - $i)->format('j'); @endphp
                <div class="h-16 lg:h-24 p-1 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 opacity-60 flex flex-col items-center justify-start shadow-inaktif">
                    <span class="text-xs sm:text-sm font-bold text-gray-400 dark:text-gray-600 mt-1">{{ $tglLalu }}</span>
                </div>
            @endfor

            @for($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $dateStr = $currentDate->format('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $hasAgenda = isset($agendas[$dateStr]);
                    $hasUltah = isset($ultahJemaat[$dateStr]);
                    $hasLibur = isset($hariLibur[$dateStr]);
                    
                    $isSunday = \Carbon\Carbon::parse($dateStr)->dayOfWeek === 0;
                    $isToday = $dateStr === date('Y-m-d');
                    $hasAnyEvent = $hasAgenda || $hasUltah || $hasLibur;
                @endphp

                <div class="date-box h-16 lg:h-24 p-1.5 rounded-xl border flex flex-col items-center justify-start overflow-visible transition-all relative
                    {{ $isToday ? 'border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800' }}
                    {{ $hasAnyEvent && !$isToday ? 'bg-blue-50/30 dark:bg-gray-800' : '' }} shadow-aktif
                ">
                    @if($hasAnyEvent)
                        <div class="date-tooltip flex flex-col gap-1.5">
                            <p class="font-bold text-blue-300 border-b border-gray-600 mb-1 pb-1 text-center">{{ date('d M Y', strtotime($dateStr)) }}</p>
                            @if($hasLibur) 
                                <p class="text-red-400 flex items-center"><i class="fa-solid fa-flag w-4 text-center mr-1"></i> {{ $hariLibur[$dateStr] }}</p> 
                            @endif
                            @if($hasUltah) 
                                @foreach($ultahJemaat[$dateStr] as $ju) 
                                    <p class="text-pink-400 flex items-center"><i class="fa-solid fa-cake-candles w-4 text-center mr-1"></i> HUT {{ explode(' ', trim($ju->nama_lengkap))[0] }}</p> 
                                @endforeach
                            @endif
                            @if($hasAgenda) 
                                @foreach($agendas[$dateStr] as $ag) 
                                    <p class="text-white flex items-center"><i class="fa-regular fa-calendar-check w-4 text-center mr-1"></i> {{ $ag->waktu ? \Carbon\Carbon::parse($ag->waktu)->format('H:i') . ' - ' : '' }}{{ $ag->nama_acara }}</p> 
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <span class="date-num text-sm sm:text-base mt-0.5 transition-all z-0 {{ $isToday ? 'font-black text-white bg-blue-500 rounded-full w-6 h-6 flex items-center justify-center shadow-md' : ($isSunday ? 'font-black text-red-500' : 'font-bold text-gray-700 dark:text-gray-200') }}">
                        {{ $i }}
                    </span>

                    <div class="w-full mt-auto space-y-0.5 overflow-y-auto hide-scrollbar text-left z-10 relative px-0.5">
                        @if($hasLibur)
                            <button onclick="lihatDetailSistem('Libur Nasional', '{{ $hariLibur[$dateStr] }}', '{{ date('d M Y', strtotime($dateStr)) }}')" class="text-[8px] sm:text-[9px] w-full text-left font-bold px-1 rounded truncate transition hover:opacity-80 bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400">
                                <i class="fa-solid fa-flag mr-0.5"></i> {{ $hariLibur[$dateStr] }}
                            </button>
                        @endif

                        @if($hasUltah)
                            @foreach($ultahJemaat[$dateStr] as $jemaat)
                                @php $umur = \Carbon\Carbon::parse($jemaat->tgl_lahir)->diffInYears(\Carbon\Carbon::parse($dateStr)); @endphp
                                <button onclick="lihatDetailSistem('Ulang Tahun Jemaat', 'HUT Ke-{{ $umur }} {{ $jemaat->nama_lengkap }}', '{{ date('d M Y', strtotime($dateStr)) }}')" class="text-[8px] sm:text-[9px] w-full text-left font-bold px-1 rounded truncate transition hover:opacity-80 bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400">
                                    <i class="fa-solid fa-cake-candles mr-0.5"></i> {{ explode(' ', trim($jemaat->nama_lengkap))[0] }} ({{ $umur }})
                                </button>
                            @endforeach
                        @endif

                        @if($hasAgenda)
                            @foreach($agendas[$dateStr] as $agenda)
                                @php
                                    $colorClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'; 
                                    if($agenda->warna_label == 'merah') $colorClass = 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400';
                                    if($agenda->warna_label == 'kuning') $colorClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                                @endphp
                                <button onclick='lihatDetailAgenda(@json($agenda))' class="text-[8px] sm:text-[9px] w-full text-left font-bold px-1 py-[1px] rounded truncate transition hover:opacity-80 {{ $colorClass }}">
                                    {{ $agenda->waktu ? \Carbon\Carbon::parse($agenda->waktu)->format('H:i') . ' ' : '' }}{{ $agenda->nama_acara }}
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endfor

            @php
                $totalCells = $startDayOfWeek + $daysInMonth;
                $paddingEnd = (7 - ($totalCells % 7)) % 7;
            @endphp
            @for($i = 1; $i <= $paddingEnd; $i++)
                <div class="h-16 lg:h-24 p-1 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 opacity-60 flex flex-col items-center justify-start shadow-inaktif">
                    <span class="text-xs sm:text-sm font-bold text-gray-400 dark:text-gray-600 mt-1">{{ $i }}</span>
                </div>
            @endfor
        </div>

        <div class="mt-4 flex flex-wrap justify-center lg:justify-start gap-3 text-[10px] font-medium border-t dark:border-gray-700 pt-3 text-gray-500 dark:text-gray-400">
            <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-1.5"></span> Hari Raya/Libur</div>
            <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-1.5"></span> Ibadah</div>
            <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-yellow-500 mr-1.5"></span> Rapat / Acara Lain</div>
            <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-pink-500 mr-1.5"></span> Ulang Tahun</div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white border-b dark:border-gray-700 pb-2">Agenda Gereja Terdekat</h3>
        
        @forelse($agendaTerdekat as $item)
            @php
                $borderColor = 'bg-blue-500'; $textColor = 'text-blue-600 dark:text-blue-400'; 
                $bgColor = 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800/50';
                if($item->warna_label == 'merah') {
                    $borderColor = 'bg-red-500'; $textColor = 'text-red-600 dark:text-red-400'; 
                    $bgColor = 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800/50';
                } elseif($item->warna_label == 'kuning') {
                    $borderColor = 'bg-yellow-500'; $textColor = 'text-yellow-600 dark:text-yellow-400'; 
                    $bgColor = 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-100 dark:border-yellow-800/50';
                }
            @endphp
            <div class="bg-card bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm flex items-start relative overflow-hidden group hover:shadow-md transition cursor-pointer" onclick='lihatDetailAgenda(@json($item))'>
                <div class="absolute top-0 left-0 w-1 h-full {{ $borderColor }}"></div>
                <div class="{{ $bgColor }} {{ $textColor }} rounded-lg p-2 text-center min-w-[50px] mr-3 border">
                    <span class="block text-[10px] font-bold uppercase">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('M') }}</span>
                    <span class="block text-lg font-black leading-none">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d') }}</span>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-white text-sm group-hover:text-blue-600 transition">{{ $item->nama_acara }}</h4>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fa-regular fa-clock mr-1"></i> {{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WITA' : 'Sepanjang Hari' }}
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center p-6 text-gray-500 border border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                Belum ada agenda gereja terdekat.
            </div>
        @endforelse
    </div>
</div>

<div id="modalKalender" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
            <h3 id="modalTitle" class="font-bold text-gray-800 dark:text-white flex items-center">
                <i class="fa-solid fa-calendar-plus text-blue-600 mr-2"></i> Tambah Agenda Baru
            </h3>
            <button type="button" onclick="toggleModal('modalKalender')" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto flex-1">
            <form id="formAgenda" action="{{ url('/kalender/store') }}" method="POST">
                @csrf
                <div id="methodContainerAgenda"></div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nama Acara / Peringatan *</label>
                        <input type="text" name="nama_acara" id="f_nama" required class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="Contoh: HUT Gereja, Paskah Pemuda...">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 mb-2">Tanggal Pelaksanaan *</label>
                            <input type="text" name="tanggal_mulai" id="f_tanggal" required class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white bg-white" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 mb-2">Waktu / Jam (Opsional)</label>
                            <input type="text" name="waktu" id="f_waktu" class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white bg-white" placeholder="Pilih Waktu">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-3">Tandai Sebagai (Warna Kalender) *</label>
                        <div class="flex space-x-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="warna_label" value="merah" id="c_merah" class="peer hidden" checked>
                                <div class="px-4 py-2 rounded-xl border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 text-xs font-bold flex items-center transition bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 peer-checked:text-red-700 dark:peer-checked:text-red-400">
                                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span> Hari Raya
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="warna_label" value="biru" id="c_biru" class="peer hidden">
                                <div class="px-4 py-2 rounded-xl border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 text-xs font-bold flex items-center transition bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 peer-checked:text-blue-700 dark:peer-checked:text-blue-400">
                                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Ibadah
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="warna_label" value="kuning" id="c_kuning" class="peer hidden">
                                <div class="px-4 py-2 rounded-xl border-2 border-transparent peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 text-xs font-bold flex items-center transition bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 peer-checked:text-yellow-700 dark:peer-checked:text-yellow-400">
                                    <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span> Rapat / Acara
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Deskripsi / Keterangan</label>
                        <textarea name="deskripsi" id="f_deskripsi" rows="3" class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 border-t dark:border-gray-700 flex justify-end space-x-3 bg-gray-50 dark:bg-gray-800">
            <button type="button" onclick="toggleModal('modalKalender')" class="px-6 py-2 rounded-xl text-gray-500 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</button>
            <button type="submit" form="formAgenda" class="px-6 py-2 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:bg-blue-700 transition">Simpan Agenda</button>
        </div>
    </div>
</div>

<div id="modalViewAgenda" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-blue-50 dark:bg-blue-900/20">
            <h3 class="font-bold text-blue-800 dark:text-blue-300 flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> Detail Agenda Gereja
            </h3>
            <button type="button" onclick="toggleModal('modalViewAgenda')" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <h2 id="view_nama_acara" class="text-2xl font-black text-gray-800 dark:text-white">-</h2>
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fa-regular fa-calendar text-blue-500 mr-3 w-4 text-center"></i> 
                <span id="view_tanggal" class="font-medium">-</span>
            </div>
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fa-regular fa-clock text-yellow-500 mr-3 w-4 text-center"></i> 
                <span id="view_waktu" class="font-medium">-</span>
            </div>
            <div class="pt-4 border-t dark:border-gray-700">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Deskripsi Keterangan</p>
                <p id="view_deskripsi" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border dark:border-gray-700">-</p>
            </div>
        </div>
        <div class="p-6 border-t dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800">
            <div id="deleteContainerAgenda">
                <form id="formDeleteAgenda" method="POST" onsubmit="return confirm('Yakin ingin menghapus agenda ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition flex items-center">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="toggleModal('modalViewAgenda')" class="px-5 py-2 rounded-xl text-gray-500 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Tutup</button>
                <button type="button" id="btnEditFromView" class="px-5 py-2 rounded-xl bg-yellow-500 text-white font-bold shadow-lg hover:bg-yellow-600 transition flex items-center">
                    <i class="fa-solid fa-pen mr-2"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modalViewSistem" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white dark:bg-gray-800 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden flex flex-col text-center relative pt-10">
        <button type="button" onclick="toggleModal('modalViewSistem')" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        <div class="px-6 pb-6 space-y-3">
            <div id="sys_icon" class="mx-auto w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4"></div>
            <p id="sys_tipe" class="text-xs font-black uppercase tracking-wider text-gray-400">INFO SISTEM</p>
            <h2 id="sys_judul" class="text-xl font-black text-gray-800 dark:text-white leading-tight">-</h2>
            <div class="inline-block bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-4 py-1.5 rounded-full mt-2">
                <span id="sys_tanggal" class="text-sm font-bold text-gray-600 dark:text-gray-300">-</span>
            </div>
            <p class="text-xs text-gray-400 mt-4 px-4 border-t dark:border-gray-700 pt-4">Informasi ini ditarik otomatis dari database Jemaat/Kalender Nasional dan tidak dapat diedit manual.</p>
        </div>
        <div class="p-4 bg-gray-50 dark:bg-gray-800">
            <button type="button" onclick="toggleModal('modalViewSistem')" class="w-full py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    // Inisialisasi Flatpickr Tanpa disableMobile, agar di HP memunculkan pemilih scroll bawaan native HP yang sangat mudah
    flatpickr("#f_tanggal", {
        dateFormat: "Y-m-d",
        locale: "id"
    });
    
    flatpickr("#f_waktu", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

    function bukaModalTambah() {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-calendar-plus text-blue-600 mr-2"></i> Tambah Agenda Baru';
        document.getElementById('formAgenda').action = "{{ url('/kalender/store') }}";
        document.getElementById('methodContainerAgenda').innerHTML = '';
        document.getElementById('formAgenda').reset();
        document.getElementById('c_biru').checked = true; 
        
        // Reset Flatpickr
        document.getElementById('f_tanggal')._flatpickr.clear();
        document.getElementById('f_waktu')._flatpickr.clear();

        toggleModal('modalKalender');
    }

    function lihatDetailAgenda(agenda) {
        event.stopPropagation();
        
        document.getElementById('view_nama_acara').innerText = agenda.nama_acara;
        const dateObj = new Date(agenda.tanggal_mulai);
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        document.getElementById('view_tanggal').innerText = dateObj.toLocaleDateString('id-ID', options);
        document.getElementById('view_waktu').innerText = agenda.waktu ? agenda.waktu.substring(0,5) + ' WITA' : 'Sepanjang Hari';
        document.getElementById('view_deskripsi').innerText = agenda.deskripsi || 'Tidak ada keterangan tambahan.';
        document.getElementById('formDeleteAgenda').action = "{{ url('/kalender/delete') }}/" + agenda.id;

        document.getElementById('btnEditFromView').onclick = function() {
            toggleModal('modalViewAgenda');
            bukaFormEdit(agenda);
        };
        toggleModal('modalViewAgenda');
    }

    function bukaFormEdit(agenda) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-500 mr-2"></i> Edit Agenda';
        document.getElementById('formAgenda').action = "{{ url('/kalender/update') }}/" + agenda.id;
        document.getElementById('methodContainerAgenda').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('f_nama').value = agenda.nama_acara;
        
        document.getElementById('f_tanggal')._flatpickr.setDate(agenda.tanggal_mulai);
        if(agenda.waktu) document.getElementById('f_waktu')._flatpickr.setDate(agenda.waktu.substring(0,5));
        
        document.getElementById('f_deskripsi').value = agenda.deskripsi || '';

        if(agenda.warna_label == 'merah') document.getElementById('c_merah').checked = true;
        else if(agenda.warna_label == 'kuning') document.getElementById('c_kuning').checked = true;
        else document.getElementById('c_biru').checked = true;

        toggleModal('modalKalender');
    }

    function lihatDetailSistem(tipe, judul, tanggal) {
        event.stopPropagation();
        
        document.getElementById('sys_tipe').innerText = tipe;
        document.getElementById('sys_judul').innerText = judul;
        document.getElementById('sys_tanggal').innerText = tanggal;

        let icon = '<i class="fa-solid fa-flag text-red-500 text-3xl"></i>';
        if(tipe === 'Ulang Tahun Jemaat') {
            icon = '<i class="fa-solid fa-cake-candles text-pink-500 text-3xl"></i>';
        }
        document.getElementById('sys_icon').innerHTML = icon;

        toggleModal('modalViewSistem');
    }
</script>
@endpush