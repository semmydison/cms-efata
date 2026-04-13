@extends('layouts.app')

@section('title')
    Keuangan <span class="text-gray-400 font-normal mx-2">></span> Pos Anggaran (RAB/RAP)
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Pos Anggaran</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Atur kategori pemasukan/pengeluaran dan target tahunan.</p>
    </div>
    
    <button onclick="openModalKategori()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md flex items-center justify-center">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Kategori
    </button>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-blue-50 dark:bg-blue-900/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
            <div class="p-2 bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300 rounded-lg mr-3">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800 dark:text-white">Pos Kas Rutin</h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">Nama Kategori</th>
                        <th class="p-4 font-semibold">Jenis</th>
                        <th class="p-4 font-semibold text-right">Target Tahunan</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    @forelse($kategoriRutin as $kr)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="p-4 font-medium text-gray-800 dark:text-gray-200">{{ $kr->nama_kategori }}</td>
                        <td class="p-4">
                            @if($kr->jenis == 'Pemasukan')
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-bold">Masuk</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-bold">Keluar</span>
                            @endif
                        </td>
                        <td class="p-4 text-right font-mono font-medium text-gray-600 dark:text-gray-300">
                            Rp {{ number_format($kr->target_tahunan, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <button onclick="editKategori({{ $kr->id }}, '{{ $kr->nama_kategori }}', '{{ $kr->jenis }}', '{{ $kr->peruntukan_kas }}', {{ $kr->target_tahunan }})" class="text-yellow-500 hover:text-yellow-600 transition"><i class="fa-solid fa-pen-to-square"></i></button>
                            <form action="{{ url('/keuangan/kategori/delete/'.$kr->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini? SEMUA TRANSAKSI yang menggunakan kategori ini juga akan TERHAPUS!');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400">Belum ada pos anggaran untuk Kas Rutin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-emerald-50 dark:bg-emerald-900/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-800 text-emerald-600 dark:text-emerald-300 rounded-lg mr-3">
                <i class="fa-solid fa-vault"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800 dark:text-white">Pos Kas Cadangan</h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">Nama Kategori</th>
                        <th class="p-4 font-semibold">Jenis</th>
                        <th class="p-4 font-semibold text-right">Target Tahunan</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    @forelse($kategoriCadangan as $kc)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="p-4 font-medium text-gray-800 dark:text-gray-200">{{ $kc->nama_kategori }}</td>
                        <td class="p-4">
                            @if($kc->jenis == 'Pemasukan')
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-bold">Masuk</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-bold">Keluar</span>
                            @endif
                        </td>
                        <td class="p-4 text-right font-mono font-medium text-gray-600 dark:text-gray-300">
                            Rp {{ number_format($kc->target_tahunan, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <button onclick="editKategori({{ $kc->id }}, '{{ $kc->nama_kategori }}', '{{ $kc->jenis }}', '{{ $kc->peruntukan_kas }}', {{ $kc->target_tahunan }})" class="text-yellow-500 hover:text-yellow-600 transition"><i class="fa-solid fa-pen-to-square"></i></button>
                            <form action="{{ url('/keuangan/kategori/delete/'.$kc->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400">Belum ada pos anggaran untuk Kas Cadangan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalKategori" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800 dark:text-white">Tambah Pos Anggaran</h3>
            <button onclick="closeModalKategori()" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-6">
            <form id="formKategori" action="{{ url('/keuangan/kategori/store') }}" method="POST">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Peruntukan Kas *</label>
                        <select name="peruntukan_kas" id="inputPeruntukan" required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                            <option value="Rutin">KAS RUTIN</option>
                            <option value="Cadangan">KAS CADANGAN</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Jenis Arus *</label>
                        <select name="jenis" id="inputJenis" required class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                            <option value="Pemasukan">Pemasukan (Uang Masuk)</option>
                            <option value="Pengeluaran">Pengeluaran (Uang Keluar)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Nama Kategori *</label>
                        <input type="text" name="nama_kategori" id="inputNama" required placeholder="Cth: Kolekte Utama, Listrik, Diakonia" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Target Tahunan / Pagu (Rp)</label>
                        <input type="number" name="target_tahunan" id="inputTarget" required value="0" min="0" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm dark:text-white">
                        <p class="text-[10px] text-gray-400 mt-1">Isi 0 jika tidak ada target/pagu khusus.</p>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeModalKategori()" class="px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-md">Simpan Pos</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modalKategori');
    const form = document.getElementById('formKategori');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openModalKategori() {
        title.innerText = 'Tambah Pos Anggaran';
        form.action = "{{ url('/keuangan/kategori/store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        modal.classList.remove('hidden');
    }

    function closeModalKategori() {
        modal.classList.add('hidden');
    }

    function editKategori(id, nama, jenis, peruntukan, target) {
        title.innerText = 'Edit Pos Anggaran';
        form.action = "{{ url('/keuangan/kategori/update') }}/" + id;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('inputNama').value = nama;
        document.getElementById('inputJenis').value = jenis;
        document.getElementById('inputPeruntukan').value = peruntukan;
        document.getElementById('inputTarget').value = target;
        
        modal.classList.remove('hidden');
    }
</script>
@endpush