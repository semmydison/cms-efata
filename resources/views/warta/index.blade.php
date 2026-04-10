@extends('layouts.app')

@section('title')
    Media & Ibadah <span class="text-gray-400 font-normal mx-2">></span> Warta Jemaat
@endsection

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight">Warta Jemaat & Informasi</h1>
        <p class="text-gray-500 dark:text-gray-400">Pusat pengelolaan konten untuk Layar Publik dan Website Gereja.</p>
    </div>
    <div class="flex space-x-3">
        <button onclick="window.open('{{ url('/warta/publik') }}', 'TV_Warta', 'width=1280,height=720')" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition flex items-center">
            <i class="fa-solid fa-tv mr-2"></i> Buka Layar Publik
        </button>
        <button onclick="bukaModalInput()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Buat Berita Baru
        </button>
    </div>
</div>

<div id="filterContainer" class="flex space-x-2 mb-6 overflow-x-auto pb-2 hide-scrollbar">
    <a href="{{ url('/warta?kategori=all') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'all' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Semua Konten</a>
    <a href="{{ url('/warta?kategori=umum') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'umum' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Informasi Umum</a>
    <a href="{{ url('/warta?kategori=pesan') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'pesan' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Pesan Gembala</a>
    <a href="{{ url('/warta?kategori=surat') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'surat' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Surat Sinode</a>
    <a href="{{ url('/warta?kategori=himbauan') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'himbauan' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Himbauan Majelis</a>
    <a href="{{ url('/warta?kategori=duka') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'duka' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Berita Duka</a>
    <a href="{{ url('/warta?kategori=kehadiran') }}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ $kategoriAktif == 'kehadiran' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:shadow' }}">Kehadiran</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="wartaList">
    @forelse($wartas as $warta)
        @php
            $badgeBg = 'bg-gray-100 dark:bg-gray-700'; $badgeText = 'text-gray-700 dark:text-gray-300';
            if($warta->kategori == 'pesan') { $badgeBg = 'bg-green-100 dark:bg-green-900/30'; $badgeText = 'text-green-700 dark:text-green-400'; }
            if($warta->kategori == 'umum') { $badgeBg = 'bg-teal-100 dark:bg-teal-900/30'; $badgeText = 'text-teal-700 dark:text-teal-400'; }
            if($warta->kategori == 'surat') { $badgeBg = 'bg-orange-100 dark:bg-orange-900/30'; $badgeText = 'text-orange-700 dark:text-orange-400'; }
            if($warta->kategori == 'duka') { $badgeBg = 'bg-gray-800 dark:bg-black'; $badgeText = 'text-white'; }
            if($warta->kategori == 'kehadiran') { $badgeBg = 'bg-indigo-100 dark:bg-indigo-900/30'; $badgeText = 'text-indigo-700 dark:text-indigo-400'; }
            if($warta->kategori == 'himbauan') { $badgeBg = 'bg-blue-100 dark:bg-blue-900/30'; $badgeText = 'text-blue-700 dark:text-blue-400'; }
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col group hover:shadow-xl transition-all h-full">
            @if($warta->gambar)
                <div class="h-32 w-full bg-gray-200 dark:bg-gray-700 rounded-t-2xl overflow-hidden relative">
                    <img src="{{ asset('storage/'.$warta->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
            @endif

            <div class="p-5 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-3">
                    <span class="{{ $badgeBg }} {{ $badgeText }} text-[10px] font-black px-2 py-1 rounded uppercase">{{ str_replace('_', ' ', $warta->kategori) }}</span>
                    <span class="text-xs text-gray-400 font-medium"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($warta->tanggal_tampil)->translatedFormat('d M Y') }}</span>
                </div>
                
                <h3 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-2">{{ $warta->judul }}</h3>
                
                @if($warta->kategori == 'kehadiran')
                    <div class="mt-2 flex space-x-2">
                        <div class="flex-1 bg-blue-50 dark:bg-blue-900/20 p-2 rounded text-center"><p class="text-[10px] font-bold text-blue-600">Laki</p><p class="text-lg font-black text-gray-800 dark:text-white">{{ $warta->hadir_laki }}</p></div>
                        <div class="flex-1 bg-pink-50 dark:bg-pink-900/20 p-2 rounded text-center"><p class="text-[10px] font-bold text-pink-600">Perempuan</p><p class="text-lg font-black text-gray-800 dark:text-white">{{ $warta->hadir_perempuan }}</p></div>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ $warta->konten }}</p>
                @endif
                
                @if($warta->file_path)
                    <div class="mt-3 flex items-center text-xs font-bold text-red-500"><i class="fa-solid fa-paperclip mr-1"></i> Terlampir 1 PDF</div>
                @endif
            </div>

            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700 flex justify-between items-center mt-auto rounded-b-2xl">
                <button onclick='lihatPreviewWarta(@json($warta))' class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center"><i class="fa-solid fa-eye mr-1.5"></i> Mode Spekta</button>
                
                <div class="flex space-x-2">
                    <button onclick='bukaModalEdit(@json($warta))' class="p-1.5 text-gray-400 hover:text-yellow-500 transition tooltip" title="Edit Warta"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form action="{{ url('/warta/delete/'.$warta->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus konten ini secara permanen?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 transition tooltip" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="lg:col-span-3 text-center p-12 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl">
            <i class="fa-regular fa-newspaper text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400">Belum Ada Informasi</h3>
        </div>
    @endforelse
</div>

<div id="modalInput" onclick="if(event.target === this) toggleModal('modalInput')" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity cursor-pointer">
    <div class="relative w-full max-w-3xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] cursor-default">
        <div class="px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
            <h3 id="modalTitle" class="font-bold text-gray-800 dark:text-white flex items-center">
                <i class="fa-solid fa-paper-plane text-blue-600 mr-2"></i> Buat Berita Baru
            </h3>
            <button type="button" onclick="toggleModal('modalInput')" class="text-gray-400 hover:text-red-500 transition-all"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        
        <form id="formWarta" action="{{ url('/warta/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>

            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Judul Berita / Info *</label>
                        <input type="text" name="judul" id="f_judul" required class="w-full bg-gray-100 dark:bg-gray-900 border-none focus:ring-2 focus:ring-blue-500 rounded-xl px-4 py-3 outline-none text-sm dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Kategori *</label>
                        <select name="kategori" id="f_kategori" onchange="toggleFormKehadiran()" required class="w-full bg-gray-100 dark:bg-gray-900 border-none focus:ring-2 focus:ring-blue-500 rounded-xl px-4 py-3 outline-none text-sm dark:text-white font-bold">
                            <option value="umum">Informasi Umum (Bencana, Lomba, dll)</option>
                            <option value="pesan">Pesan Gembala</option>
                            <option value="surat">Surat Masuk Sinode</option>
                            <option value="himbauan">Himbauan Majelis</option>
                            <option value="duka">Berita Duka</option>
                            <option value="kehadiran">Laporan Kehadiran</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Tanggal Publikasi *</label>
                    <input type="date" name="tanggal_tampil" id="f_tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-100 dark:bg-gray-900 border-none focus:ring-2 focus:ring-blue-500 rounded-xl px-4 py-3 outline-none text-sm dark:text-white w-1/2">
                </div>

                <div id="formKehadiran" class="hidden grid-cols-2 gap-4 mt-2 bg-blue-50 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-100 dark:border-blue-800/30 mb-4">
                    <div class="col-span-2"><p class="text-xs font-bold text-blue-800 dark:text-blue-300"><i class="fa-solid fa-users mr-1"></i> Data Kehadiran Utama</p></div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Laki-Laki</label>
                        <input type="number" name="hadir_laki" id="f_laki" class="w-full bg-white dark:bg-gray-900 rounded-lg px-3 py-2 outline-none text-sm dark:text-white border dark:border-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-2">Perempuan</label>
                        <input type="number" name="hadir_perempuan" id="f_perempuan" class="w-full bg-white dark:bg-gray-900 rounded-lg px-3 py-2 outline-none text-sm dark:text-white border dark:border-gray-700">
                    </div>
                </div>

                <div id="formKontenTeks" class="mb-4">
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Isi Berita / Konten *</label>
                    <textarea name="konten" id="f_konten" rows="8" class="w-full bg-gray-100 dark:bg-gray-900 border-none focus:ring-2 focus:ring-blue-500 rounded-xl px-4 py-3 outline-none text-sm dark:text-white leading-relaxed" placeholder="Ketik berita selengkapnya di sini..."></textarea>
                </div>

                <div id="formUploadDoc" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="block w-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer">
                        <input type="file" name="gambar" class="hidden" accept="image/*" onchange="document.getElementById('lblGambar').innerText=this.files[0].name; document.getElementById('lblGambar').classList.add('text-blue-500')">
                        <i class="fa-solid fa-image text-3xl text-gray-400 mb-2"></i>
                        <p id="lblGambar" class="text-xs font-bold text-gray-600 dark:text-gray-300">Pilih Foto Sampul (Gambar)</p>
                    </label>

                    <label class="block w-full border-2 border-dashed border-red-300 dark:border-red-900/50 rounded-xl p-4 text-center hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">
                        <input type="file" name="file_dokumen" class="hidden" accept=".pdf" onchange="document.getElementById('lblPdf').innerText=this.files[0].name; document.getElementById('lblPdf').classList.add('text-red-500')">
                        <i class="fa-solid fa-file-pdf text-3xl text-red-400 mb-2"></i>
                        <p id="lblPdf" class="text-xs font-bold text-red-600 dark:text-red-400">Pilih Dokumen Lampiran (PDF)</p>
                    </label>
                </div>
            </div>

            <div class="p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-end space-x-3">
                <button type="button" onclick="toggleModal('modalInput')" class="px-6 py-2.5 rounded-xl text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:bg-blue-700 transition"><i class="fa-solid fa-save mr-2"></i> Simpan Warta</button>
            </div>
        </form>
    </div>
</div>

<div id="modalPreview" onclick="if(event.target === this) toggleModal('modalPreview')" class="fixed inset-0 z-[110] hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 lg:p-10 transition-opacity cursor-pointer">
    <div id="modalPreviewWindow" class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col h-full max-h-[90vh] cursor-default transition-all duration-300">
        <div id="prev_banner_container" class="h-48 md:h-64 w-full bg-gray-800 relative hidden flex-shrink-0">
            <img id="prev_banner_img" src="" class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
        </div>

        <button type="button" onclick="toggleModal('modalPreview')" class="absolute top-4 right-4 text-white hover:text-red-400 bg-black/50 w-10 h-10 rounded-full flex items-center justify-center transition-all z-50 shadow-md"><i class="fa-solid fa-xmark text-xl"></i></button>

        <div class="p-6 md:p-10 overflow-y-auto flex-1 relative z-10">
            <span id="prev_kategori" class="bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">KATEGORI</span>
            
            <h2 id="prev_judul" class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mt-4 mb-2 leading-tight">Judul Berita</h2>
            <p id="prev_tanggal" class="text-sm text-gray-500 font-bold mb-8 border-b dark:border-gray-700 pb-4"><i class="fa-regular fa-calendar-check mr-1"></i> Tanggal</p>
            
            <div id="prev_konten" class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-300 leading-loose text-lg font-serif">
                Isi berita...
            </div>

            <div id="prev_kehadiran" class="hidden mt-8 flex space-x-4 bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex-1 text-center"><p class="text-xs font-bold text-gray-500 uppercase">Laki-laki</p><p id="prev_laki" class="text-4xl font-black text-blue-500">0</p></div>
                <div class="w-px bg-gray-300 dark:bg-gray-600"></div>
                <div class="flex-1 text-center"><p class="text-xs font-bold text-gray-500 uppercase">Perempuan</p><p id="prev_perempuan" class="text-4xl font-black text-pink-500">0</p></div>
            </div>

            <div id="prev_pdf_container" class="hidden mt-10">
                <a id="prev_pdf_link" href="#" class="inline-flex items-center px-6 py-3 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-xl font-bold transition">
                    <i class="fa-solid fa-file-pdf text-2xl mr-3"></i> Baca Surat / PDF Terlampir
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

    function toggleFormKehadiran() {
        const kat = document.getElementById('f_kategori').value;
        const formHadir = document.getElementById('formKehadiran');
        const formTeks = document.getElementById('formKontenTeks');
        const formDoc = document.getElementById('formUploadDoc');

        if (kat === 'kehadiran') {
            formHadir.classList.remove('hidden'); formHadir.classList.add('grid');
            formTeks.style.display = 'none'; formDoc.style.display = 'none';
        } else {
            formHadir.classList.add('hidden'); formHadir.classList.remove('grid');
            formTeks.style.display = 'block'; formDoc.style.display = 'grid';
        }
    }

    function bukaModalInput() {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-paper-plane text-blue-600 mr-2"></i> Buat Berita Baru';
        document.getElementById('formWarta').action = "{{ url('/warta/store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('formWarta').reset();
        
        document.getElementById('lblGambar').innerText = "Pilih Foto Sampul (Gambar)";
        document.getElementById('lblGambar').className = "text-xs font-bold text-gray-600 dark:text-gray-300";
        document.getElementById('lblPdf').innerText = "Pilih Dokumen Lampiran (PDF)";
        document.getElementById('lblPdf').className = "text-xs font-bold text-red-600 dark:text-red-400";
        
        toggleFormKehadiran();
        toggleModal('modalInput');
    }

    function bukaModalEdit(warta) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-500 mr-2"></i> Edit Warta / Berita';
        document.getElementById('formWarta').action = "{{ url('/warta/update') }}/" + warta.id;
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('f_judul').value = warta.judul;
        document.getElementById('f_kategori').value = warta.kategori;
        document.getElementById('f_tanggal').value = warta.tanggal_tampil;
        document.getElementById('f_konten').value = warta.konten || '';
        document.getElementById('f_laki').value = warta.hadir_laki || '';
        document.getElementById('f_perempuan').value = warta.hadir_perempuan || '';
        
        document.getElementById('lblGambar').innerText = warta.gambar ? "(Ganti Foto Lama)" : "Pilih Foto Sampul Baru";
        document.getElementById('lblPdf').innerText = warta.file_path ? "(Ganti PDF Lama)" : "Pilih PDF Baru";

        toggleFormKehadiran();
        toggleModal('modalInput');
    }

    function lihatPreviewWarta(warta) {
        document.getElementById('prev_judul').innerText = warta.judul;
        document.getElementById('prev_kategori').innerText = warta.kategori.replace('_', ' ');
        document.getElementById('prev_tanggal').innerText = warta.tanggal_tampil; 

        const bannerCont = document.getElementById('prev_banner_container');
        if (warta.gambar) {
            document.getElementById('prev_banner_img').src = "/storage/" + warta.gambar;
            bannerCont.classList.remove('hidden');
        } else {
            bannerCont.classList.add('hidden');
        }

        const kontenDiv = document.getElementById('prev_konten');
        const hadirDiv = document.getElementById('prev_kehadiran');
        const modalWindow = document.getElementById('modalPreviewWindow'); // Elemen Jendela Pop-up Utama
        
        if (warta.kategori === 'kehadiran') {
            // MODE KEHADIRAN (Compact / Sempit)
            modalWindow.classList.remove('max-w-4xl');
            modalWindow.classList.add('max-w-md'); // Mengubah lebar menjadi kecil (compact)
            
            kontenDiv.classList.add('hidden');
            hadirDiv.classList.remove('hidden');
            document.getElementById('prev_laki').innerText = warta.hadir_laki;
            document.getElementById('prev_perempuan').innerText = warta.hadir_perempuan;
        } else {
            // MODE NORMAL BERITA (Lebar / Full)
            modalWindow.classList.remove('max-w-md');
            modalWindow.classList.add('max-w-4xl'); // Mengembalikan ke lebar asli
            
            hadirDiv.classList.add('hidden');
            kontenDiv.classList.remove('hidden');
            kontenDiv.innerHTML = (warta.konten || '').replace(/\n/g, '<br>');
        }

        const pdfCont = document.getElementById('prev_pdf_container');
        if (warta.file_path) {
            document.getElementById('prev_pdf_link').href = "/warta/download/" + warta.id;
            pdfCont.classList.remove('hidden');
        } else {
            pdfCont.classList.add('hidden');
        }

        toggleModal('modalPreview');
    }
</script>
@endpush