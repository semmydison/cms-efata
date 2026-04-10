<!DOCTYPE html>
<html lang="id" id="theme-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS EFATA TBN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        sepia: { 50: '#fbf0d9', 100: '#f3e5ab', 800: '#5f4b32', 900: '#433422' }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; transition: all 0.3s ease; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .sepia-mode { background-color: #fbf0d9; color: #433422; }
        .sepia-mode .bg-card { background-color: #f3e5ab; border-color: #d4c48a; }
        .sepia-mode aside { background-color: #5f4b32; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex h-screen overflow-hidden">

    <aside class="w-64 bg-blue-900 text-white flex flex-col hidden md:flex transition-all relative">
        <div class="h-16 flex items-center justify-center border-b border-blue-800">
            <h1 class="text-xl font-bold uppercase tracking-wider"><i class="fa-solid fa-church mr-2 text-yellow-400"></i> EFATA TBN</h1>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto hide-scrollbar">
            <a href="{{ url('/') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('/') || request()->is('dashboard') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-gauge-high w-6"></i> Dashboard
            </a>
            
            <div class="pt-4 pb-1 text-xs text-blue-300 uppercase font-semibold">Manajemen Jemaat</div>
            <a href="{{ url('/jemaat') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('jemaat*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-users w-6"></i> Data Jemaat
            </a>
            <a href="{{ url('/wilayah') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('wilayah*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-map-location-dot w-6"></i> Wilayah & Rayon
            </a>
            
            <div class="pt-4 pb-1 text-xs text-blue-300 uppercase font-semibold">Keuangan</div>
            <a href="{{ url('/keuangan') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('keuangan*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-wallet w-6"></i> Transaksi Kas
            </a>            
            
            <div class="pt-4 pb-1 text-xs text-blue-300 uppercase font-semibold">Media & Ibadah</div>
            <a href="{{ url('/liturgi') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('liturgi*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-file-powerpoint w-6"></i> Liturgi / Slide
            </a>
            <a href="{{ url('/kalender') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('kalender*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-calendar-days w-6"></i> Kalender Acara
            </a>
            <a href="{{ url('/warta') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('warta*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-newspaper w-6"></i> Warta Jemaat
            </a>

            <div class="pt-4 pb-1 text-xs text-blue-300 uppercase font-semibold">Pengaturan Sistem</div>
            <a href="#" onclick="alert('Modul Manajemen Pengguna belum diimplementasikan.')" class="flex items-center px-4 py-2 hover:bg-blue-800 rounded-lg opacity-70 text-white transition-colors">
                <i class="fa-solid fa-users-gear w-6"></i> Manajemen Pengguna
            </a>
            <a href="{{ url('/profil-gereja') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->is('profil-gereja*') ? 'bg-blue-700 text-white font-bold shadow-lg' : 'hover:bg-blue-800 opacity-70 text-white' }}">
                <i class="fa-solid fa-place-of-worship w-6"></i> Profil Gereja
            </a>
        </nav>

        <div class="p-4 border-t border-blue-800">
            <a href="#" onclick="alert('Modul Pengaturan Akun belum diimplementasikan.')" class="flex items-center px-4 py-2 hover:bg-blue-700 rounded-lg text-white transition-colors">
                <i class="fa-solid fa-user-gear w-6"></i> Pengaturan Akun
            </a>
            <a href="#" onclick="alert('Fitur Logout akan aktif setelah sistem Autentikasi dipasang.')" class="flex items-center px-4 py-2 mt-2 text-red-300 hover:text-white hover:bg-red-600 rounded-lg transition-colors font-medium">
                <i class="fa-solid fa-arrow-right-from-bracket w-6"></i> Keluar (Logout)
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-6 z-40 border-b dark:border-gray-700 relative">
            <div class="flex items-center space-x-4">
                <h2 class="font-bold text-gray-700 dark:text-white hidden sm:block">@yield('title', 'CMS EFATA')</h2>
                <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-full p-1 border dark:border-gray-600 hidden md:flex">
                    <button onclick="changeTheme('light')" class="w-8 h-8 rounded-full flex items-center justify-center text-yellow-500 hover:bg-white transition shadow-sm"><i class="fa-solid fa-sun"></i></button>
                    <button onclick="changeTheme('sepia')" class="w-8 h-8 rounded-full flex items-center justify-center text-sepia-800 hover:bg-sepia-100 transition"><i class="fa-solid fa-book-open"></i></button>
                    <button onclick="changeTheme('dark')" class="w-8 h-8 rounded-full flex items-center justify-center text-blue-400 hover:bg-gray-900 transition"><i class="fa-solid fa-moon"></i></button>
                </div>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-4">
                
                <div class="relative">
                    <button onclick="toggleDropdown(event, 'notifDropdown')" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 p-2 focus:outline-none transition-colors relative">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                    </button>
                    
                    <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                        <div class="px-4 py-3 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Notifikasi</h3>
                            <button class="text-[10px] text-blue-600 font-bold uppercase tracking-wider hover:underline">Tandai Dibaca</button>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <div class="p-4 text-center text-sm text-gray-500">Belum ada notifikasi baru.</div>
                        </div>
                        <div class="px-4 py-2 text-center bg-gray-50 dark:bg-gray-800 border-t dark:border-gray-700">
                            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua Riwayat</a>
                        </div>
                    </div>
                </div>

                <div class="relative border-l border-gray-200 dark:border-gray-700 pl-2 sm:pl-4">
                    <button onclick="toggleDropdown(event, 'profileDropdown')" class="flex items-center space-x-2 focus:outline-none group p-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-sm group-hover:ring-2 ring-blue-300 transition-all">A</div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-200 leading-none">Admin Utama</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Superadmin</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-blue-600 hidden sm:block ml-1"></i>
                    </button>

                    <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                        <div class="px-4 py-3 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-center">
                            <div class="w-12 h-12 mx-auto rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xl mb-2">A</div>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">Admin Utama</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">admin@efatatbn.org</p>
                        </div>
                        <div class="py-2">
                            <a href="#" onclick="alert('Modul Pengaturan Akun belum diimplementasikan.')" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 transition">
                                <i class="fa-solid fa-user-gear w-5"></i> Profil & Akun
                            </a>
                        </div>
                        <div class="py-2 border-t dark:border-gray-700 text-center">
                            <a href="#" onclick="alert('Fitur Logout akan aktif setelah sistem Autentikasi dipasang.')" class="flex items-center justify-center px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar (Logout)
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-4 md:p-6 transition-all">
            @yield('content') 
        </main>
    </div>

    <script>
        function toggleDropdown(event, id) {
            event.stopPropagation();
            const dropdown = document.getElementById(id);
            const isHidden = dropdown.classList.contains('hidden');
            
            // Tutup semua dropdown yang sedang terbuka
            document.getElementById('notifDropdown')?.classList.add('hidden');
            document.getElementById('profileDropdown')?.classList.add('hidden');
            
            if (isHidden) dropdown.classList.remove('hidden');
        }

        // Klik di luar dropdown untuk menutup
        window.onclick = function(event) {
            if (!event.target.closest('.relative')) {
                document.getElementById('notifDropdown')?.classList.add('hidden');
                document.getElementById('profileDropdown')?.classList.add('hidden');
            }
        }

        const root = document.getElementById('theme-root');
        function changeTheme(mode) {
            root.classList.remove('dark', 'sepia-mode');
            if (mode === 'dark') root.classList.add('dark');
            else if (mode === 'sepia') root.classList.add('sepia-mode');
            localStorage.setItem('church_cms_theme', mode);
        }
        
        window.onload = () => {
            const saved = localStorage.getItem('church_cms_theme');
            if (saved) changeTheme(saved);
        }
    </script>
    
    @stack('scripts')
</body>
</html>