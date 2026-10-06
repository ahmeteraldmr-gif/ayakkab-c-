<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Yönetim Paneli | Yusuf Akboğa Ayakkabı')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS (Play CDN with Modern SaaS Dashboard Palette) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#2563EB',
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            200: '#BFDBFE',
                            500: '#3B82F6',
                            600: '#2563EB',
                            700: '#1D4ED8',
                            800: '#1E40AF',
                        },
                        sidebar: {
                            DEFAULT: '#111827',
                            hover: '#1F2937',
                            border: '#1F2937',
                            text: '#9CA3AF',
                        },
                        canvas: '#F6F8FC',
                        surface: '#FFFFFF',
                        border: '#E5E7EB',
                        textPrimary: '#111827',
                        textSecondary: '#6B7280',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: #F6F8FC;
            color: #111827;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
        @media screen and (max-width: 640px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
        /* Clean Light Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col md:flex-row bg-[#F6F8FC] text-[#111827]">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleAdminSidebar(false)" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden md:hidden"></div>

    <!-- Dark Slate SaaS Sidebar Navigation (#111827) -->
    <aside id="adminSidebar" class="fixed md:sticky md:top-0 inset-y-0 left-0 z-50 w-64 bg-[#111827] border-r border-[#1F2937] flex flex-col justify-between flex-shrink-0 h-screen max-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex flex-col flex-grow overflow-y-auto min-h-0">
            
            <!-- Admin Brand / Logo & Mobile Close -->
            <div class="p-5 border-b border-[#1F2937] flex items-center justify-between flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col group">
                    <span class="font-sans font-extrabold text-base tracking-tight text-white group-hover:text-blue-400 transition-colors">
                        YUSUF AKBOĞA
                    </span>
                    <span class="text-[10px] tracking-[0.25em] font-semibold text-blue-400 uppercase mt-0.5">
                        Yönetim Paneli
                    </span>
                </a>
                <button type="button" onclick="toggleAdminSidebar(false)" class="md:hidden text-gray-400 hover:text-white text-lg p-1" aria-label="Menüyü Kapat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1 text-sm flex-grow">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Dashboard</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-shoe-prints w-5 text-center text-sm {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Ürünler</span>
                </a>

                <a href="{{ route('admin.stocks.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.stocks.index') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm {{ request()->routeIs('admin.stocks.index') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Stok Yönetimi</span>
                </a>

                <a href="{{ route('admin.stocks.movements') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.stocks.movements') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center text-sm {{ request()->routeIs('admin.stocks.movements') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Stok Hareketleri</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-bag-shopping w-5 text-center text-sm {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Siparişler</span>
                    @if(isset($adminNewOrdersCount) && $adminNewOrdersCount > 0)
                        <span class="ml-auto px-1.5 py-0.5 rounded-md bg-blue-500 text-white font-bold text-[10px]">{{ $adminNewOrdersCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-ticket w-5 text-center text-sm {{ request()->routeIs('admin.coupons.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Kuponlar</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Kategoriler</span>
                </a>

                <a href="{{ route('admin.brands.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.brands.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5 text-center text-sm {{ request()->routeIs('admin.brands.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Markalar</span>
                </a>

                <a href="{{ route('admin.campaigns.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.campaigns.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-bullhorn w-5 text-center text-sm {{ request()->routeIs('admin.campaigns.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Kampanyalar</span>
                </a>

                <a href="{{ route('admin.messages.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.messages.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-envelope w-5 text-center text-sm {{ request()->routeIs('admin.messages.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Mesajlar</span>
                    @if(isset($adminUnreadMessagesCount) && $adminUnreadMessagesCount > 0)
                        <span class="ml-auto px-1.5 py-0.5 rounded-md bg-amber-500 text-white font-bold text-[10px]">{{ $adminUnreadMessagesCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-[#2563EB] text-white font-semibold shadow-sm' : 'text-gray-400 hover:bg-[#1F2937] hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center text-sm {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span class="text-[13px]">Site Ayarları</span>
                </a>
            </nav>
        </div>

        <!-- User Profile / Bottom Actions (Always pinned to bottom) -->
        <div class="p-3.5 border-t border-[#1F2937] space-y-2.5 bg-[#0B0F19] flex-shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center space-x-2 w-full py-2 px-3 bg-[#1F2937] hover:bg-[#374151] text-gray-200 hover:text-white rounded-xl text-xs font-semibold transition-colors border border-gray-700/50">
                <i class="fa-solid fa-arrow-up-right-from-square text-blue-400 text-xs"></i>
                <span>Siteyi Görüntüle</span>
            </a>

            <div class="flex items-center justify-between pt-1 px-1">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-sm flex-shrink-0">
                        YA
                    </div>
                    <div class="text-xs min-w-0 truncate">
                        <p class="font-bold text-white text-[12.5px] truncate">{{ auth()->user()->name ?? 'Yusuf Akboğa' }}</p>
                        <p class="text-gray-400 text-[10.5px]">Yönetici</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-lg transition-colors flex items-center gap-1.5 text-xs font-semibold" title="Çıkış Yap">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        <span class="hidden xl:inline">Çıkış</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navbar (Clean White Header with Subtle Border) -->
        <header class="h-16 bg-white border-b border-[#E5E7EB] px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-[0_1px_2px_rgba(0,0,0,0.03)]">
            <div class="flex items-center space-x-3">
                <button type="button" onclick="toggleAdminSidebar(true)" class="md:hidden p-2 rounded-lg bg-gray-100 text-gray-700 hover:text-black focus:outline-none" aria-label="Menüyü Aç">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
                <h2 class="font-sans font-bold text-base sm:text-lg text-[#111827]">@yield('page_title', 'Dashboard')</h2>
            </div>
            
            <div class="flex items-center space-x-2 sm:space-x-3">
                
                <!-- Notification Center Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" onclick="toggleNotificationDropdown()" class="relative p-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-600 hover:text-gray-900 border border-gray-200 transition-colors" title="Bildirim Merkezi">
                        <i class="fa-regular fa-bell text-sm"></i>
                        @if(isset($adminTotalNotifications) && $adminTotalNotifications > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-600 text-white font-bold text-[10px] flex items-center justify-center animate-pulse">
                                {{ $adminTotalNotifications > 9 ? '9+' : $adminTotalNotifications }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Content -->
                    <div id="adminNotificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 py-2 divide-y divide-gray-100">
                        <div class="p-3 font-bold text-xs text-gray-800 flex items-center justify-between">
                            <span>Bildirim Merkezi</span>
                            <span class="text-[11px] text-blue-600 font-normal">{{ $adminTotalNotifications ?? 0 }} yeni durum</span>
                        </div>
                        <div class="py-1 text-xs max-h-64 overflow-y-auto">
                            @if(isset($adminNewOrdersCount) && $adminNewOrdersCount > 0)
                                <a href="{{ route('admin.orders.index', ['status' => 'yeni']) }}" class="flex items-center space-x-3 px-3.5 py-2.5 hover:bg-gray-50 text-gray-700">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $adminNewOrdersCount }} Yeni Sipariş</p>
                                        <p class="text-[11px] text-gray-500">İşlem bekleyen yeni siparişler var.</p>
                                    </div>
                                </a>
                            @endif

                            @if(isset($adminUnreadMessagesCount) && $adminUnreadMessagesCount > 0)
                                <a href="{{ route('admin.messages.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 hover:bg-gray-50 text-gray-700">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $adminUnreadMessagesCount }} Okunmamış Mesaj</p>
                                        <p class="text-[11px] text-gray-500">Müşterilerden gelen yeni mesajlar.</p>
                                    </div>
                                </a>
                            @endif

                            @if(isset($adminLowStockCount) && $adminLowStockCount > 0)
                                <a href="{{ route('admin.stocks.index', ['stock_status' => 'low']) }}" class="flex items-center space-x-3 px-3.5 py-2.5 hover:bg-gray-50 text-gray-700">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $adminLowStockCount }} Kritik / Tükenen Stok</p>
                                        <p class="text-[11px] text-gray-500">Stoğu kritik veya bitmiş numaralar.</p>
                                    </div>
                                </a>
                            @endif

                            @if(isset($adminPendingAlertsCount) && $adminPendingAlertsCount > 0)
                                <a href="{{ route('admin.stocks.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 hover:bg-gray-50 text-gray-700">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $adminPendingAlertsCount }} Stok Talebi</p>
                                        <p class="text-[11px] text-gray-500">Stoğa girince haber ver bekleyenleri.</p>
                                    </div>
                                </a>
                            @endif

                            @if(!isset($adminTotalNotifications) || $adminTotalNotifications == 0)
                                <div class="p-6 text-center text-gray-400">
                                    <i class="fa-solid fa-check-double text-emerald-500 text-lg mb-1 block"></i>
                                    <span>Tüm işlemler güncel, bekleyen bildirim yok.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors" title="Siteyi Görüntüle">
                    <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-xs"></i>
                    <span>Siteyi Gör</span>
                </a>

                <form method="POST" action="{{ route('admin.logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 text-xs font-semibold rounded-lg transition-colors" title="Güvenli Çıkış Yap">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span>Çıkış Yap</span>
                    </button>
                </form>

                @yield('header_actions')
            </div>
        </header>

        <!-- Main Body (Light SaaS Canvas #F6F8FC) -->
        <main class="p-4 sm:p-6 md:p-8 flex-grow">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span class="font-medium text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                        <span class="font-medium text-xs sm:text-sm">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-xs">
                    <div class="flex items-center space-x-2 mb-2 text-red-700 font-bold text-xs sm:text-sm">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Lütfen formdaki hataları kontrol ediniz:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-red-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Admin Universal Confirmation Modal -->
    <div id="adminConfirmModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-200 text-center space-y-4" onclick="event.stopPropagation()">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl border border-red-100">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 id="confirmModalTitle" class="font-sans font-bold text-lg text-gray-900">İşlemi Onaylayın</h3>
                <p id="confirmModalMessage" class="text-xs sm:text-sm text-gray-500 mt-1">Bu kaydı silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeConfirmModal()" class="w-1/2 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition-colors">
                    Vazgeç
                </button>
                <button type="button" id="confirmModalApproveBtn" class="w-1/2 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-colors">
                    Evet, Sil
                </button>
            </div>
        </div>
    </div>

    <!-- Admin Toast Container -->
    <div id="adminToastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col space-y-2 pointer-events-none"></div>

    <script>
        function toggleAdminSidebar(open) {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;
            if (open) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Confirmation Modal Logic
        let pendingConfirmCallback = null;

        function openConfirmModal(title, message, onConfirm) {
            document.getElementById('confirmModalTitle').innerText = title || 'Silme İşlemini Onayla';
            document.getElementById('confirmModalMessage').innerText = message || 'Bu kaydı silmek istediğinize emin misiniz?';
            pendingConfirmCallback = onConfirm;
            const modal = document.getElementById('adminConfirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmModal() {
            const modal = document.getElementById('adminConfirmModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingConfirmCallback = null;
        }

        document.getElementById('confirmModalApproveBtn')?.addEventListener('click', function() {
            if (typeof pendingConfirmCallback === 'function') {
                pendingConfirmCallback();
            }
            closeConfirmModal();
        });

        // Intercept delete forms across admin
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const methodInput = form.querySelector('input[name="_method"]');
            const isDelete = methodInput && methodInput.value.toUpperCase() === 'DELETE';
            
            if (isDelete && !form.dataset.confirmed) {
                e.preventDefault();
                const customMsg = form.dataset.confirmMessage || 'Bu kaydı kalıcı olarak silmek istediğinize emin misiniz?';
                openConfirmModal('Silme İşlemini Onayla', customMsg, function() {
                    form.dataset.confirmed = 'true';
                    form.submit();
                });
            }
        });

        // Toast Notification System for Admin
        function showAdminToast(message, type = 'success') {
            const container = document.getElementById('adminToastContainer');
            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-emerald-600 text-white' : (type === 'error' ? 'bg-red-600 text-white' : 'bg-gray-800 text-white');
            toast.className = `pointer-events-auto flex items-center p-3.5 rounded-xl shadow-lg text-xs font-semibold ${bgClass} transition-all duration-300 opacity-0 translate-y-2`;
            toast.innerHTML = `<span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('opacity-0', 'translate-y-2'), 10);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('adminNotificationDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('adminNotificationDropdown');
            const bellBtn = e.target.closest('button[title="Bildirim Merkezi"]');
            if (dropdown && !dropdown.classList.contains('hidden') && !dropdown.contains(e.target) && !bellBtn) {
                dropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                toggleAdminSidebar(false);
                closeConfirmModal();
                const dropdown = document.getElementById('adminNotificationDropdown');
                if (dropdown) dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
