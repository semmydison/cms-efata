@extends('layouts.app')

@section('title')
    Manajemen Jemaat <span class="text-gray-400 font-normal mx-2">></span> Wilayah & Rayon
@endsection

@section('content')
@php
    $totalWilayah = count($wilayahs);
    $totalRayon = 0;
    $totalJiwaGlobal = 0;
    $kumpulanKKGlobal = collect();

    foreach($wilayahs as $w) {
        $totalRayon += $w->rayons->count();
        foreach($w->rayons as $r) {
            if(isset($r->jemaats)) {
                $totalJiwaGlobal += $r->jemaats->count();
                // PERBAIKAN: Menggunakan 'no_kk' sesuai database
                $kumpulanKKGlobal = $kumpulanKKGlobal->concat($r->jemaats->pluck('no_kk')->filter(function($value) {
                    return !empty($value) && $value !== '-';
                }));
            }
        }
    }
    $totalKKGlobal = $kumpulanKKGlobal->unique()->count();
@endphp

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Struktur Wilayah & Rayon</h2>
    
    <div class="flex space-x-3 w-full sm:w-auto">
        <button id="lockToggleBtn" onclick="toggleLock()" class="flex-1 sm:flex-none bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm flex items-center justify-center">
            <i id="lockIcon" class="fa-solid fa-lock mr-2"></i> Mode Aman
        </button>
        
        <button id="btnTambahArea" onclick="openModalArea('wilayah')" class="hidden flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm items-center justify-center">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Area
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 flex items-center transition-colors">
        <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 mr-4"><i class="fa-solid fa-map text-xl"></i></div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Wilayah</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalWilayah }} Wilayah</p>
        </div>
    </div>
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 flex items-center transition-colors">
        <div class="p-3 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 mr-4"><i class="fa-solid fa-map-pin text-xl"></i></div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Rayon</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalRayon }} Rayon</p>
        </div>
    </div>
    <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 flex items-center transition-colors">
        <div class="p-3 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 mr-4"><i class="fa-solid fa-users text-xl"></i></div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Keseluruhan</p>
            <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5">{{ $totalJiwaGlobal }} Jiwa <span class="text-xs font-normal text-gray-500 ml-1">&bull; {{ $totalKKGlobal }} KK</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    @forelse($wilayahs as $wilayah)
        @php
            $jiwaWilayah = 0;
            $kumpulanKKWilayah = collect();
            foreach($wilayah->rayons as $r) {
                if(isset($r->jemaats)) {
                    $jiwaWilayah += $r->jemaats->count();
                    $kumpulanKKWilayah = $kumpulanKKWilayah->concat($r->jemaats->pluck('no_kk')->filter(function($value) {
                        return !empty($value) && $value !== '-';
                    }));
                }
            }
            $kkWilayah = $kumpulanKKWilayah->unique()->count();
        @endphp

        <div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative transition-colors flex flex-col h-full">
            <div class="lock-overlay absolute inset-0 z-10 cursor-not-allowed bg-transparent"></div>
            
            <div class="bg-gray-50 dark:bg-gray-800/50 px-5 py-4 border-b dark:border-gray-700 flex justify-between items-center relative z-20">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white">{{ $wilayah->nama_wilayah }}</h3>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1 font-medium bg-blue-100 dark:bg-blue-900/40 px-2 py-0.5 rounded-full inline-block"><i class="fa-solid fa-users text-blue-600 mr-1"></i> {{ $jiwaWilayah }} Jiwa &bull; {{ $kkWilayah }} KK</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1.5 font-mono">ID Wilayah : {{ $wilayah->id }}</p>
                </div>
                <div class="admin-action hidden space-x-2">
                    <button onclick="editWilayah({{ $wilayah->id }}, '{{ htmlspecialchars($wilayah->nama_wilayah, ENT_QUOTES) }}')" class="text-gray-400 hover:text-blue-600 transition bg-white dark:bg-gray-700 p-1.5 rounded border dark:border-gray-600 shadow-sm"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form action="{{ url('/wilayah/delete/'.$wilayah->id) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN BAHAYA!\n\nMenghapus Wilayah ini akan OTOMATIS MENGHAPUS SEMUA RAYON DAN DATA JEMAAT di dalamnya.\n\nLanjutkan?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition bg-white dark:bg-gray-700 p-1.5 rounded border dark:border-gray-600 shadow-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            
            <div class="p-5 relative z-20 flex-1 flex flex-col">
                <ul class="space-y-3 flex-1">
                    @forelse($wilayah->rayons as $rayon)
                        @php
                            $jiwaRayon = isset($rayon->jemaats) ? $rayon->jemaats->count() : 0;
                            $kkRayon = isset($rayon->jemaats) ? $rayon->jemaats->pluck('no_kk')->filter(function($value) {
                                return !empty($value) && $value !== '-';
                            })->unique()->count() : 0;
                        @endphp
                        <li class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg border dark:border-gray-600 shadow-sm">
                            <div>
                                <span class="font-bold text-gray-700 dark:text-gray-200 text-sm block">{{ $rayon->nama_rayon }}</span>
                                @if($rayon->nama_penatua)
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block mb-0.5">Penatua: {{ $rayon->nama_penatua }}</span>
                                @endif
                                <span class="text-[10px] text-gray-400 block mb-1">{{ $jiwaRayon }} Jiwa &bull; {{ $kkRayon }} KK</span>
                                <span class="text-[10px] text-indigo-500 dark:text-indigo-400 font-mono block">ID Rayon : {{ $rayon->id }}</span>
                            </div>
                            <div class="admin-action hidden text-gray-400 border-l dark:border-gray-500 pl-3 space-x-1 flex-nowrap">
                                <button onclick="editRayon({{ $rayon->id }}, '{{ htmlspecialchars($rayon->nama_rayon, ENT_QUOTES) }}', '{{ htmlspecialchars($rayon->nama_penatua ?? '', ENT_QUOTES) }}', {{ $wilayah->id }})" class="hover:text-yellow-500 transition px-1.5 py-1"><i class="fa-solid fa-pen text-sm"></i></button>
                                <form action="{{ url('/wilayah/rayon/delete/'.$rayon->id) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN BAHAYA!\n\nMenghapus Rayon ini akan OTOMATIS MENGHAPUS SEMUA DATA JEMAAT di dalamnya.\n\nLanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hover:text-red-500 transition px-1.5 py-1"><i class="fa-solid fa-trash text-sm"></i></button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-sm text-gray-400 py-2">Belum ada rayon.</li>
                    @endforelse
                </ul>
                <button onclick="openModalArea('rayon', '{{ $wilayah->id }}')" class="admin-action hidden mt-4 w-full py-2 border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 rounded-lg text-sm font-medium hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition justify-center">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Rayon di {{ $wilayah->nama_wilayah }}
                </button>
            </div>
        </div>
    @empty
        <div class="col-span-full p-10 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl text-gray-500">
            <i class="fa-solid fa-map-location-dot text-4xl mb-3"></i>
            <p>Belum ada data wilayah. Silakan klik tombol "Mode Aman" lalu "Tambah Area".</p>
        </div>
    @endforelse
</div>

<div id="modalDataArea" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl flex flex-col overflow-hidden">
        
        <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h3 id="modalTitleArea" class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                <i class="fa-solid fa-map-location-dot text-blue-600 mr-2"></i> Tambah Area Baru
            </h3>
            <button onclick="closeModalArea()" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <div class="p-6">
            <div id="typeToggleContainer">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Pilih Jenis Area</label>
                <div class="flex space-x-4 mb-6 bg-gray-50 dark:bg-gray-900 p-1.5 rounded-xl border border-gray-200 dark:border-gray-700">
                    <label class="flex-1 text-center cursor-pointer relative">
                        <input type="radio" name="tipe_area_toggle" value="wilayah" id="radioWilayah" class="peer hidden" checked>
                        <div class="py-2 px-4 rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow text-gray-500 dark:text-gray-400 text-sm font-bold transition-all">
                            Wilayah
                        </div>
                    </label>
                    <label class="flex-1 text-center cursor-pointer relative">
                        <input type="radio" name="tipe_area_toggle" value="rayon" id="radioRayon" class="peer hidden">
                        <div class="py-2 px-4 rounded-lg peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:shadow text-gray-500 dark:text-gray-400 text-sm font-bold transition-all">
                            Rayon
                        </div>
                    </label>
                </div>
            </div>

            <form id="formWilayahAction" action="{{ url('/wilayah/store') }}" method="POST" class="block">
                @csrf 
                <div id="methodContainerWilayah"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nama Wilayah *</label>
                        <input type="text" name="nama_wilayah" id="inputNamaWilayah" placeholder="Contoh: Wilayah 5, Wilayah Utara..." required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition">
                    </div>
                </div>
                <div class="pt-6 mt-6 border-t dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeModalArea()" class="px-5 py-2.5 rounded-xl text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-md hover:bg-blue-700 transition flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>

            <form id="formRayonAction" action="{{ url('/wilayah/rayon/store') }}" method="POST" class="hidden">
                @csrf 
                <div id="methodContainerRayon"></div>
                <div class="space-y-4">
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                        <label class="block text-xs font-black uppercase text-indigo-800 dark:text-indigo-400 mb-2">Pilih Induk Wilayah *</label>
                        <select id="selectIndukWilayah" name="wilayah_id" required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none text-sm dark:text-white transition">
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nama Rayon *</label>
                        <input type="text" name="nama_rayon" id="inputNamaRayon" placeholder="Contoh: Rayon E, Rayon Syalom..." required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none text-sm dark:text-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nama Penatua Rayon</label>
                        <input type="text" name="nama_penatua" id="inputNamaPenatua" placeholder="Contoh: Bpk. Andreas" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none text-sm dark:text-white transition">
                    </div>
                </div>
                <div class="pt-6 mt-6 border-t dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeModalArea()" class="px-5 py-2.5 rounded-xl text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-md hover:bg-indigo-700 transition flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let isLocked = true; 
    
    function toggleLock() {
        isLocked = !isLocked;
        const lockBtn = document.getElementById('lockToggleBtn');
        const btnTambahArea = document.getElementById('btnTambahArea');
        const adminActions = document.querySelectorAll('.admin-action');
        const overlays = document.querySelectorAll('.lock-overlay');

        if (isLocked) {
            lockBtn.className = "flex-1 sm:flex-none bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm flex items-center justify-center";
            lockBtn.innerHTML = '<i class="fa-solid fa-lock mr-2"></i> Mode Aman';
            btnTambahArea.classList.remove('flex');
            btnTambahArea.classList.add('hidden');
            adminActions.forEach(el => {
                el.classList.remove('flex', 'block');
                el.classList.add('hidden');
            });
            overlays.forEach(el => el.style.display = 'block');
        } else {
            lockBtn.className = "flex-1 sm:flex-none bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800 px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm flex items-center justify-center";
            lockBtn.innerHTML = '<i class="fa-solid fa-unlock-keyhole mr-2"></i> Mode Edit';
            btnTambahArea.classList.remove('hidden');
            btnTambahArea.classList.add('flex');
            adminActions.forEach(el => {
                el.classList.remove('hidden');
                if (el.tagName === 'BUTTON' && el.classList.contains('w-full')) {
                    el.classList.add('flex'); 
                } else {
                    el.classList.add('flex'); 
                }
            });
            overlays.forEach(el => el.style.display = 'none');
        }
    }

    const modalArea = document.getElementById('modalDataArea');
    const radioWilayah = document.getElementById('radioWilayah');
    const radioRayon = document.getElementById('radioRayon');
    const formWilayah = document.getElementById('formWilayahAction');
    const formRayon = document.getElementById('formRayonAction');
    const selectInduk = document.getElementById('selectIndukWilayah');
    const typeToggleContainer = document.getElementById('typeToggleContainer');

    function openModalArea(tipe = 'wilayah', id_wilayah = '') {
        document.getElementById('modalTitleArea').innerHTML = '<i class="fa-solid fa-map-location-dot text-blue-600 mr-2"></i> Tambah Area Baru';
        
        formWilayah.action = "{{ url('/wilayah/store') }}";
        formRayon.action = "{{ url('/wilayah/rayon/store') }}";
        document.getElementById('methodContainerWilayah').innerHTML = '';
        document.getElementById('methodContainerRayon').innerHTML = '';
        formWilayah.reset();
        formRayon.reset();
        
        typeToggleContainer.classList.remove('hidden');
        modalArea.classList.remove('hidden');
        
        if (tipe === 'rayon') {
            radioRayon.checked = true;
            formWilayah.classList.add('hidden'); formWilayah.classList.remove('block');
            formRayon.classList.remove('hidden'); formRayon.classList.add('block');
            if(id_wilayah !== '') selectInduk.value = id_wilayah;
        } else {
            radioWilayah.checked = true;
            formRayon.classList.add('hidden'); formRayon.classList.remove('block');
            formWilayah.classList.remove('hidden'); formWilayah.classList.add('block');
            selectInduk.value = ''; 
        }
    }

    function closeModalArea() { 
        modalArea.classList.add('hidden'); 
    }

    radioWilayah.addEventListener('change', function() {
        if (this.checked) {
            formRayon.classList.add('hidden'); formRayon.classList.remove('block');
            formWilayah.classList.remove('hidden'); formWilayah.classList.add('block');
        }
    });

    radioRayon.addEventListener('change', function() {
        if (this.checked) {
            formWilayah.classList.add('hidden'); formWilayah.classList.remove('block');
            formRayon.classList.remove('hidden'); formRayon.classList.add('block');
        }
    });

    function editWilayah(id, nama) {
        document.getElementById('modalTitleArea').innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-600 mr-2"></i> Edit Wilayah';
        formWilayah.action = "{{ url('/wilayah/update') }}/" + id;
        document.getElementById('methodContainerWilayah').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        radioWilayah.checked = true;
        formRayon.classList.add('hidden'); formRayon.classList.remove('block');
        formWilayah.classList.remove('hidden'); formWilayah.classList.add('block');
        
        document.getElementById('inputNamaWilayah').value = nama;
        typeToggleContainer.classList.add('hidden'); 
        
        modalArea.classList.remove('hidden');
    }

    function editRayon(id, nama, penatua, wilayah_id) {
        document.getElementById('modalTitleArea').innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-600 mr-2"></i> Edit Rayon';
        formRayon.action = "{{ url('/wilayah/rayon/update') }}/" + id;
        document.getElementById('methodContainerRayon').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        radioRayon.checked = true;
        formWilayah.classList.add('hidden'); formWilayah.classList.remove('block');
        formRayon.classList.remove('hidden'); formRayon.classList.add('block');
        
        document.getElementById('selectIndukWilayah').value = wilayah_id;
        document.getElementById('inputNamaRayon').value = nama;
        document.getElementById('inputNamaPenatua').value = penatua;
        
        typeToggleContainer.classList.add('hidden'); 
        
        modalArea.classList.remove('hidden');
    }
</script>
@endpush