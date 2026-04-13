@extends('layouts.app')

@section('title')
    Keuangan <span class="text-gray-400 font-normal mx-2">></span> Buku Kas Harian
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Buku Kas Harian</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catat dan pantau seluruh arus lalu lintas keuangan gereja.</p>
    </div>
    
    <button onclick="openModalTransaksi()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md flex items-center justify-center">
        <i class="fa-solid fa-plus mr-2"></i> Catat Transaksi
    </button>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-list-ul text-blue-600 mr-2"></i> Riwayat Transaksi Terbaru</h3>
        </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold whitespace-nowrap">Tanggal</th>
                    <th class="p-4 font-semibold whitespace-nowrap">Kamar Kas</th>
                    <th class="p-4 font-semibold">Uraian & Kategori</th>
                    <th class="p-4 font-semibold text-right whitespace-nowrap">Pemasukan (Rp)</th>
                    <th class="p-4 font-semibold text-right whitespace-nowrap">Pengeluaran (Rp)</th>
                    <th class="p-4 font-semibold text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                @forelse($transaksis as $t)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="p-4 text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}
                    </td>
                    <td class="p-4">
                        @if($t->kategori->peruntukan_kas == 'Rutin')
                            <span class="bg-blue-100 text-blue-700 border border-blue-200 text-[10px] px-2 py-1 rounded-md font-bold uppercase tracking-wide">Rutin</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-[10px] px-2 py-1 rounded-md font-bold uppercase tracking-wide">Cadangan</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ $t->uraian }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><i class="fa-solid fa-tag text-gray-400 mr-1"></i> {{ $t->kategori->nama_kategori }}</p>
                    </td>
                    <td class="p-4 text-right font-mono font-bold text-green-600 dark:text-green-400">
                        {{ $t->kategori->jenis == 'Pemasukan' ? number_format($t->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="p-4 text-right font-mono font-bold text-red-600 dark:text-red-400">
                        {{ $t->kategori->jenis == 'Pengeluaran' ? number_format($t->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="p-4 text-center space-x-2 whitespace-nowrap">
                        <button onclick="editTransaksi({{ $t->id }}, '{{ $t->tanggal }}', {{ $t->kategori_id }}, '{{ htmlspecialchars($t->uraian, ENT_QUOTES) }}', {{ $t->nominal }}, '{{ $t->kategori->peruntukan_kas }}', '{{ $t->kategori->jenis }}')" class="text-yellow-500 hover:text-yellow-600 transition bg-white dark:bg-gray-800 border dark:border-gray-600 shadow-sm p-1.5 rounded"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form action="{{ url('/keuangan/transaksi/delete/'.$t->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Saldo kas akan otomatis terkoreksi.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 transition bg-white dark:bg-gray-800 border dark:border-gray-600 shadow-sm p-1.5 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-500">
                        <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                        Belum ada riwayat transaksi. Silakan klik tombol "Catat Transaksi".
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalTransaksi" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-cash-register text-blue-600 mr-2"></i> Catat Transaksi Baru</h3>
            <button onclick="closeModalTransaksi()" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <form id="formTransaksi" action="{{ url('/keuangan/transaksi/store') }}" method="POST">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50">
                        <div>
                            <label class="block text-[11px] font-black text-blue-800 dark:text-blue-300 uppercase mb-1">Kamar Kas</label>
                            <select id="filterKamar" onchange="filterKategori()" class="w-full bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-sm font-bold text-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Rutin">Kas Rutin</option>
                                <option value="Cadangan">Kas Cadangan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-blue-800 dark:text-blue-300 uppercase mb-1">Jenis Arus</label>
                            <select id="filterJenis" onchange="filterKategori()" class="w-full bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-sm font-bold text-green-600 dark:text-green-400 outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Pemasukan" class="text-green-600 font-bold">Pemasukan (+)</option>
                                <option value="Pengeluaran" class="text-red-600 font-bold">Pengeluaran (-)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Tanggal *</label>
                            <input type="date" name="tanggal" id="inputTanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Kategori Pos *</label>
                            <select name="kategori_id" id="inputKategori" required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                                <option value="">-- Pilih Pos --</option>
                                @foreach($kategoriPemasukan as $kp)
                                    <option value="{{ $kp->id }}" data-kamar="{{ $kp->peruntukan_kas }}" data-jenis="Pemasukan" class="hidden">{{ $kp->nama_kategori }}</option>
                                @endforeach
                                @foreach($kategoriPengeluaran as $kk)
                                    <option value="{{ $kk->id }}" data-kamar="{{ $kk->peruntukan_kas }}" data-jenis="Pengeluaran" class="hidden">{{ $kk->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Uraian / Keterangan *</label>
                        <input type="text" name="uraian" id="inputUraian" required placeholder="Cth: Kolekte Ibadah Utama 1, Bayar Listrik Bulan Juli" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Nominal (Rp) *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-bold">Rp</span>
                            </div>
                            <input type="number" name="nominal" id="inputNominal" required min="1" placeholder="0" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none font-mono text-lg font-bold dark:text-white tracking-wider">
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeModalTransaksi()" class="px-5 py-2.5 rounded-xl text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-md flex items-center">
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
    const modal = document.getElementById('modalTransaksi');
    const form = document.getElementById('formTransaksi');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');
    
    const filterKamar = document.getElementById('filterKamar');
    const filterJenis = document.getElementById('filterJenis');
    const selectKategori = document.getElementById('inputKategori');

    // Fungsi pintar untuk menyaring opsi kategori berdasarkan Kamar dan Jenis
    function filterKategori() {
        const selectedKamar = filterKamar.value;
        const selectedJenis = filterJenis.value;
        let hasValidOption = false;

        // Loop melalui semua opsi kategori
        Array.from(selectKategori.options).forEach(option => {
            if (option.value === "") return; // Abaikan opsi default "-- Pilih Pos --"
            
            const optKamar = option.getAttribute('data-kamar');
            const optJenis = option.getAttribute('data-jenis');

            // Tampilkan jika cocok, sembunyikan jika tidak
            if (optKamar === selectedKamar && optJenis === selectedJenis) {
                option.classList.remove('hidden');
                option.disabled = false;
                hasValidOption = true;
            } else {
                option.classList.add('hidden');
                option.disabled = true;
            }
        });

        // Reset pilihan ke default setiap kali filter berubah
        selectKategori.value = "";
        
        // Ubah warna text jenis arus
        if(selectedJenis === 'Pemasukan') {
            filterJenis.className = "w-full bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-sm font-bold text-green-600 dark:text-green-400 outline-none focus:ring-2 focus:ring-blue-500";
        } else {
            filterJenis.className = "w-full bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-sm font-bold text-red-600 dark:text-red-400 outline-none focus:ring-2 focus:ring-blue-500";
        }
    }

    function openModalTransaksi() {
        title.innerHTML = '<i class="fa-solid fa-cash-register text-blue-600 mr-2"></i> Catat Transaksi Baru';
        form.action = "{{ url('/keuangan/transaksi/store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        document.getElementById('inputTanggal').value = "{{ date('Y-m-d') }}";
        
        // Jalankan filter saat modal dibuka untuk merapikan dropdown pertama kali
        filterKategori();
        
        modal.classList.remove('hidden');
    }

    function closeModalTransaksi() {
        modal.classList.add('hidden');
    }

    function editTransaksi(id, tanggal, kategori_id, uraian, nominal, kamar, jenis) {
        title.innerHTML = '<i class="fa-solid fa-pen-to-square text-yellow-600 mr-2"></i> Edit Transaksi';
        form.action = "{{ url('/keuangan/transaksi/update') }}/" + id;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('inputTanggal').value = tanggal;
        document.getElementById('inputUraian').value = uraian;
        document.getElementById('inputNominal').value = nominal;
        
        // Set filter berdasarkan data yang mau diedit
        filterKamar.value = kamar;
        filterJenis.value = jenis;
        
        // Jalankan filter agar optionnya muncul
        filterKategori();
        
        // Setelah difilter, pilih value kategorinya
        setTimeout(() => {
            selectKategori.value = kategori_id;
        }, 50);
        
        modal.classList.remove('hidden');
    }
    
    // Auto-jalankan filter saat halaman dimuat jika modal sempat di-cache browser
    document.addEventListener("DOMContentLoaded", function() {
        filterKategori();
    });
</script>
@endpush