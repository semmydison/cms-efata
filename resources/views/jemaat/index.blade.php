@extends('layouts.app')

@section('title')
    Manajemen Jemaat <span class="text-gray-400 font-normal mx-2">></span> Data Jemaat
@endsection

@push('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .sort-icon { cursor: pointer; transition: color 0.2s; }
        .sort-icon:hover { color: #3b82f6; }
        .sort-active { color: #2563eb; }
    </style>
@endpush

@section('content')
<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Data Jemaat</h2>
    
    <div class="flex flex-wrap gap-2 w-full xl:w-auto">
        <button onclick="toggleModal('modalExport')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center">
            <i class="fa-solid fa-file-export mr-2"></i> Export Data
        </button>
        <button onclick="toggleModal('modalImport')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center">
            <i class="fa-solid fa-file-import mr-2"></i> Import Excel
        </button>
        <button onclick="toggleModal('modalJadwalRT')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center">
            <i class="fa-solid fa-calendar-alt mr-2"></i> Jadwal Ibadah Rayon
        </button>
        <button onclick="bukaModalTambah()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Tambah Jemaat
        </button>
    </div>
</div>

<div class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex flex-col lg:flex-row gap-4 justify-between items-center">
        <div class="relative w-full lg:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
            </div>
            <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 outline-none dark:text-white transition-all shadow-sm" placeholder="Cari nama, NIK, atau KK...">
        </div>
        
        <div class="flex flex-wrap gap-2 w-full lg:w-auto items-center">
            <span id="dataCountIndicator" class="text-xs font-bold text-gray-500 dark:text-gray-400 mr-2 bg-gray-200 dark:bg-gray-700 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600">
                Tampil <span id="countVisible" class="text-blue-600 dark:text-blue-400">{{ count($jemaats) }}</span> dari {{ count($jemaats) }} Data
            </span>

            <select id="filterWilayah" onchange="updateFilterRayon()" class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition-all shadow-sm cursor-pointer min-w-[140px]">
                <option value="all">Semua Wilayah</option>
                @foreach($wilayahs as $w)
                    <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                @endforeach
            </select>
            
            <select id="filterRayon" onchange="terapkanFilter()" class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition-all shadow-sm cursor-pointer min-w-[140px]">
                <option value="all">Semua Rayon</option>
            </select>

            <select id="filterStatus" onchange="terapkanFilter()" class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition-all shadow-sm cursor-pointer hidden sm:block">
                <option value="all">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="pindah">Pindah</option>
                <option value="meninggal dunia">Meninggal Dunia</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto hide-scrollbar">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="tabelJemaat">
            <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600 select-none">
                <tr>
                    <th scope="col" class="px-6 py-4 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer transition" onclick="sortTable('nama')">
                        NAMA <i class="fa-solid fa-sort ml-1 text-gray-400" id="iconSortNama"></i>
                    </th>
                    <th scope="col" class="px-6 py-4">NIK</th>
                    <th scope="col" class="px-6 py-4">KK</th>
                    <th scope="col" class="px-6 py-4 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer transition" onclick="sortTable('wilayah')">
                        WILAYAH <i class="fa-solid fa-sort ml-1 text-gray-400" id="iconSortWilayah"></i>
                    </th>
                    <th scope="col" class="px-6 py-4 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer transition" onclick="sortTable('rayon')">
                        RAYON <i class="fa-solid fa-sort ml-1 text-gray-400" id="iconSortRayon"></i>
                    </th>
                    <th scope="col" class="px-6 py-4">STATUS</th>
                    <th scope="col" class="px-6 py-4 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="tbodyJemaat">
                @forelse($jemaats as $jemaat)
                <tr class="jemaat-row hover:bg-gray-50 dark:hover:bg-gray-800/50 transition" 
                    data-wilayah="{{ $jemaat->wilayah_id }}" 
                    data-rayon="{{ $jemaat->rayon_id }}" 
                    data-status="{{ strtolower($jemaat->status_keanggotaan) }}"
                    data-kk="{{ $jemaat->no_kk }}"
                    data-nama="{{ $jemaat->nama_lengkap }}"
                    data-namawilayah="{{ strtolower($jemaat->wilayah->nama_wilayah ?? 'z') }}"
                    data-namarayon="{{ strtolower($jemaat->rayon->nama_rayon ?? 'z') }}">
                    
                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white nama-jemaat">{{ $jemaat->nama_lengkap }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-700 dark:text-gray-300 nik-jemaat">{{ $jemaat->nik }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-indigo-600 dark:text-indigo-400 kk-jemaat">{{ $jemaat->no_kk ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 text-[10px] font-bold px-2.5 py-0.5 rounded border border-blue-200 dark:border-blue-800">{{ $jemaat->wilayah->nama_wilayah ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-[10px] font-bold px-2.5 py-0.5 rounded border border-indigo-200 dark:border-indigo-800">{{ $jemaat->rayon->nama_rayon ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($jemaat->status_keanggotaan == 'Aktif')
                            <span class="flex items-center w-max text-xs font-bold text-green-600 dark:text-green-400"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Aktif</span>
                        @elseif($jemaat->status_keanggotaan == 'Pindah')
                            <span class="flex items-center w-max text-xs font-bold text-orange-500 dark:text-orange-400"><span class="w-2 h-2 rounded-full bg-orange-500 mr-2"></span> Pindah</span>
                        @else
                            <span class="flex items-center w-max text-xs font-bold text-red-600 dark:text-red-400"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Meninggal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center flex justify-center space-x-3 mt-1">
                        <button onclick='lihatDetail(@json($jemaat))' class="text-blue-500 hover:text-blue-700 transition" title="Lihat Detail"><i class="fa-solid fa-eye"></i></button>
                        <button onclick='editJemaat(@json($jemaat))' class="text-yellow-500 hover:text-yellow-700 transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form action="{{ url('/jemaat/delete/'.$jemaat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data jemaat ini secara permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-users-slash text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                        <p class="font-medium">Belum ada data jemaat yang terdaftar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="emptyState" class="hidden p-10 text-center text-gray-500 dark:text-gray-400 border-t dark:border-gray-700">
        <i class="fa-solid fa-users-slash text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
        <p class="font-medium">Data jemaat tidak ditemukan berdasarkan filter tersebut.</p>
        <p class="text-sm mt-1 text-gray-400">Silakan ubah kriteria pencarian Anda.</p>
    </div>
</div>

<div id="modalTambahJemaat" onclick="if(event.target === this) toggleModal('modalTambahJemaat')" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center transition-opacity cursor-pointer">
    <div class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl mx-4 my-8 flex flex-col max-h-[95vh] cursor-default">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-t-2xl">
            <h3 id="modalFormTitle" class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-address-card text-blue-600 mr-2"></i> Form Entri Data Jemaat</h3>
            <button onclick="toggleModal('modalTambahJemaat')" type="button" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <form id="formJemaat" action="{{ url('/jemaat/store') }}" method="POST">
                @csrf
                <div id="methodContainerJemaat"></div>

                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-400 uppercase tracking-wider mb-4 border-b dark:border-gray-700 pb-2"><i class="fa-regular fa-id-badge mr-1"></i> Data Identitas</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIK (KTP) *</label>
                        <input type="text" name="nik" id="f_nik" maxlength="16" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm font-mono tracking-widest dark:text-white" placeholder="16 Digit NIK">
                        <span id="nikWarning" class="text-xs text-red-500 font-bold hidden mt-1"><i class="fa-solid fa-triangle-exclamation"></i> NIK ini sudah terdaftar di sistem!</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Kartu Keluarga (KK) *</label>
                        <input type="text" name="no_kk" id="f_no_kk" maxlength="16" required class="w-full bg-yellow-50 dark:bg-yellow-900/20 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm font-mono tracking-widest dark:text-white" placeholder="16 Digit No. KK">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="f_nama" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="Nama sesuai identitas">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="f_tempat_lahir" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="Kota/Kabupaten">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir *</label>
                        <div class="relative">
                            <input type="date" name="tgl_lahir" id="f_tgl_lahir" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin *</label>
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" id="f_jk_L" value="L" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" required>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" id="f_jk_P" value="P" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" required>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Golongan Darah</label>
                        <select name="gol_darah" id="f_goldar" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                            <option value="">-- Pilih --</option>
                            <option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-lg">+62</span>
                            <input type="tel" name="no_whatsapp" id="f_wa" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-r-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="81234567890">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Saat Ini (Domisili)</label>
                        <textarea name="alamat_domisili" id="f_alamat" rows="2" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="Jalan, RT/RW, Kelurahan..."></textarea>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-400 uppercase tracking-wider mb-4 border-b dark:border-gray-700 pb-2"><i class="fa-solid fa-place-of-worship mr-1"></i> Atribut Gerejawi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-lg border border-blue-100 dark:border-blue-800/30">
                        <label class="block text-sm font-medium text-blue-900 dark:text-blue-300 mb-1">Wilayah *</label>
                        <select name="wilayah_id" id="f_wilayah" onchange="updateRayonDropdownForm()" required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm mb-3 dark:text-white">
                            <option value="">Pilih Wilayah</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                            @endforeach
                        </select>

                        <label class="block text-sm font-medium text-blue-900 dark:text-blue-300 mb-1">Rayon *</label>
                        <select name="rayon_id" id="f_rayon" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                            <option value="">-- Pilih Wilayah Dulu --</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Keanggotaan *</label>
                            <select name="status_keanggotaan" id="f_status" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white font-bold">
                                <option value="Aktif">Aktif</option>
                                <option value="Pindah">Pindah</option>
                                <option value="Meninggal Dunia">Meninggal Dunia</option>
                            </select>
                        </div>
                        <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-between bg-white dark:bg-gray-900">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sudah Baptis?</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status_baptis" value="1" id="f_baptis" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-between bg-white dark:bg-gray-900">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sudah Sidi?</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status_sidi" value="1" id="f_sidi" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-400 uppercase tracking-wider mb-4 border-b dark:border-gray-700 pb-2"><i class="fa-solid fa-sliders mr-1"></i> Preferensi & Privasi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profesi / Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="f_pekerjaan" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-blue-500 outline-none text-sm dark:text-white" placeholder="Contoh: PNS, Wiraswasta...">
                    </div>
                    <div class="md:col-span-2 mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minat Pelayanan (Pilih lebih dari satu)</label>
                        <div class="flex flex-wrap gap-2">
                            <div>
                                <input type="checkbox" id="minat_musik" name="minat_pelayanan[]" value="Musik & Pujian" class="peer hidden" />
                                <label for="minat_musik" class="inline-block px-4 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 peer-checked:bg-blue-100 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-800 dark:peer-checked:text-blue-400 peer-checked:border-blue-400 dark:peer-checked:border-blue-600 transition-all">
                                    <i class="fa-solid fa-music mr-1"></i> Musik & Pujian
                                </label>
                            </div>
                            <div>
                                <input type="checkbox" id="minat_multimedia" name="minat_pelayanan[]" value="Multimedia" class="peer hidden" />
                                <label for="minat_multimedia" class="inline-block px-4 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 peer-checked:bg-blue-100 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-800 dark:peer-checked:text-blue-400 peer-checked:border-blue-400 dark:peer-checked:border-blue-600 transition-all">
                                    <i class="fa-solid fa-camera mr-1"></i> Multimedia
                                </label>
                            </div>
                            <div>
                                <input type="checkbox" id="minat_sekolahminggu" name="minat_pelayanan[]" value="Sekolah Minggu" class="peer hidden" />
                                <label for="minat_sekolahminggu" class="inline-block px-4 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 peer-checked:bg-blue-100 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-800 dark:peer-checked:text-blue-400 peer-checked:border-blue-400 dark:peer-checked:border-blue-600 transition-all">
                                    <i class="fa-solid fa-child-reaching mr-1"></i> Sekolah Minggu
                                </label>
                            </div>
                            <div>
                                <input type="checkbox" id="minat_diakonia" name="minat_pelayanan[]" value="Diakonia" class="peer hidden" />
                                <label for="minat_diakonia" class="inline-block px-4 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 peer-checked:bg-blue-100 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-800 dark:peer-checked:text-blue-400 peer-checked:border-blue-400 dark:peer-checked:border-blue-600 transition-all">
                                    <i class="fa-solid fa-hand-holding-heart mr-1"></i> Diakonia/Sosial
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-2">
                        <label class="flex items-start p-3 border border-yellow-200 dark:border-yellow-800/50 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/20 transition">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="tampilkan_ultah" id="f_ultah" value="1" checked class="w-5 h-5 text-blue-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-bold text-gray-800 dark:text-gray-200 block">Izinkan Publikasi Ulang Tahun</span>
                                <span class="text-gray-600 dark:text-gray-400">Sistem akan menampilkan nama dan tanggal lahir di halaman publik.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                    <button type="button" onclick="toggleModal('modalTambahJemaat')" class="px-5 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg transition shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700">Batal</button>
                    <button type="submit" id="btnSubmitJemaat" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg transition shadow-sm flex items-center hover:bg-blue-700"><i class="fa-solid fa-save mr-2"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalDetail" onclick="if(event.target === this) toggleModal('modalDetail')" class="fixed inset-0 z-[110] hidden bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center transition-opacity p-4 cursor-pointer">
    <div class="relative w-full max-w-3xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl flex flex-col max-h-[95vh] cursor-default">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/20 rounded-t-2xl">
            <div class="flex items-center">
                <div id="v_inisial" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold mr-3 shadow">A</div>
                <div>
                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100 leading-tight">Detail Profil Jemaat</h3>
                    <p class="text-xs text-blue-700 dark:text-blue-300">Data Internal Sekretariat Gereja</p>
                </div>
            </div>
            <button onclick="toggleModal('modalDetail')" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-2xl"></i></button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <div class="flex flex-wrap gap-2">
                <span id="v_badge_status" class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-md border border-green-200">Aktif</span>
                <span id="v_badge_baptis" class="hidden px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-md border border-blue-200"><i class="fa-solid fa-droplet mr-1"></i> Sudah Baptis</span>
                <span id="v_badge_sidi" class="hidden px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-md border border-purple-200"><i class="fa-solid fa-dove mr-1"></i> Sudah Sidi</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-black uppercase text-gray-400 mb-1">Nama Lengkap</p>
                        <p id="v_nama" class="font-medium text-gray-800 dark:text-white">-</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs font-black uppercase text-gray-400 mb-1">NIK</p>
                            <p id="v_nik" class="font-medium text-gray-800 dark:text-white font-mono">-</p>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase text-gray-400 mb-1">Nomor KK</p>
                            <p id="v_kk" class="font-medium text-gray-800 dark:text-white font-mono">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs font-black uppercase text-gray-400 mb-1">Tempat/Tgl Lahir</p>
                            <p id="v_ttl" class="font-medium text-gray-800 dark:text-white">-</p>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase text-gray-400 mb-1">Gender / Goldar</p>
                            <p id="v_gender" class="font-medium text-gray-800 dark:text-white">-</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border dark:border-gray-700">
                    <div>
                        <p class="text-xs font-black uppercase text-gray-400 mb-1">Area Pelayanan</p>
                        <p id="v_area" class="font-bold text-blue-600 dark:text-blue-400">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-gray-400 mb-1">Profesi & Minat Pelayanan</p>
                        <p id="v_profesi" class="font-medium text-gray-800 dark:text-white">-</p>
                        <p id="v_minat" class="text-sm text-gray-500 mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-gray-400 mb-1">Kontak & Alamat</p>
                        <p id="v_kontak" class="font-medium text-gray-800 dark:text-white"><i class="fa-solid fa-phone text-xs mr-1 text-gray-400"></i> -</p>
                        <p id="v_alamat" class="text-sm text-gray-500 mt-1 leading-tight">-</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-right rounded-b-2xl">
            <button onclick="toggleModal('modalDetail')" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition shadow-sm">Tutup Preview</button>
        </div>
    </div>
</div>

<div id="modalExport" onclick="if(event.target === this) toggleModal('modalExport')" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4 cursor-pointer">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-3xl shadow-2xl flex flex-col overflow-hidden cursor-default">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-emerald-50 dark:bg-emerald-900/20">
            <h3 class="font-bold text-emerald-800 dark:text-emerald-300 flex items-center"><i class="fa-solid fa-file-export mr-2"></i> Export Data Jemaat</h3>
            <button onclick="toggleModal('modalExport')" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Pilih kriteria wilayah atau rayon yang ingin ditarik datanya untuk keperluan laporan atau sensus jemaat.</p>
            <div>
                <label class="block text-xs font-black uppercase text-gray-500 mb-2">Wilayah</label>
                <select class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 outline-none text-sm dark:text-white">
                    <option value="all">Semua Wilayah</option>
                    @foreach($wilayahs as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                    @endforeach
                </select>
            </div>
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800 mt-4">
                <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300 mb-2"><i class="fa-solid fa-circle-info mr-1"></i> Format Cetak Excel (Otomatis)</p>
                <ul class="text-[10px] text-gray-600 dark:text-gray-400 list-disc ml-4 space-y-1">
                    <li><strong>Kop Surat:</strong> Mengambil identitas dari Profil Gereja.</li>
                    <li><strong>Tanggal:</strong> Mengikuti tanggal cetak (hari ini).</li>
                </ul>
            </div>
        </div>
        <div class="p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-end space-x-3">
            <button onclick="toggleModal('modalExport')" class="px-6 py-2 rounded-xl text-gray-500 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</button>
            <a href="{{ url('/jemaat/export') }}" class="px-6 py-2 rounded-xl bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-200 dark:shadow-none hover:bg-emerald-700 transition flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i> Download Excel
            </a>
        </div>
    </div>
</div>

<div id="modalImport" onclick="if(event.target === this) toggleModal('modalImport')" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4 cursor-pointer">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-3xl shadow-2xl flex flex-col overflow-hidden cursor-default">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-indigo-50 dark:bg-indigo-900/20">
            <h3 class="font-bold text-indigo-800 dark:text-indigo-300 flex items-center"><i class="fa-solid fa-file-import mr-2"></i> Import Data Jemaat</h3>
            <button onclick="toggleModal('modalImport')" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        
        <form id="formImportJemaat" action="{{ url('/jemaat/import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-5">
                <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-200 dark:border-blue-800/30 flex flex-col items-center text-center">
                    <p class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-1">Belum punya formatnya?</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-3">Gunakan template resmi kami agar data pasti terbaca oleh sistem tanpa error.</p>
                    <a href="{{ url('/jemaat/template') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow transition flex items-center">
                        <i class="fa-solid fa-download mr-2"></i> Download Template Excel
                    </a>
                </div>

                <div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3"><i class="fa-solid fa-upload mr-1"></i> Unggah file Excel yang sudah Anda isi.</p>
                    <label class="block w-full border-2 border-dashed border-indigo-300 dark:border-indigo-700/50 rounded-2xl p-6 text-center hover:bg-indigo-50 dark:hover:bg-gray-800/50 transition cursor-pointer group bg-gray-50 dark:bg-gray-900/30 mb-3">
                        <input type="file" name="file_excel" id="fileImportExcel" accept=".xlsx, .xls" required class="hidden" onchange="updateImportFileName(this)">
                        <i class="fa-solid fa-file-excel text-4xl text-gray-300 dark:text-gray-600 group-hover:text-indigo-500 transition mb-3"></i>
                        <p id="importFileNameDisplay" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 transition-colors">Pilih file Excel</p>
                    </label>
                </div>
            </div>
            
            <div class="p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalImport')" class="px-6 py-2.5 rounded-xl text-gray-500 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition flex items-center">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Proses Import
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalJadwalRT" onclick="if(event.target === this) toggleModal('modalJadwalRT')" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4 cursor-pointer">
    <div class="bg-white dark:bg-gray-800 w-full max-w-5xl rounded-3xl shadow-2xl flex flex-col overflow-hidden max-h-[95vh] cursor-default">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-purple-50 dark:bg-purple-900/20">
            <h3 class="font-bold text-purple-800 dark:text-purple-300 flex items-center"><i class="fa-solid fa-calendar-alt mr-2"></i> Generate Jadwal Ibadah Rayon</h3>
            <button onclick="toggleModal('modalJadwalRT')" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-4">
            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl border border-purple-100 dark:border-purple-800 mb-2">
                <p class="text-sm text-purple-800 dark:text-purple-300"><strong>Sistem Cerdas:</strong> Sistem akan mengacak jadwal rumah ibadah berdasarkan data Kepala Keluarga (KK) di Rayon tersebut untuk hari Rabu, Sabtu, dan Minggu.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/2">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Pilih Wilayah Dulu</label>
                    <select id="genWilayah" onchange="updateGenRayon()" class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 outline-none text-sm dark:text-white">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-1/2">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Pilih Rayon</label>
                    <select id="genRayon" class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 outline-none text-sm dark:text-white">
                        <option value="">-- Pilih Wilayah Dulu --</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 items-end mt-4 border-b pb-6 dark:border-gray-700">
                <div class="w-full sm:w-2/3">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Periode Pelaksanaan</label>
                    <select id="f_periode_ibadah" class="w-full bg-gray-100 dark:bg-gray-900 border-none rounded-xl px-4 py-3 outline-none text-sm dark:text-white font-bold cursor-pointer">
                        <option value="1">Periode Semester 1 (Januari - Juni)</option>
                        <option value="2">Periode Semester 2 (Juli - Desember)</option>
                    </select>
                </div>
                <button type="button" onclick="generateJadwalRT()" class="w-full sm:w-1/3 px-6 py-3 rounded-xl bg-purple-600 text-white font-bold shadow-lg hover:bg-purple-700 transition flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-bolt mr-2"></i> Susun Jadwal
                </button>
            </div>

            <div id="hasilJadwalContainer" class="hidden mt-6 pt-2">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-gray-800 dark:text-white">Preview PDF</h4>
                    <button type="button" onclick="downloadPdfJadwal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Unduh PDF
                    </button>
                </div>

                <div class="flex justify-center bg-gray-200 dark:bg-gray-900 p-4 lg:p-8 rounded-xl overflow-x-auto">
                    <div id="pdfPrintArea" class="bg-white shadow-xl mx-auto" style="width: 210mm; min-height: 297mm; padding: 20mm; color: #000; box-sizing: border-box;">
                        
                        <div class="flex items-center justify-center relative mb-4" style="font-family: 'Times New Roman', Times, serif; color: #000; line-height: 1.15;">
                            <div class="absolute left-0 top-0 w-24 h-24 flex items-center justify-center">
                                <img src="{{ asset('assets/images/logo-gmit.png') }}" alt="Logo GMIT" class="max-w-full max-h-full object-contain">
                            </div>
                            
                            <div class="text-center w-full px-24">
                                <h2 class="text-[15px] font-bold uppercase m-0">GEREJA MASEHI INJILI DI TIMOR</h2>
                                <h3 class="text-[13px] font-bold uppercase m-0">(GBM GPI DAN ANGGOTA PGI)</h3>
                                <h2 class="text-[15px] font-bold uppercase m-0 mt-1">KLASIS SOE</h2>
                                <h1 class="text-[18px] font-bold uppercase m-0 mt-1">MAJELIS JEMAAT EFATA TUBUNAIN</h1>
                                <p class="text-[13px] font-bold m-0 mt-1">Jln. Jurusan Batuputih-Panite</p>
                            </div>
                        </div>
                        <div class="border-b-[3px] border-black mb-[2px]"></div>
                        <div class="border-b-[1px] border-black mb-8"></div>

                        <div class="text-center mb-8">
                            <h2 class="text-xl font-bold uppercase underline mb-2 tracking-wide">Jadwal Ibadah Rayon</h2>
                            <p id="labelPeriodePDF" class="text-sm font-bold m-0 text-gray-800">Periode: Semester 1 (Tahun 2026)</p>
                        </div>

                        <div class="text-[15px] mb-6 flex justify-between">
                            <div>
                                <table class="w-auto">
                                    <tr>
                                        <td class="pr-4 py-1 font-bold">Wilayah</td>
                                        <td class="py-1">: <span id="labelWilayahPDF">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="pr-4 py-1 font-bold">Rayon</td>
                                        <td class="py-1">: <span id="labelRayonPDF">-</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <table class="w-full text-sm border-collapse mb-12">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-black px-4 py-3 text-center font-bold" width="25%">Tanggal</th>
                                    <th class="border border-black px-4 py-3 text-center font-bold" width="20%">Hari</th>
                                    <th class="border border-black px-4 py-3 font-bold" width="55%">Tuan Rumah (Keluarga)</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyJadwalDinamic">
                            </tbody>
                        </table>

                        <div class="flex justify-end text-[15px] mt-12 pr-8" style="font-family: 'Times New Roman', Times, serif; color: #000;">
                            <div class="text-center w-64">
                                <p class="mb-1">Tubunain, <span id="tglCetakPDF"></span></p>
                                <p class="mb-24">Majelis Rayon <span id="labelTtdRayon"></span></p>
                                <p class="border-b-[2px] border-dashed border-black inline-block min-w-[200px]"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const rawWilayahData = {!! json_encode($wilayahs) !!};
    const dataWilayah = Array.isArray(rawWilayahData) ? rawWilayahData : Object.values(rawWilayahData);
    
    const existingNiks = {!! json_encode($jemaats->pluck('nik')->toArray()) !!};

    document.addEventListener('DOMContentLoaded', function() {
        terapkanFilter(); 
    });

    // --- FITUR SORTING TABEL JEMAAT ---
    let currentSort = { column: '', direction: 'asc' };
    
    function sortTable(column) {
        const tbody = document.getElementById('tbodyJemaat');
        const rows = Array.from(tbody.querySelectorAll('.jemaat-row'));
        
        // Atur arah urutan (toggle)
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Reset ikon urutan
        document.getElementById('iconSortNama').className = 'fa-solid fa-sort ml-1 text-gray-400';
        document.getElementById('iconSortWilayah').className = 'fa-solid fa-sort ml-1 text-gray-400';
        document.getElementById('iconSortRayon').className = 'fa-solid fa-sort ml-1 text-gray-400';

        // Ganti ikon sesuai arah urutan
        const activeIcon = document.getElementById('iconSort' + column.charAt(0).toUpperCase() + column.slice(1));
        if(currentSort.direction === 'asc') {
            activeIcon.className = 'fa-solid fa-sort-up ml-1 text-blue-600';
        } else {
            activeIcon.className = 'fa-solid fa-sort-down ml-1 text-blue-600';
        }

        // Logika pengurutan berdasarkan atribut data (data-nama, data-namawilayah, data-namarayon)
        rows.sort((a, b) => {
            let valA, valB;
            
            if (column === 'nama') {
                valA = a.getAttribute('data-nama');
                valB = b.getAttribute('data-nama');
            } else if (column === 'wilayah') {
                valA = a.getAttribute('data-namawilayah');
                valB = b.getAttribute('data-namawilayah');
            } else if (column === 'rayon') {
                valA = a.getAttribute('data-namarayon');
                valB = b.getAttribute('data-namarayon');
            }

            if (valA < valB) return currentSort.direction === 'asc' ? -1 : 1;
            if (valA > valB) return currentSort.direction === 'asc' ? 1 : -1;
            return 0;
        });

        // Susun ulang elemen tr di dalam tbody
        rows.forEach(row => tbody.appendChild(row));
    }


    document.getElementById('f_nik').addEventListener('keyup', function() {
        const inputNik = this.value;
        const originalNik = this.getAttribute('data-original'); 
        const warningEl = document.getElementById('nikWarning');
        const submitBtn = document.getElementById('btnSubmitJemaat');

        if (inputNik !== originalNik && existingNiks.includes(inputNik)) {
            warningEl.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            warningEl.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });

    function toggleModal(id) { 
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden'); 
    }

    function bukaModalTambah() {
        document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-user-plus text-blue-600 mr-2"></i> Form Entri Data Jemaat Baru';
        document.getElementById('formJemaat').action = "{{ url('/jemaat/store') }}";
        document.getElementById('methodContainerJemaat').innerHTML = '';
        document.getElementById('formJemaat').reset();
        
        document.getElementById('f_nik').removeAttribute('data-original');
        document.getElementById('nikWarning').classList.add('hidden');
        document.getElementById('btnSubmitJemaat').disabled = false;
        document.getElementById('btnSubmitJemaat').classList.remove('opacity-50', 'cursor-not-allowed');
        
        updateRayonDropdownForm(); 
        toggleModal('modalTambahJemaat');
    }

    function editJemaat(jemaat) {
        document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-500 mr-2"></i> Edit Data Jemaat';
        document.getElementById('formJemaat').action = "{{ url('/jemaat/update') }}/" + jemaat.id;
        document.getElementById('methodContainerJemaat').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        const inputNik = document.getElementById('f_nik');
        inputNik.value = jemaat.nik;
        inputNik.setAttribute('data-original', jemaat.nik);
        document.getElementById('nikWarning').classList.add('hidden');
        document.getElementById('btnSubmitJemaat').disabled = false;
        document.getElementById('btnSubmitJemaat').classList.remove('opacity-50', 'cursor-not-allowed');

        document.getElementById('f_no_kk').value = jemaat.no_kk;
        document.getElementById('f_nama').value = jemaat.nama_lengkap;
        document.getElementById('f_tempat_lahir').value = jemaat.tempat_lahir || '';
        
        document.getElementById('f_tgl_lahir').value = jemaat.tgl_lahir;
        
        if(jemaat.jenis_kelamin === 'L') document.getElementById('f_jk_L').checked = true;
        else document.getElementById('f_jk_P').checked = true;
        
        document.getElementById('f_goldar').value = jemaat.gol_darah || '';
        document.getElementById('f_wa').value = jemaat.no_whatsapp || '';
        document.getElementById('f_alamat').value = jemaat.alamat_domisili || '';
        
        document.getElementById('f_status').value = jemaat.status_keanggotaan;
        document.getElementById('f_baptis').checked = jemaat.status_baptis;
        document.getElementById('f_sidi').checked = jemaat.status_sidi;
        document.getElementById('f_ultah').checked = jemaat.tampilkan_ultah;
        document.getElementById('f_pekerjaan').value = jemaat.pekerjaan || '';

        const checkboxes = document.querySelectorAll('input[name="minat_pelayanan[]"]');
        checkboxes.forEach(cb => cb.checked = false);
        if(jemaat.minat_pelayanan && Array.isArray(jemaat.minat_pelayanan)) {
            checkboxes.forEach(cb => {
                if(jemaat.minat_pelayanan.includes(cb.value)) cb.checked = true;
            });
        }

        document.getElementById('f_wilayah').value = jemaat.wilayah_id;
        updateRayonDropdownForm(); 
        setTimeout(() => { document.getElementById('f_rayon').value = jemaat.rayon_id; }, 100);

        toggleModal('modalTambahJemaat');
    }

    function updateRayonDropdownForm() {
        const selectWilayah = document.getElementById('f_wilayah').value;
        const selectRayon = document.getElementById('f_rayon');
        selectRayon.innerHTML = '<option value="">-- Pilih Rayon --</option>';
        if (!selectWilayah) return;

        const wilayahTerpilih = dataWilayah.find(w => w.id == selectWilayah);
        const listRayons = wilayahTerpilih ? (wilayahTerpilih.rayons || []) : [];

        if (listRayons.length > 0) {
            listRayons.forEach(rayon => {
                let option = document.createElement('option');
                option.value = rayon.id;
                option.text = rayon.nama_rayon;
                selectRayon.add(option);
            });
        } else {
            selectRayon.innerHTML = '<option value="">-- Belum Ada Rayon --</option>';
        }
    }

    function updateFilterRayon() {
        const selectWilayah = document.getElementById('filterWilayah').value;
        const selectRayon = document.getElementById('filterRayon');
        
        selectRayon.innerHTML = '<option value="all">Semua Rayon</option>';
        if (selectWilayah !== 'all') {
            const wilayahTerpilih = dataWilayah.find(w => w.id == selectWilayah);
            const listRayons = wilayahTerpilih ? (wilayahTerpilih.rayons || []) : [];

            if (listRayons.length > 0) {
                listRayons.forEach(rayon => {
                    let option = document.createElement('option');
                    option.value = rayon.id; 
                    option.text = rayon.nama_rayon;
                    selectRayon.add(option);
                });
            }
        }
        terapkanFilter(); 
    }

    function terapkanFilter() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const wilayah = document.getElementById('filterWilayah').value;
        const rayon = document.getElementById('filterRayon').value;
        const status = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('.jemaat-row');
        
        let visibleCount = 0;
        let unikKK = new Set(); 

        rows.forEach(row => {
            const rowWilayah = row.getAttribute('data-wilayah');
            const rowRayon = row.getAttribute('data-rayon');
            const rowStatus = row.getAttribute('data-status');
            const namaJemaat = row.querySelector('.nama-jemaat').innerText.toLowerCase();
            const nikJemaat = row.querySelector('.nik-jemaat').innerText.toLowerCase();
            const kk = row.getAttribute('data-kk').toLowerCase();

            const matchSearch = namaJemaat.includes(search) || nikJemaat.includes(search) || kk.includes(search);
            const matchWilayah = wilayah === 'all' || rowWilayah === wilayah;
            const matchRayon = rayon === 'all' || rowRayon === rayon;
            const matchStatus = status === 'all' || rowStatus === status;

            if (matchSearch && matchWilayah && matchRayon && matchStatus) {
                row.style.display = ''; 
                visibleCount++;
                
                if (kk && kk !== '-' && kk !== '') {
                    unikKK.add(kk);
                }
            } else {
                row.style.display = 'none'; 
            }
        });

        document.getElementById('countVisible').innerText = visibleCount;
        document.getElementById('kkCountVisible').innerText = unikKK.size;

        if(visibleCount > 0) {
            document.getElementById('tabelJemaat').style.display = '';
            document.getElementById('emptyState').style.display = 'none';
        } else {
            document.getElementById('tabelJemaat').style.display = 'none';
            document.getElementById('emptyState').style.display = 'block';
        }
    }
    document.getElementById('searchInput').addEventListener('keyup', terapkanFilter);

    function lihatDetail(jemaat) {
        document.getElementById('v_inisial').innerText = jemaat.nama_lengkap.charAt(0).toUpperCase();
        document.getElementById('v_nama').innerText = jemaat.nama_lengkap;
        document.getElementById('v_nik').innerText = jemaat.nik;
        document.getElementById('v_kk').innerText = jemaat.no_kk;
        
        let tglIndo = jemaat.tgl_lahir ? jemaat.tgl_lahir.split('-').reverse().join('-') : '-';
        document.getElementById('v_ttl').innerText = (jemaat.tempat_lahir || '-') + ', ' + tglIndo;
        document.getElementById('v_gender').innerText = (jemaat.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') + ' / ' + (jemaat.gol_darah || '-');
        
        document.getElementById('v_area').innerText = (jemaat.wilayah ? jemaat.wilayah.nama_wilayah : '-') + ' - ' + (jemaat.rayon ? jemaat.rayon.nama_rayon : '-');
        document.getElementById('v_profesi').innerText = jemaat.pekerjaan || '-';
        document.getElementById('v_minat').innerText = jemaat.minat_pelayanan ? jemaat.minat_pelayanan.join(', ') : 'Belum memilih minat';
        document.getElementById('v_kontak').innerText = jemaat.no_whatsapp ? '+62 ' + jemaat.no_whatsapp : '-';
        document.getElementById('v_alamat').innerText = jemaat.alamat_domisili || '-';

        const bStatus = document.getElementById('v_badge_status');
        bStatus.innerText = jemaat.status_keanggotaan;
        if(jemaat.status_keanggotaan === 'Aktif') bStatus.className = 'px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-md border border-green-200';
        else if(jemaat.status_keanggotaan === 'Pindah') bStatus.className = 'px-3 py-1 bg-orange-100 text-orange-800 text-xs font-bold rounded-md border border-orange-200';
        else bStatus.className = 'px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-md border border-red-200';

        document.getElementById('v_badge_baptis').style.display = jemaat.status_baptis ? 'inline-block' : 'none';
        document.getElementById('v_badge_sidi').style.display = jemaat.status_sidi ? 'inline-block' : 'none';

        toggleModal('modalDetail');
    }

    function updateGenRayon() {
        const selectWilayah = document.getElementById('genWilayah').value;
        const selectRayon = document.getElementById('genRayon');
        selectRayon.innerHTML = '<option value="">-- Pilih Rayon --</option>';
        if (!selectWilayah) return;

        const wilayahTerpilih = dataWilayah.find(w => w.id == selectWilayah);
        const listRayons = wilayahTerpilih ? (wilayahTerpilih.rayons || []) : [];

        if (listRayons.length > 0) {
            listRayons.forEach(rayon => {
                let option = document.createElement('option');
                option.value = rayon.id;
                option.text = rayon.nama_rayon;
                selectRayon.add(option);
            });
        }
    }

    function generateJadwalRT() {
        const wil = document.getElementById('genWilayah').value;
        const ray = document.getElementById('genRayon').value;
        const periodeVal = document.getElementById('f_periode_ibadah').value;

        if(wil === '' || ray === '') {
            alert('Mohon pilih Wilayah dan Rayon terlebih dahulu!');
            return;
        }

        const genWilayahEl = document.getElementById('genWilayah');
        const genRayonEl = document.getElementById('genRayon');
        const namaWilayah = genWilayahEl.options[genWilayahEl.selectedIndex].text;
        const namaRayon = genRayonEl.options[genRayonEl.selectedIndex].text;
        
        const tahunSekarang = new Date().getFullYear();
        const namaPeriode = (periodeVal === '1') ? 'Semester 1 (Januari - Juni)' : 'Semester 2 (Juli - Desember)';

        document.getElementById('labelWilayahPDF').innerText = namaWilayah;
        document.getElementById('labelRayonPDF').innerText = namaRayon;
        document.getElementById('labelTtdRayon').innerText = namaRayon; 
        document.getElementById('labelPeriodePDF').innerText = `Periode: ${namaPeriode} Tahun ${tahunSekarang}`;
        
        const tglOptions = { day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('tglCetakPDF').innerText = new Date().toLocaleDateString('id-ID', tglOptions);

        let listKK = [];
        let unikKK = new Set();

        const rows = document.querySelectorAll('.jemaat-row');
        rows.forEach(row => {
            const rowWil = row.getAttribute('data-wilayah');
            const rowRay = row.getAttribute('data-rayon');
            const noKK = row.getAttribute('data-kk');
            const nama = row.getAttribute('data-nama');

            if (rowWil === wil && rowRay === ray) {
                if (noKK && noKK !== '' && noKK !== '-' && !unikKK.has(noKK)) {
                    unikKK.add(noKK);
                    listKK.push(nama); 
                } else if (!noKK || noKK === '' || noKK === '-') {
                    listKK.push(nama); 
                }
            }
        });

        const tbody = document.getElementById('tbodyJadwalDinamic');
        tbody.innerHTML = '';

        if (listKK.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="border border-black px-4 py-6 text-center text-red-600 font-bold">Tidak ada data jemaat (KK) di rayon ini. Pastikan data jemaat sudah diinput.</td></tr>';
        } else {
            listKK = listKK.sort(() => Math.random() - 0.5);

            let currentDate = (periodeVal === '1') ? new Date(tahunSekarang, 0, 1) : new Date(tahunSekarang, 6, 1);
            currentDate.setDate(currentDate.getDate() - 1); 
            
            const hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

            listKK.forEach(namaKeluarga => {
                while (true) {
                    currentDate.setDate(currentDate.getDate() + 1);
                    let day = currentDate.getDay();
                    if (day === 0 || day === 3 || day === 6) break; 
                }

                let hariStr = hariIndo[currentDate.getDay()];
                let tglStr = `${currentDate.getDate()} ${bulanIndo[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
                let styleHari = (currentDate.getDay() === 0) ? 'font-bold text-red-600' : 'text-black';

                tbody.innerHTML += `
                    <tr>
                        <td class="border border-black px-4 py-2 text-center text-black font-medium">${tglStr}</td>
                        <td class="border border-black px-4 py-2 text-center ${styleHari}">${hariStr}</td>
                        <td class="border border-black px-4 py-2 text-black">Kel. ${namaKeluarga}</td>
                    </tr>`;
            });
        }

        const container = document.getElementById('hasilJadwalContainer');
        container.classList.remove('hidden');
    }

    function downloadPdfJadwal() {
        const ray = document.getElementById('genRayon').value;
        if(ray === '') {
            alert('Silakan Susun Jadwal terlebih dahulu!');
            return;
        }

        const element = document.getElementById('pdfPrintArea');
        const genRayonEl = document.getElementById('genRayon');
        const namaRayon = genRayonEl.options[genRayonEl.selectedIndex].text;
        
        const opt = {
            margin:       [0, 0, 0, 0], 
            filename:     'Jadwal_Ibadah_' + namaRayon.replace(/\s+/g, '_') + '.pdf',
            image:        { type: 'jpeg', quality: 1 },
            html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }

    function updateImportFileName(input) {
        const display = document.getElementById('importFileNameDisplay');
        if (input.files && input.files[0]) {
            display.innerText = input.files[0].name;
            display.classList.remove('text-gray-600', 'dark:text-gray-300');
            display.classList.add('text-indigo-600', 'dark:text-indigo-400');
        } else {
            display.innerText = "Pilih file Excel";
            display.classList.remove('text-indigo-600', 'dark:text-indigo-400');
            display.classList.add('text-gray-600', 'dark:text-gray-300');
        }
    }

    document.getElementById('f_sidi').addEventListener('change', function() {
        if (this.checked) document.getElementById('f_baptis').checked = true;
    });
    document.getElementById('f_baptis').addEventListener('change', function() {
        if (!this.checked) document.getElementById('f_sidi').checked = false;
    });
</script>
@endpush