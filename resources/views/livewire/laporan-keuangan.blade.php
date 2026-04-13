<div class="max-w-7xl mx-auto py-6">

    @if($step == 1)
    <div class="min-h-[70vh] flex flex-col items-center justify-center animate-fade-in">
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-blue-600 text-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl rotate-3">
                <i class="fa-solid fa-vault text-3xl"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">E-Laporan Efata TBN</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Silakan pilih sumber kas yang ingin Anda proses.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl w-full px-6">
            <button wire:click="pilihKas('Rutin')" class="group bg-white rounded-[2rem] p-10 shadow-sm border-2 border-slate-100 hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-left relative overflow-hidden h-60">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-50 rounded-full group-hover:scale-[2.5] transition-transform duration-700 ease-out z-0"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg mb-6 group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-wallet text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 uppercase">Kas Rutin</h3>
                    <p class="text-sm text-slate-500 font-bold mt-2">Dana Operasional & Pelayanan Jemaat</p>
                </div>
            </button>

            <button wire:click="pilihKas('Cadangan')" class="group bg-white rounded-[2rem] p-10 shadow-sm border-2 border-slate-100 hover:border-orange-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-left relative overflow-hidden h-60">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-orange-50 rounded-full group-hover:scale-[2.5] transition-transform duration-700 ease-out z-0"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-orange-500 text-white rounded-2xl flex items-center justify-center shadow-lg mb-6 group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-piggy-bank text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 uppercase">Kas Cadangan</h3>
                    <p class="text-sm text-slate-500 font-bold mt-2">Dana Pembangunan & Cadangan Khusus</p>
                </div>
            </button>

            <button wire:click="pilihKas('Semua')" class="group bg-white rounded-[2rem] p-10 shadow-sm border-2 border-slate-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-left relative overflow-hidden h-60">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-50 rounded-full group-hover:scale-[2.5] transition-transform duration-700 ease-out z-0"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg mb-6 group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-layer-group text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 uppercase">Gabungan</h3>
                    <p class="text-sm text-slate-500 font-bold mt-2">Pratinjau Arus Kas Global (Preview)</p>
                </div>
            </button>
        </div>
    </div>
    @endif

    @if($step == 2)
    <div class="flex items-center justify-between mb-8 print:hidden animate-fade-in-down">
        <button wire:click="kembali" class="flex items-center gap-2 text-slate-500 hover:text-slate-900 font-bold transition-all group">
            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:bg-slate-100 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </div>
            Ganti Sumber Kas
        </button>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">
                <i class="fa-solid fa-check-double mr-2 text-emerald-400"></i> Sumber Kas: {{ $kas === 'Semua' ? 'GABUNGAN' : strtoupper($kas) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start animate-fade-in">
        
        <div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 print:hidden sticky top-6">
            <h2 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-blue-500"></i> Konfigurasi Laporan
            </h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Jenis Laporan</label>
                    <div class="space-y-2">
                        @php
                            $jenis = [
                                'bku' => ['Buku Kas Umum', 'fa-book'],
                                'pemasukan' => ['Laporan Pemasukan', 'fa-arrow-down'],
                                'pengeluaran' => ['Laporan Pengeluaran', 'fa-arrow-up'],
                                'realisasi' => ['Realisasi Anggaran', 'fa-chart-pie']
                            ];
                        @endphp
                        @foreach($jenis as $val => $data)
                        <label class="relative block cursor-pointer group">
                            <input type="radio" wire:model.live="jenisLaporan" value="{{ $val }}" class="peer sr-only">
                            <div class="p-4 rounded-xl border border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 hover:bg-slate-50 transition-all flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $jenisLaporan === $val ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500' }} transition-colors">
                                    <i class="fa-solid {{ $data[1] }}"></i>
                                </div>
                                <span class="text-sm font-bold {{ $jenisLaporan === $val ? 'text-blue-900' : 'text-slate-700' }}">{{ $data[0] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Periode</label>
                    <div class="flex bg-slate-100 p-1 rounded-lg mb-3">
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" wire:model.live="periode" value="bulanan" class="peer sr-only">
                            <div class="py-2 text-xs font-bold rounded-md peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-blue-600 text-slate-500 transition-all">Bulanan</div>
                        </label>
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" wire:model.live="periode" value="tahunan" class="peer sr-only">
                            <div class="py-2 text-xs font-bold rounded-md peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-blue-600 text-slate-500 transition-all">Tahunan</div>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @if($periode === 'bulanan')
                        <div class="col-span-1">
                            <select wire:model.live="bulan" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition-all cursor-pointer">
                                @php $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember']; @endphp
                                @foreach($namaBulan as $angka => $nama)
                                    <option value="{{ $angka }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="{{ $periode === 'tahunan' ? 'col-span-2' : 'col-span-1' }}">
                            <select wire:model.live="tahun" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition-all cursor-pointer">
                                @foreach($daftarTahun as $thn)
                                    <option value="{{ $thn }}">{{ $thn }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex gap-3">
                    @if($kas === 'Semua')
                        <div class="flex-1 bg-red-50 text-red-600 py-3 px-4 rounded-xl text-xs font-bold border border-red-200 text-center">
                            <i class="fa-solid fa-ban mr-2"></i> PDF Resmi Tidak Berlaku Untuk Kas Gabungan
                        </div>
                    @else
                        <a href="{{ route('keuangan.laporan.pdf', ['kas' => $kas, 'jenis' => $jenisLaporan, 'bulan' => $periode === 'bulanan' ? $bulan : 'Semua', 'tahun' => $tahun]) }}" 
                           target="_blank" 
                           class="flex-1 bg-slate-900 hover:bg-black text-white py-3 rounded-xl text-sm font-bold shadow-lg shadow-slate-200 transition-all flex justify-center items-center gap-2">
                            <i class="fa-solid fa-file-pdf"></i> Unduh PDF Resmi
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 relative">
            <div wire:loading class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 backdrop-blur-sm rounded-2xl transition-all">
                <div class="flex flex-col items-center">
                    <i class="fa-solid fa-circle-notch fa-spin text-blue-600 text-4xl mb-3"></i>
                    <span class="text-sm font-bold text-slate-600 uppercase tracking-widest">Menyusun Data...</span>
                </div>
            </div>

            <div wire:loading.class="opacity-40 scale-[0.99]" class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-slate-200 transition-all duration-300 ease-in-out print:shadow-none print:border-none print:p-0 print:m-0 font-serif text-slate-900 min-h-[800px]">
                
                <div class="text-center border-b-[3px] border-double border-slate-800 pb-6 mb-8">
                    <h1 class="text-lg font-bold tracking-widest uppercase">Majelis Jemaat GMIT Efata TBN</h1>
                    <h2 class="text-2xl font-black mt-1 uppercase">
                        {{ $jenisLaporan === 'bku' ? 'Buku Kas Umum' : ($jenisLaporan === 'pemasukan' ? 'Laporan Pemasukan' : 'Laporan Keuangan') }}
                    </h2>
                    <h3 class="text-md font-bold mt-1 uppercase">SUMBER DANA: KAS {{ $kas === 'Semua' ? 'GABUNGAN' : strtoupper($kas) }}</h3>
                    <p class="text-sm font-semibold uppercase tracking-widest mt-2">
                        Periode: {{ $periode === 'bulanan' ? $namaBulan[$bulan] ?? date('M') . ' ' . $tahun : 'Tahun ' . $tahun }}
                    </p>
                </div>

                <table class="w-full text-[13px] border-collapse border border-slate-800 mb-6">
                    <thead class="bg-slate-100 text-slate-800">
                        <tr>
                            <th class="border border-slate-800 px-3 py-2 text-center w-10 uppercase tracking-wider">No</th>
                            <th class="border border-slate-800 px-3 py-2 text-left uppercase tracking-wider">Tanggal</th>
                            <th class="border border-slate-800 px-3 py-2 text-left uppercase tracking-wider">Uraian / Kategori</th>
                            <th class="border border-slate-800 px-3 py-2 text-right w-28 uppercase tracking-wider">Masuk (Rp)</th>
                            <th class="border border-slate-800 px-3 py-2 text-right w-28 uppercase tracking-wider">Keluar (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi ?? [] as $index => $item)
                            @php
                                // Konversi data ke format akuntansi yang benar
                                $isMasuk = $item->kategori->jenis === 'Pemasukan';
                                $masuk = $isMasuk ? $item->nominal : 0;
                                $keluar = !$isMasuk ? $item->nominal : 0;
                            @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-800 px-3 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-slate-800 px-3 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="border border-slate-800 px-3 py-2">
                                <span class="font-bold block">{{ $item->uraian }}</span>
                                <span class="text-[10px] text-slate-500 italic">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                            </td>
                            <td class="border border-slate-800 px-3 py-2 text-right font-mono {{ $masuk > 0 ? 'font-bold text-emerald-700' : 'text-slate-400' }}">
                                {{ $masuk > 0 ? number_format($masuk, 0, ',', '.') : '-' }}
                            </td>
                            <td class="border border-slate-800 px-3 py-2 text-right font-mono {{ $keluar > 0 ? 'font-bold text-red-700' : 'text-slate-400' }}">
                                {{ $keluar > 0 ? number_format($keluar, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="border border-slate-800 px-3 py-10 text-center text-slate-500">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-100 font-bold">
                        <tr>
                            <td colspan="3" class="border border-slate-800 px-3 py-3 text-right uppercase tracking-wider">Total Mutasi</td>
                            <td class="border border-slate-800 px-3 py-3 text-right font-mono text-emerald-700">Rp {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</td>
                            <td class="border border-slate-800 px-3 py-3 text-right font-mono text-red-700">Rp {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td colspan="3" class="border border-slate-800 px-3 py-3 text-right uppercase tracking-wider font-black">Saldo Akhir Periode</td>
                            <td colspan="2" class="border border-slate-800 px-3 py-3 text-right font-mono font-black text-lg text-slate-900 bg-slate-50">
                                Rp {{ number_format(($totalMasuk ?? 0) - ($totalKeluar ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-16 flex justify-between px-8 text-[13px]">
                    <div class="text-center">
                        <p>Mengetahui,</p>
                        <p class="mb-20 font-bold">Ketua Majelis Jemaat</p>
                        <p class="uppercase font-bold underline">(..................................................)</p>
                    </div>
                    <div class="text-center">
                        <p>Batuputih, {{ date('d F Y') }}</p>
                        <p class="mb-20 font-bold">Bendahara Jemaat</p>
                        <p class="uppercase font-bold underline">(..................................................)</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    .animate-fade-in-down { animation: fadeInDown 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    @media print {
        @page { size: A4 portrait; margin: 1cm; }
        body { background: white !important; }
        nav, header, aside, .sidebar, .print\:hidden { display: none !important; }
        .max-w-7xl { width: 100% !important; max-width: none !important; margin: 0 !important; padding: 0 !important; }
        .lg\:col-span-8 { width: 100% !important; }
    }
</style>