@extends('layouts.app')

@section('title')
    Pengaturan Sistem <span class="text-gray-400 font-normal mx-2">></span> Profil Gereja
@endsection

@push('styles')
<style>
    .org-line::before { content: ''; position: absolute; top: -24px; left: 50%; width: 2px; height: 24px; background-color: #cbd5e1; }
    .dark .org-line::before { background-color: #475569; }
    .org-branch::after { content: ''; position: absolute; top: -24px; width: 50%; height: 2px; background-color: #cbd5e1; }
    .dark .org-branch::after { background-color: #475569; }
</style>
@endpush

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Profil & Struktur Gereja</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola identitas resmi, alamat, visi misi, serta susunan pengurus gereja.</p>
    </div>
    <div id="actionContainer" class="flex w-full sm:w-auto hidden">
        <button onclick="bukaModalTambah()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Tambah Pengurus
        </button>
    </div>
</div>

<div class="flex border-b border-gray-300 dark:border-gray-700 mb-6 overflow-x-auto hide-scrollbar">
    <button onclick="switchTab('identitas', this)" class="tab-btn px-6 py-3 font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 whitespace-nowrap transition">Identitas Gereja</button>
    <button onclick="switchTab('struktur', this)" class="tab-btn px-6 py-3 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent whitespace-nowrap transition">Struktur Organisasi (Majelis)</button>
</div>

<div id="tab-identitas" class="bg-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden block">
    <div class="p-6 md:p-8">
        <form action="{{ url('/profil-gereja/update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="flex flex-col items-center">
                    <label class="w-48 h-48 rounded-2xl bg-gray-50 dark:bg-gray-900 border-2 border-dashed border-gray-300 dark:border-gray-600 shadow-inner flex items-center justify-center relative group cursor-pointer overflow-hidden mb-4 transition-all hover:border-blue-500">
                        <input type="file" name="logo" id="logoGereja" accept="image/*" class="hidden" onchange="previewLogo(event)">
                        
                        @if(isset($profil) && $profil->logo)
                            <img id="previewLogoGereja" src="{{ asset('storage/' . $profil->logo) }}" onerror="this.style.display='none'; document.getElementById('defaultLogoIcon').classList.remove('hidden');" alt="Logo Gereja" class="absolute inset-0 w-full h-full object-contain p-2 z-10 bg-white">
                            <div id="defaultLogoIcon" class="hidden text-center p-4">
                                <i class="fa-solid fa-church text-5xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 dark:text-gray-500">Upload Logo</p>
                            </div>
                        @else
                            <img id="previewLogoGereja" src="" alt="Logo Gereja" class="hidden absolute inset-0 w-full h-full object-contain p-2 z-10">
                            <div id="defaultLogoIcon" class="text-center p-4">
                                <i class="fa-solid fa-church text-5xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 dark:text-gray-500">Upload Logo</p>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                            <i class="fa-solid fa-camera text-white text-2xl mb-1"></i>
                            <span class="text-white text-xs font-bold">Ganti Logo</span>
                        </div>
                    </label>
                    <p class="text-[10px] text-gray-400 text-center max-w-[200px]">Format: JPG, PNG, atau SVG. Ukuran ideal 500x500px.</p>
                </div>

                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-2">Nama Resmi Gereja *</label>
                            <input type="text" name="nama_gereja" value="{{ $profil->nama_gereja ?? 'Gereja Efata TBN' }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white font-bold transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-2">Alamat Lengkap *</label>
                            <textarea name="alamat" rows="2" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">{{ $profil->alamat ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-2">Nomor Telepon</label>
                            <input type="text" name="no_telepon" value="{{ $profil->no_telepon ?? '' }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-2">Email Resmi</label>
                            <input type="email" name="email" value="{{ $profil->email ?? '' }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-2">Visi Gereja</label>
                            <textarea name="visi" rows="2" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white italic transition-all">{{ $profil->visi ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400 mb-2">Misi Gereja</label>
                            <textarea name="misi" rows="3" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">{{ $profil->misi ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Identitas
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<div id="tab-struktur" class="hidden pb-10">
    <div class="flex flex-col items-center">
        
        @if($ketua)
        <div class="relative mb-10 w-full sm:w-1/2 md:w-1/3 xl:w-1/4 group">
            <div class="bg-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative transition-all z-20">
                <div class="h-24 bg-gradient-to-r from-blue-700 to-blue-500"></div>
                <div class="px-5 pb-5 text-center relative -mt-12">
                    <div class="w-24 h-24 mx-auto bg-gray-200 dark:bg-gray-700 border-4 border-white dark:border-gray-800 rounded-full shadow-md flex items-center justify-center text-4xl text-gray-400 mb-3 overflow-hidden bg-white">
                        @if($ketua->foto)
                            <img src="{{ asset('storage/' . $ketua->foto) }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Foto" class="w-full h-full object-cover">
                            <i class="fa-solid fa-user-tie hidden"></i>
                        @else
                            <i class="fa-solid fa-user-tie"></i>
                        @endif
                    </div>
                    <h3 class="font-black text-gray-800 dark:text-white text-xl leading-tight">{{ $ketua->nama_lengkap }}</h3>
                    <p class="text-sm font-bold text-blue-600 dark:text-blue-400 mt-1 uppercase tracking-wide">{{ $ketua->jabatan }}</p>
                    <p class="text-xs text-gray-500 mt-2">Periode {{ $ketua->periode_mulai }} - {{ $ketua->periode_selesai }}</p>
                </div>
            </div>
            
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all space-x-3 z-30 rounded-2xl">
                <button onclick="editPengurus('{{ $ketua->id }}', '{{ $ketua->nama_lengkap }}', '{{ $ketua->jabatan }}', '{{ $ketua->periode_mulai }}', '{{ $ketua->periode_selesai }}', '{{ $ketua->foto ? asset('storage/'.$ketua->foto) : '' }}')" class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg tooltip" title="Edit Profil"><i class="fa-solid fa-pen"></i></button>
                <form action="{{ url('/profil-gereja/pengurus/delete/'.$ketua->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Ketua Majelis ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg tooltip" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>

            @if($anggotas->count() > 0)
            <div class="absolute left-1/2 bottom-[-40px] w-0.5 h-10 bg-gray-300 dark:bg-gray-600 transform -translate-x-1/2 z-10"></div>
            @endif
        </div>
        @else
        <div class="mb-10 text-center text-gray-500">
            <p>Belum ada data Ketua Majelis Jemaat.</p>
        </div>
        @endif

        @php
            $wakil = $anggotas->where('jabatan', 'Wakil Ketua Majelis Jemaat')->first();
            $sekretarisBendahara = $anggotas->whereIn('jabatan', ['Sekretaris Jemaat', 'Bendahara Umum', 'Bendahara Khusus']);
        @endphp

        @if($wakil)
        <div class="relative mb-10 w-full sm:w-1/2 md:w-1/3 xl:w-1/4 group">
            <div class="bg-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative transition-all z-20">
                <div class="h-24 bg-gradient-to-r from-sky-600 to-sky-400"></div>
                <div class="px-5 pb-5 text-center relative -mt-12">
                    <div class="w-20 h-20 mx-auto bg-gray-200 dark:bg-gray-700 border-4 border-white dark:border-gray-800 rounded-full shadow-md flex items-center justify-center text-3xl text-gray-400 mb-3 overflow-hidden bg-white">
                        @if($wakil->foto)
                            <img src="{{ asset('storage/' . $wakil->foto) }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Foto" class="w-full h-full object-cover">
                            <i class="fa-solid fa-user hidden"></i>
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <h3 class="font-black text-gray-800 dark:text-white text-lg leading-tight">{{ $wakil->nama_lengkap }}</h3>
                    <p class="text-xs font-bold text-sky-600 dark:text-sky-400 mt-1 uppercase tracking-wide">{{ $wakil->jabatan }}</p>
                    <p class="text-[10px] text-gray-500 mt-2">Periode {{ $wakil->periode_mulai }} - {{ $wakil->periode_selesai }}</p>
                </div>
            </div>

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all space-x-3 z-30 rounded-2xl">
                <button onclick="editPengurus('{{ $wakil->id }}', '{{ $wakil->nama_lengkap }}', '{{ $wakil->jabatan }}', '{{ $wakil->periode_mulai }}', '{{ $wakil->periode_selesai }}', '{{ $wakil->foto ? asset('storage/'.$wakil->foto) : '' }}')" class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg tooltip" title="Edit Profil"><i class="fa-solid fa-pen"></i></button>
                <form action="{{ url('/profil-gereja/pengurus/delete/'.$wakil->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Wakil Ketua ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg tooltip" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>

            @if($sekretarisBendahara->count() > 0)
            <div class="absolute left-1/2 bottom-[-40px] w-0.5 h-10 bg-gray-300 dark:bg-gray-600 transform -translate-x-1/2 z-10"></div>
            @endif
        </div>
        @endif

        @if($sekretarisBendahara->count() > 0)
        <div class="relative w-full max-w-5xl mt-6">
            @if($sekretarisBendahara->count() > 1)
            <div class="absolute top-0 left-[16.666%] right-[16.666%] h-0.5 bg-gray-300 dark:bg-gray-600 z-10 hidden md:block"></div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-20 pt-8 md:pt-0 justify-center">
                @foreach($sekretarisBendahara as $anggota)
                <div class="relative md:pt-8 group">
                    @if($sekretarisBendahara->count() > 1)
                    <div class="absolute top-0 left-1/2 w-0.5 h-8 bg-gray-300 dark:bg-gray-600 transform -translate-x-1/2 z-10 hidden md:block"></div>
                    @endif

                    <div class="bg-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative">
                        <div class="h-16 {{ Str::contains($anggota->jabatan, 'Bendahara') ? 'bg-rose-600' : 'bg-emerald-600' }}"></div>
                        <div class="px-4 pb-4 text-center relative -mt-8">
                            <div class="w-16 h-16 mx-auto bg-gray-200 dark:bg-gray-700 border-4 border-white dark:border-gray-800 rounded-full shadow flex items-center justify-center text-2xl text-gray-400 mb-2 overflow-hidden bg-white">
                                @if($anggota->foto)
                                    <img src="{{ asset('storage/' . $anggota->foto) }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Foto" class="w-full h-full object-cover">
                                    <i class="fa-solid fa-user hidden"></i>
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-base leading-tight">{{ $anggota->nama_lengkap }}</h3>
                            <p class="text-xs font-bold {{ Str::contains($anggota->jabatan, 'Bendahara') ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} mt-1 uppercase tracking-wide">{{ $anggota->jabatan }}</p>
                        </div>

                        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all space-x-3 z-30 rounded-2xl">
                            <button onclick="editPengurus('{{ $anggota->id }}', '{{ $anggota->nama_lengkap }}', '{{ $anggota->jabatan }}', '{{ $anggota->periode_mulai }}', '{{ $anggota->periode_selesai }}', '{{ $anggota->foto ? asset('storage/'.$anggota->foto) : '' }}')" class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg tooltip" title="Edit Profil"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ url('/profil-gereja/pengurus/delete/'.$anggota->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengurus ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg tooltip" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<div id="modalPengurus" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity p-4">
    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-3xl shadow-2xl flex flex-col overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
            <h3 id="modalPengurusTitle" class="font-bold text-gray-800 dark:text-white flex items-center"><i class="fa-solid fa-user-plus text-blue-600 mr-2"></i> Form Pengurus / Majelis</h3>
            <button onclick="toggleModal('modalPengurus')" type="button" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        
        <form id="formPengurus" action="{{ url('/profil-gereja/pengurus/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>

            <div class="p-6 space-y-4">
                <div class="flex justify-center mb-4">
                    <label class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-900 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center relative cursor-pointer hover:border-blue-500 transition-colors overflow-hidden group">
                        <input type="file" name="foto" id="fotoPengurus" accept="image/*" class="hidden" onchange="previewPengurus(event)">
                        <i id="defaultPengurusIcon" class="fa-solid fa-camera text-2xl text-gray-400"></i>
                        <img id="previewFotoPengurus" src="" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-[10px] font-bold text-center">Pilih / Ubah Foto</span>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nama Lengkap (beserta Gelar) *</label>
                    <input type="text" name="nama_lengkap" id="inputNamaLengkap" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all" placeholder="Contoh: Pdt. Budi, S.Th">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Jabatan dalam Gereja *</label>
                    <select name="jabatan" id="inputJabatan" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">
                        <option value="Ketua Majelis Jemaat">Ketua Majelis Jemaat</option>
                        <option value="Wakil Ketua Majelis Jemaat">Wakil Ketua Majelis Jemaat</option>
                        <option value="Sekretaris Jemaat">Sekretaris Jemaat</option>
                        <option value="Bendahara Umum">Bendahara Umum</option>
                        <option value="Bendahara Khusus">Bendahara Khusus</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Periode Mulai</label>
                        <input type="number" name="periode_mulai" id="inputMulai" placeholder="Tahun (Ex: 2024)" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Periode Berakhir</label>
                        <input type="number" name="periode_selesai" id="inputSelesai" placeholder="Tahun (Ex: 2028)" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white transition-all">
                    </div>
                </div>
            </div>

            <div class="p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalPengurus')" class="px-6 py-2.5 rounded-xl text-gray-500 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-200 dark:shadow-none hover:bg-blue-700 transition"><i class="fa-solid fa-save mr-2"></i> Simpan Pengurus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- Solusi Fix: Fungsi Dropdown Master Layout ---
    function toggleDropdown(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(id);
        const isHidden = dropdown.classList.contains('hidden');
        closeAllDropdowns();
        if (isHidden) dropdown.classList.remove('hidden');
    }

    function closeAllDropdowns() {
        document.getElementById('notifDropdown')?.classList.add('hidden');
        document.getElementById('profileDropdown')?.classList.add('hidden');
    }

    window.onclick = function(event) {
        if (!event.target.closest('.relative')) closeAllDropdowns();
    }
    // ---------------------------------------------

    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

    // Membuka Form sebagai "TAMBAH BARU"
    function bukaModalTambah() {
        document.getElementById('modalPengurusTitle').innerHTML = '<i class="fa-solid fa-user-plus text-blue-600 mr-2"></i> Tambah Pengurus / Majelis';
        document.getElementById('formPengurus').action = "{{ url('/profil-gereja/pengurus/store') }}";
        document.getElementById('methodContainer').innerHTML = ''; // Pastikan POST (bukan PUT)
        document.getElementById('formPengurus').reset();
        
        document.getElementById('previewFotoPengurus').classList.add('hidden');
        document.getElementById('defaultPengurusIcon').classList.remove('hidden');
        
        toggleModal('modalPengurus');
    }

    // Membuka Form sebagai "EDIT"
    function editPengurus(id, nama, jabatan, mulai, selesai, fotoUrl) {
        document.getElementById('modalPengurusTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-500 mr-2"></i> Edit Data Pengurus';
        document.getElementById('formPengurus').action = "{{ url('/profil-gereja/pengurus/update') }}/" + id;
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('inputNamaLengkap').value = nama;
        document.getElementById('inputJabatan').value = jabatan;
        document.getElementById('inputMulai').value = mulai;
        document.getElementById('inputSelesai').value = selesai;

        if (fotoUrl && fotoUrl !== '') {
            document.getElementById('previewFotoPengurus').src = fotoUrl;
            document.getElementById('previewFotoPengurus').classList.remove('hidden');
            document.getElementById('defaultPengurusIcon').classList.add('hidden');
        } else {
            document.getElementById('previewFotoPengurus').classList.add('hidden');
            document.getElementById('defaultPengurusIcon').classList.remove('hidden');
        }

        toggleModal('modalPengurus');
    }

function switchTab(jenisTab, elemen) {
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.className = 'tab-btn px-6 py-3 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent whitespace-nowrap transition';
        });
        elemen.className = 'tab-btn px-6 py-3 font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 whitespace-nowrap transition';

        if (jenisTab === 'identitas') {
            document.getElementById('tab-identitas').classList.remove('hidden');
            document.getElementById('tab-identitas').classList.add('block');
            document.getElementById('tab-struktur').classList.remove('block');
            document.getElementById('tab-struktur').classList.add('hidden');
            document.getElementById('actionContainer').classList.add('hidden'); 
        } else {
            document.getElementById('tab-struktur').classList.remove('hidden');
            document.getElementById('tab-struktur').classList.add('block');
            document.getElementById('tab-identitas').classList.remove('block');
            document.getElementById('tab-identitas').classList.add('hidden');
            document.getElementById('actionContainer').classList.remove('hidden'); 
        }

        // SIMPAN MEMORI TAB KE BROWSER
        sessionStorage.setItem('tabProfilGerejaAktif', jenisTab);
    }

    // PANGGIL MEMORI SAAT HALAMAN SELESAI LOADING
    document.addEventListener("DOMContentLoaded", function() {
        const tabTersimpan = sessionStorage.getItem('tabProfilGerejaAktif');
        if (tabTersimpan === 'struktur') {
            // Simulasikan klik pada tab Struktur Organisasi
            const tombolStruktur = document.querySelectorAll('.tab-btn')[1];
            switchTab('struktur', tombolStruktur);
        }
    });

    function previewLogo(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgPreview = document.getElementById('previewLogoGereja');
                const defaultIcon = document.getElementById('defaultLogoIcon');
                imgPreview.src = e.target.result;
                imgPreview.classList.remove('hidden');
                if(defaultIcon) defaultIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewPengurus(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgPreview = document.getElementById('previewFotoPengurus');
                const defaultIcon = document.getElementById('defaultPengurusIcon');
                imgPreview.src = e.target.result;
                imgPreview.classList.remove('hidden');
                if(defaultIcon) defaultIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush