<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warta Publik - EFATA TBN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; overflow: hidden; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="w-screen h-screen bg-gradient-to-br from-blue-900 via-blue-950 to-black text-white flex flex-col p-8 lg:p-12 relative overflow-hidden">
    
    <div class="flex justify-between items-center mb-8 border-b border-white/20 pb-4">
        <div class="flex items-center">
            <i class="fa-solid fa-church text-5xl lg:text-6xl text-yellow-400 mr-5"></i>
            <div>
                <h1 class="text-3xl lg:text-5xl font-black uppercase tracking-widest text-white shadow-black drop-shadow-md">Warta Efata TBN</h1>
                <p class="text-blue-300 font-bold tracking-wider text-lg mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p id="clockPub" class="text-6xl lg:text-7xl font-black text-white drop-shadow-lg">00:00</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 flex-1 overflow-hidden">
        
        <div class="lg:col-span-2 bg-white/10 backdrop-blur-md rounded-3xl border border-white/10 p-10 flex flex-col justify-center items-center text-center relative shadow-2xl">
            <span class="absolute top-8 left-8 bg-green-500 text-white text-sm font-black px-5 py-2 rounded-full uppercase tracking-tighter shadow-lg">Pesan Gembala Terkini</span>
            
            @if($pesanGembala)
                <h2 class="text-4xl lg:text-5xl font-black mb-8 leading-tight drop-shadow-md">{{ $pesanGembala->judul }}</h2>
                <p class="text-2xl text-blue-100 leading-relaxed max-w-4xl italic font-serif opacity-90">
                    "{{ $pesanGembala->konten }}"
                </p>
            @else
                <i class="fa-solid fa-book-bible text-8xl text-white/30 mb-6"></i>
                <p class="text-2xl text-blue-200/50 italic">Pesan Gembala belum tersedia untuk edisi ini.</p>
            @endif
        </div>

        <div class="space-y-6 overflow-y-auto hide-scrollbar pb-4 pr-2">
            
            <div class="bg-gradient-to-br from-pink-600 to-rose-700 rounded-3xl p-6 shadow-xl border border-pink-500/50">
                <div class="flex items-center mb-4 border-b border-white/20 pb-3">
                    <i class="fa-solid fa-cake-candles text-3xl text-yellow-300 mr-3 animate-pulse"></i>
                    <h3 class="text-xl font-black uppercase tracking-widest drop-shadow-md">HUT Jemaat Hari Ini</h3>
                </div>
                <div class="space-y-3">
                    @forelse($ultahHariIni as $jemaat)
                        @php 
                            $umur = \Carbon\Carbon::parse($jemaat->tgl_lahir)->age; 
                            $inisial = substr($jemaat->nama_lengkap, 0, 1);
                        @endphp
                        <div class="flex items-center bg-black/20 p-3 rounded-2xl">
                            <div class="w-12 h-12 rounded-full bg-white text-rose-600 flex items-center justify-center font-black text-xl mr-4 shadow-lg">{{ $inisial }}</div>
                            <div>
                                <p class="font-bold text-lg leading-tight">{{ $jemaat->nama_lengkap }}</p>
                                <p class="text-xs text-pink-200">Merayakan HUT ke-{{ $umur }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-pink-200 text-center italic py-2">Tidak ada jemaat yang berulang tahun hari ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-blue-600 rounded-3xl p-6 shadow-xl border border-blue-400/50">
                <div class="flex items-center mb-4 border-b border-white/20 pb-3">
                    <i class="fa-regular fa-calendar-check text-3xl text-blue-200 mr-3"></i>
                    <h3 class="text-xl font-black uppercase tracking-widest drop-shadow-md">Agenda Terdekat</h3>
                </div>
                @if($agendaTerdekat)
                    <p class="text-2xl font-black text-yellow-300 leading-tight">{{ $agendaTerdekat->nama_acara }}</p>
                    <p class="text-blue-100 text-base mt-2"><i class="fa-regular fa-clock mr-2"></i> {{ \Carbon\Carbon::parse($agendaTerdekat->tanggal_mulai)->translatedFormat('l, d M Y') }} {{ $agendaTerdekat->waktu ? '• '.\Carbon\Carbon::parse($agendaTerdekat->waktu)->format('H:i') : '' }}</p>
                @else
                    <p class="text-sm text-blue-200 italic py-2">Belum ada agenda ibadah terdekat.</p>
                @endif
            </div>

            @if($laporanKehadiran)
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-6 shadow-xl border border-emerald-500/50">
                <div class="flex items-center mb-4 border-b border-white/20 pb-3">
                    <i class="fa-solid fa-chart-pie text-3xl text-emerald-200 mr-3"></i>
                    <h3 class="text-lg font-black uppercase tracking-widest drop-shadow-md">Kehadiran Minggu Lalu</h3>
                </div>
                <div class="flex justify-between items-center bg-black/20 p-4 rounded-2xl mb-3">
                    <div class="text-center w-1/2">
                        <p class="text-emerald-200 text-[10px] uppercase font-bold tracking-widest mb-1">Laki-laki</p>
                        <p class="text-3xl font-black text-white">{{ $laporanKehadiran->hadir_laki }}</p>
                    </div>
                    <div class="w-px h-12 bg-white/20"></div>
                    <div class="text-center w-1/2">
                        <p class="text-emerald-200 text-[10px] uppercase font-bold tracking-widest mb-1">Perempuan</p>
                        <p class="text-3xl font-black text-white">{{ $laporanKehadiran->hadir_perempuan }}</p>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-sm text-white/80">Total Kehadiran: <span class="font-black text-white text-xl ml-1">{{ $laporanKehadiran->hadir_laki + $laporanKehadiran->hadir_perempuan }}</span> Jiwa</p>
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        // Jam Digital Real-time
        setInterval(() => {
            const now = new Date();
            document.getElementById('clockPub').innerText = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        }, 1000);
    </script>
</body>
</html>