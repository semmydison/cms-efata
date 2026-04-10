@extends('layouts.app')

@section('title')
    Media & Ibadah <span class="text-gray-400 font-normal mx-2">></span> Liturgi
@endsection

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight">Repositori Liturgi & Slide</h1>
        <p class="text-gray-500 dark:text-gray-400">Pusat penyimpanan presentasi ibadah. Maksimal menampung 10 file terbaru.</p>
    </div>
    <button onclick="toggleModal('modalUpload')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center">
        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload Slide Baru
    </button>
</div>

@if($activeLiturgi)
<div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-3xl shadow-lg border border-blue-700 overflow-hidden mb-10 flex flex-col md:flex-row transition-all relative text-white">
    <div class="p-8 md:p-10 md:w-2/3 flex flex-col justify-center">
        <span class="inline-flex items-center bg-blue-900/50 text-blue-200 border border-blue-400/30 text-xs font-bold px-4 py-1.5 rounded-full uppercase mb-5 w-max shadow-sm tracking-wider">
            <span class="w-2.5 h-2.5 rounded-full bg-green-400 mr-2 animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></span> Disiapkan Untuk Ibadah Terdekat
        </span>
        <h2 class="text-3xl font-black mb-2 tracking-tight">{{ $activeLiturgi->judul }}</h2>
        <p class="text-blue-200 mb-4 font-medium"><i class="fa-regular fa-calendar mr-2"></i> Ibadah: {{ \Carbon\Carbon::parse($activeLiturgi->tanggal)->translatedFormat('l, d F Y') }}</p>
        
        <div class="bg-black/30 border border-white/20 rounded-xl p-3 mb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] text-blue-300 font-bold uppercase tracking-wider mb-0.5">Link Download Publik (Untuk Jemaat/Operator)</p>
                <p class="text-sm font-mono text-white select-all">{{ url('/liturgi/publik/download') }}</p>
            </div>
            <a href="{{ url('/liturgi/publik/download') }}" target="_blank" class="w-10 h-10 rounded-lg bg-blue-500 hover:bg-blue-400 flex items-center justify-center transition tooltip" title="Tes Link Publik">
                <i class="fa-solid fa-external-link-alt"></i>
            </a>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <a href="{{ url('/liturgi/download/' . $activeLiturgi->id) }}" class="px-6 py-3 bg-white text-blue-700 hover:bg-gray-100 font-black rounded-xl transition text-sm flex items-center shadow-lg transform hover:-translate-y-0.5">
                @if(Str::endsWith($activeLiturgi->file_name, '.pdf'))
                    <i class="fa-solid fa-file-pdf text-red-600 text-lg mr-2"></i> Download PDF Asli
                @else
                    <i class="fa-solid fa-download text-blue-600 text-lg mr-2"></i> Download & Buka di PowerPoint
                @endif
            </a>
            
            <a href="#tabelArsip" class="px-6 py-3 bg-blue-900/40 hover:bg-blue-900/60 text-blue-100 border border-blue-400/30 font-bold rounded-xl transition text-sm flex items-center shadow-sm">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> Lihat Koleksi Arsip
            </a>
        </div>
    </div>
    
    <div class="md:w-1/3 bg-black/20 flex flex-col items-center justify-center p-8 border-l border-white/10 backdrop-blur-sm">
        @if(Str::endsWith($activeLiturgi->file_name, '.pdf'))
            <i class="fa-solid fa-file-pdf text-7xl text-red-400 mb-4 opacity-90 drop-shadow-lg"></i>
        @else
            <i class="fa-solid fa-file-powerpoint text-7xl text-orange-400 mb-4 opacity-90 drop-shadow-lg"></i>
        @endif
        <p class="font-bold text-center truncate w-full px-4 text-blue-50">{{ $activeLiturgi->file_name }}</p>
        <p class="text-[10px] text-blue-300 mt-2 font-medium bg-black/30 px-3 py-1 rounded-full"><i class="fa-solid fa-cloud-arrow-up mr-1"></i> Diupload: {{ $activeLiturgi->created_at->format('d/m/Y - H:i:s') }}</p>
    </div>
</div>
@else
<div class="bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl p-12 text-center mb-10 flex flex-col items-center justify-center">
    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
        <i class="fa-solid fa-folder-open text-3xl text-gray-400 dark:text-gray-500"></i>
    </div>
    <h3 class="text-xl font-black text-gray-700 dark:text-gray-300 mb-2">Belum Ada File Ibadah Aktif</h3>
    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">Silakan upload slide presentasi baru atau aktifkan salah satu file dari tabel arsip di bawah untuk persiapan ibadah.</p>
</div>
@endif

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2" id="tabelArsip">
    <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i> Riwayat Arsip Berkas</h3>
    
    <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-1.5 border border-gray-200 dark:border-gray-600">
        <div class="w-2 h-2 rounded-full {{ $totalFiles >= 8 ? 'bg-orange-500 animate-pulse' : 'bg-green-500' }} mr-2"></div>
        <span class="text-xs font-bold text-gray-600 dark:text-gray-300">Kapasitas Repositori: <span class="text-gray-900 dark:text-white">{{ $totalFiles }} / 10 Berkas</span></span>
    </div>
</div>

<div class="bg-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center">
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-solid fa-magnifying-glass text-gray-400"></i></div>
            <input type="text" id="cariArsip" onkeyup="filterArsip()" class="block w-full pl-10 pr-3 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 outline-none dark:text-white transition-shadow" placeholder="Cari nama ibadah atau file...">
        </div>
    </div>
    <div class="overflow-x-auto hide-scrollbar">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-black">Informasi Berkas</th>
                    <th scope="col" class="px-6 py-4 font-black">Tanggal Ibadah & Waktu Upload</th>
                    <th scope="col" class="px-6 py-4 font-black">Ukuran</th>
                    <th scope="col" class="px-6 py-4 text-center font-black">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="bodyArsip">
                @forelse($arsipLiturgi as $arsip)
                <tr class="arsip-row hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition duration-200">
                    <td class="px-6 py-4 flex items-center">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-4 {{ Str::endsWith($arsip->file_name, '.pdf') ? 'bg-red-100 dark:bg-red-900/30 text-red-500' : 'bg-orange-100 dark:bg-orange-900/30 text-orange-500' }}">
                            @if(Str::endsWith($arsip->file_name, '.pdf'))
                                <i class="fa-solid fa-file-pdf text-xl"></i>
                            @else
                                <i class="fa-solid fa-file-powerpoint text-xl"></i>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-gray-800 dark:text-white nama-arsip truncate text-base">{{ $arsip->judul }}</p>
                            <a href="{{ url('/liturgi/download/' . $arsip->id) }}" class="text-xs text-blue-500 hover:text-blue-700 hover:underline file-arsip truncate block mt-0.5"><i class="fa-solid fa-download mr-1"></i>{{ $arsip->file_name }}</a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-700 dark:text-gray-300 tgl-arsip"><i class="fa-regular fa-calendar-check mr-1 text-blue-500"></i> {{ \Carbon\Carbon::parse($arsip->tanggal)->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 font-mono"><i class="fa-solid fa-clock-rotate-left mr-1"></i> {{ $arsip->created_at->format('d/m/Y H:i:s') }}</p>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">
                        {{ $arsip->file_size }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <form action="{{ url('/liturgi/aktifkan/'.$arsip->id) }}" method="POST" onsubmit="return confirm('Pindahkan file ini ke status Aktif (Akan menggantikan file aktif saat ini)?');">
                                @csrf @method('PUT')
                                <button type="submit" class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center tooltip" title="Jadikan File Aktif"><i class="fa-solid fa-arrow-up"></i></button>
                            </form>
                            
                            <form action="{{ url('/liturgi/delete/'.$arsip->id) }}" method="POST" onsubmit="return confirm('Hapus file ini permanen dari server? Aksi ini tidak dapat dibatalkan.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-colors flex items-center justify-center tooltip" title="Hapus File"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa-solid fa-box-open text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                        <p>Belum ada arsip berkas liturgi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalUpload" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center text-lg">
                <i class="fa-solid fa-cloud-arrow-up text-blue-600 mr-2"></i> Upload Berkas Liturgi
            </h3>
            <button onclick="toggleModal('modalUpload')" class="text-gray-400 hover:text-red-500 hover:rotate-90 transition-all duration-300"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>
        
        <form action="{{ url('/liturgi/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 overflow-y-auto space-y-5 max-h-[70vh]">
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Nama Kegiatan / Ibadah *</label>
                    <input type="text" name="judul" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 outline-none text-sm dark:text-white transition-all" placeholder="Contoh: Ibadah Raya Paskah...">
                </div>
                
                <div>
                    <label class="block text-xs font-black uppercase text-gray-500 mb-2">Tanggal Penggunaan *</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 outline-none text-sm dark:text-white transition-all">
                </div>

                <label class="block mt-4 border-2 border-dashed border-blue-300 dark:border-gray-600 bg-blue-50/50 dark:bg-gray-800/50 rounded-2xl p-10 text-center hover:bg-blue-50 dark:hover:bg-gray-700/80 transition-colors cursor-pointer group">
                    <input type="file" name="file_slide" required accept=".pptx, .ppt, .pdf" class="hidden" id="fileInput" onchange="updateFileName(this)">
                    <div class="flex justify-center space-x-4 mb-4" id="iconContainer">
                        <i class="fa-solid fa-file-powerpoint text-5xl text-orange-400 group-hover:text-orange-500 transition-transform group-hover:-translate-y-1 duration-300"></i>
                        <i class="fa-solid fa-file-pdf text-5xl text-red-400 group-hover:text-red-500 transition-transform group-hover:-translate-y-1 duration-300 delay-75"></i>
                    </div>
                    <div id="fileUploadNameContainer">
                        <p class="text-base font-bold text-blue-800 dark:text-blue-400">Pilih File PPTX atau PDF</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik untuk mencari file di perangkat Anda</p>
                    </div>
                    <div class="mt-4 flex justify-center">
                        <span class="text-[10px] font-bold tracking-wider text-gray-500 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-3 py-1 rounded-full shadow-sm">Batas Maks: 25 MB</span>
                    </div>
                </label>

                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl flex items-start border border-blue-100 dark:border-blue-800/50 mt-2">
                    <div class="flex items-center h-5 mt-0.5">
                        <input type="checkbox" name="langsung_tayang" value="1" checked class="w-4 h-4 text-blue-600 rounded bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-blue-500 cursor-pointer">
                    </div>
                    <div class="ml-3">
                        <label class="font-bold text-blue-800 dark:text-blue-300 text-sm cursor-pointer">Tandai Sebagai File Aktif</label>
                        <p class="text-blue-600/80 dark:text-blue-400/80 text-xs mt-0.5 leading-relaxed">Jika dicentang, file ini akan ditempatkan di panel utama sebagai persiapan ibadah terdekat dan bisa diakses lewat link publik.</p>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 flex justify-end space-x-3 rounded-b-3xl">
                <button type="button" onclick="toggleModal('modalUpload')" class="px-6 py-2.5 rounded-xl text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/50 transition-all flex items-center">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Mulai Upload
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

    function updateFileName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const isPdf = fileName.toLowerCase().endsWith('.pdf');
            
            const iconHtml = isPdf 
                ? '<i class="fa-solid fa-file-pdf text-6xl text-red-500"></i>'
                : '<i class="fa-solid fa-file-powerpoint text-6xl text-orange-500"></i>';
                
            document.getElementById('iconContainer').innerHTML = iconHtml;
            
            document.getElementById('fileUploadNameContainer').innerHTML = `
                <p class="text-base font-black text-gray-800 dark:text-white truncate px-4">${fileName}</p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-bold"><i class="fa-solid fa-check-circle mr-1"></i> File Siap Diupload</p>
            `;
        }
    }

    function filterArsip() {
        const cari = document.getElementById('cariArsip').value.toLowerCase();
        const rows = document.querySelectorAll('.arsip-row');
        
        rows.forEach(row => {
            const teks = row.innerText.toLowerCase();
            row.style.display = teks.includes(cari) ? '' : 'none';
        });
    }
</script>
@endpush