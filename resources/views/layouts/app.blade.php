<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\Setting::get('site_title', 'Yusuf Akboğa Ayakkabı | Modern & Premium Ayakkabı Mağazası'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Yusuf Akboğa kalitesiyle en yeni ve tarz ayakkabı modellerini keşfedin.'))">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="theme-color" content="#111111">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', \App\Models\Setting::get('site_title', 'Yusuf Akboğa Ayakkabı'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'En yeni ve tarz ayakkabı modelleri.'))">
    <meta property="og:image" content="@yield('og_image', asset('favicon.ico'))">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', \App\Models\Setting::get('site_title', 'Yusuf Akboğa Ayakkabı'))">
    <meta name="twitter:description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'En yeni ve tarz ayakkabı modelleri.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('favicon.ico'))">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon', '/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::get('site_favicon', '/favicon.ico') }}">
    
    <!-- Google Fonts (Montserrat for headings, Inter & Manrope for body and UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS (Play CDN with custom brand palette) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            DEFAULT: '#111111',
                            50: '#2a2a2a',
                            100: '#222222',
                            200: '#1c1c1c',
                            300: '#161616',
                            900: '#111111',
                        },
                        anthracite: '#292929',
                        cream: '#F7F7F5',
                        lightgray: '#E9E9E9',
                        accent: {
                            DEFAULT: '#C79A58',
                            light: '#D9B277',
                            dark: '#A67C3B',
                            50: '#FBF7F0',
                            100: '#F6EEDB',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Manrope', 'sans-serif'],
                        display: ['Montserrat', 'Manrope', 'sans-serif'],
                        hero: ['Montserrat', 'sans-serif'],
                        body: ['Inter', 'Manrope', 'sans-serif'],
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
            font-family: 'Inter', 'Manrope', sans-serif;
            background-color: #F7F7F5;
            color: #111111;
            letter-spacing: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
        /* iOS Safari Input Auto-zoom Prevention (Ensures 16px font size on small screens) */
        @media screen and (max-width: 640px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
        .font-display {
            font-family: 'Montserrat', 'Manrope', sans-serif;
            font-stretch: normal !important;
            transform: none !important;
        }
        .hero-main-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.025em;
            font-stretch: normal !important;
            transform: none !important;
            font-size: clamp(34px, 7.5vw, 84px);
        }
        .hero-description {
            font-family: 'Inter', 'Manrope', sans-serif;
            font-size: clamp(14px, 2.2vw, 17.5px);
            line-height: 1.65;
            letter-spacing: 0;
            font-stretch: normal !important;
            transform: none !important;
        }
        .nav-link-item {
            font-family: 'Inter', 'Manrope', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.1px;
            font-stretch: normal !important;
            transform: none !important;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #F7F7F5;
        }
        ::-webkit-scrollbar-thumb {
            background: #C79A58;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #A67C3B;
        }
        /* Glassmorphism Header */
        .glass-header {
            background: rgba(17, 17, 17, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        /* Zoom & Hover */
        .product-card-img {
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .group:hover .product-card-img {
            transform: scale(1.06);
        }
        /* Badge Pulse */
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.02); }
        }
        .pulse-badge {
            animation: pulse-subtle 3s infinite;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen selection:bg-accent selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-dark text-white text-[12px] sm:text-[13px] py-2 px-3 sm:px-4 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2">
            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                <span class="inline-flex items-center text-accent font-semibold text-xs sm:text-[13px] truncate">
                    <i class="fa-solid fa-truck-fast mr-1.5 sm:mr-2 text-xs"></i> {{ \App\Models\Setting::get('free_shipping_threshold', 1500) }} TL Üzeri Ücretsiz Kargo
                </span>
                <span class="hidden md:inline-block text-white/40">•</span>
                <span class="hidden md:inline-block text-white/80 text-xs sm:text-[13px]">
                    <i class="fa-solid fa-shield-halved mr-1.5 text-accent"></i> %100 Orijinal & Değişim Garantisi
                </span>
            </div>
            <div class="flex items-center space-x-3 sm:space-x-5 text-white/90 text-xs sm:text-[13px] flex-shrink-0">
                <a href="tel:{{ \App\Models\Setting::get('site_phone', '+90 (216) 450 10 20') }}" class="hover:text-accent transition-colors flex items-center">
                    <i class="fa-solid fa-phone mr-1.5 text-accent"></i> <span class="hidden sm:inline">{{ \App\Models\Setting::get('site_phone', '+90 (216) 450 10 20') }}</span><span class="sm:hidden">Ara</span>
                </a>
                <a href="{{ route('size.guide') }}" class="hover:text-accent transition-colors hidden sm:inline-flex items-center">
                    <i class="fa-solid fa-ruler-combined mr-1.5 text-accent"></i> Numara Rehberi
                </a>
                <a href="{{ route('admin.login') }}" class="text-white/40 hover:text-accent transition-colors p-1" title="Yönetici Girişi">
                    <i class="fa-solid fa-lock text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header id="mainHeader" class="sticky top-0 z-40 bg-dark text-white transition-all duration-300 shadow-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex flex-col group py-1">
                    <span class="font-display font-extrabold text-lg sm:text-2xl tracking-tight text-white group-hover:text-accent transition-colors">
                        YUSUF AKBOĞA
                    </span>
                    <span class="text-[9px] sm:text-[11px] tracking-[0.2em] font-semibold text-accent uppercase -mt-0.5 sm:mt-0">
                        Ayakkabı / Footwear
                    </span>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="{{ route('home') }}" class="nav-link-item {{ request()->routeIs('home') ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        Ana Sayfa
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link-item {{ request()->routeIs('products.index') && !request('gender') ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        Tüm Ürünler
                    </a>
                    <a href="{{ route('products.index', ['gender' => 'erkek']) }}" class="nav-link-item {{ request('gender') === 'erkek' ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        Erkek
                    </a>
                    <a href="{{ route('products.index', ['gender' => 'kadin']) }}" class="nav-link-item {{ request('gender') === 'kadin' ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        Kadın
                    </a>
                    <a href="{{ route('order.track.index') }}" class="nav-link-item {{ request()->routeIs('order.track.*') ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        Sipariş Takip
                    </a>
                    <a href="{{ route('about') }}" class="nav-link-item {{ request()->routeIs('about') ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        Hakkımızda
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link-item {{ request()->routeIs('contact') ? 'text-accent' : 'text-white/90 hover:text-accent' }} transition-colors">
                        İletişim
                    </a>
                </nav>

                <!-- Header Actions (Search, Favorites, User Account, Cart, Mobile Toggle) -->
                <div class="flex items-center space-x-1.5 sm:space-x-3">
                    
                    <!-- Search Icon / Trigger -->
                    <button type="button" onclick="toggleSearchModal(true)" class="w-10 h-10 flex items-center justify-center text-white/80 hover:text-accent transition-colors rounded-full hover:bg-white/5" title="Ürün Ara">
                        <i class="fa-solid fa-magnifying-glass text-base sm:text-lg"></i>
                    </button>

                    <!-- Favorites Trigger -->
                    <button type="button" onclick="openFavoritesModal()" class="relative w-10 h-10 flex items-center justify-center text-white/80 hover:text-accent transition-colors rounded-full hover:bg-white/5" title="Favorilerim">
                        <i class="fa-regular fa-heart text-base sm:text-lg"></i>
                        <span id="headerFavBadge" class="absolute top-1 right-1 w-4 h-4 bg-accent text-dark text-[10px] font-bold rounded-full flex items-center justify-center hidden">0</span>
                    </button>

                    <!-- User Account / Login Dropdown -->
                    @auth
                        <div class="relative group">
                            <button type="button" class="w-10 h-10 flex items-center justify-center text-white/90 hover:text-accent transition-colors rounded-full hover:bg-white/5 border border-white/10" title="Hesabım">
                                <i class="fa-solid fa-user-check text-sm text-accent"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-52 bg-[#161616] border border-white/10 rounded-xl shadow-2xl py-2 hidden group-hover:block transition-all duration-200 z-50">
                                <div class="px-4 py-2 border-b border-white/10">
                                    <p class="text-xs text-white/50">Giriş yapıldı</p>
                                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                                </div>
                                <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2.5 text-xs text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                                    <i class="fa-solid fa-gauge w-4 mr-2.5 text-accent"></i> Hesap Özeti
                                </a>
                                <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-2.5 text-xs text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                                    <i class="fa-solid fa-box-open w-4 mr-2.5 text-accent"></i> Siparişlerim
                                </a>
                                <a href="{{ route('customer.addresses') }}" class="flex items-center px-4 py-2.5 text-xs text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                                    <i class="fa-solid fa-location-dot w-4 mr-2.5 text-accent"></i> Kayıtlı Adreslerim
                                </a>
                                <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-2.5 text-xs text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                                    <i class="fa-solid fa-user-pen w-4 mr-2.5 text-accent"></i> Profil Bilgilerim
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <div class="border-t border-white/10 my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-xs text-amber-400 hover:bg-white/5 transition-colors">
                                        <i class="fa-solid fa-shield-halved w-4 mr-2.5"></i> Admin Paneli
                                    </a>
                                @endif
                                <div class="border-t border-white/10 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-xs text-rose-400 hover:bg-white/5 transition-colors text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4 mr-2.5"></i> Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="w-10 h-10 flex items-center justify-center text-white/80 hover:text-accent transition-colors rounded-full hover:bg-white/5" title="Giriş Yap / Kayıt Ol">
                            <i class="fa-regular fa-user text-base sm:text-lg"></i>
                        </a>
                    @endauth

                    <!-- Cart Trigger (Min 44px touch target) -->
                    <a href="{{ route('cart.index') }}" class="relative w-10 h-10 sm:w-11 sm:h-11 bg-accent/10 hover:bg-accent text-accent hover:text-dark transition-all duration-300 rounded-full flex items-center justify-center border border-accent/30" title="Sepetim">
                        <i class="fa-solid fa-bag-shopping text-sm sm:text-base"></i>
                        <span id="headerCartCount" class="absolute -top-1 -right-1 min-w-[18px] sm:min-w-[20px] h-4 sm:h-5 bg-accent text-dark text-[10px] sm:text-[11px] font-extrabold rounded-full px-1 flex items-center justify-center shadow-md border-2 border-dark">
                            {{ $globalCart['count'] ?? 0 }}
                        </span>
                    </a>

                    <!-- Mobile Hamburger Button (Min 44px touch target) -->
                    <button id="mobileMenuBtn" type="button" onclick="toggleMobileMenu(true)" class="md:hidden w-10 h-10 flex items-center justify-center text-white/90 hover:text-accent rounded-xl hover:bg-white/5 focus:outline-none" aria-label="Menüyü Aç">
                        <i class="fa-solid fa-bars-staggered text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Slide-Over Backdrop -->
    <div id="mobileMenuBackdrop" onclick="toggleMobileMenu(false)" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-xs hidden transition-opacity duration-300"></div>

    <!-- Mobile Slide-Over Navigation Drawer -->
    <aside id="mobileDrawer" class="fixed inset-y-0 right-0 z-50 w-full max-w-xs bg-[#161616] text-white transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between shadow-2xl border-l border-white/10 md:hidden">
        
        <!-- Drawer Header -->
        <div class="p-5 border-b border-white/10 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="font-display font-bold text-lg tracking-tight text-white">YUSUF AKBOĞA</span>
                <span class="text-[10px] tracking-[0.2em] font-semibold text-accent uppercase">Ayakkabı / Menü</span>
            </div>
            <button type="button" onclick="toggleMobileMenu(false)" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 text-white/80 hover:text-white flex items-center justify-center transition-colors" aria-label="Menüyü Kapat">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Drawer Nav Links -->
        <nav class="flex-grow overflow-y-auto p-5 space-y-2">
            <a href="{{ route('home') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request()->routeIs('home') ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-house mr-3 text-accent w-5 text-center"></i> Ana Sayfa</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('products.index') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request()->routeIs('products.index') && !request('gender') ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-shoe-prints mr-3 text-accent w-5 text-center"></i> Tüm Ürünler</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('products.index', ['gender' => 'erkek']) }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request('gender') === 'erkek' ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-mars mr-3 text-accent w-5 text-center"></i> Erkek Ayakkabı</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('products.index', ['gender' => 'kadin']) }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request('gender') === 'kadin' ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-venus mr-3 text-accent w-5 text-center"></i> Kadın Ayakkabı</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('products.index', ['discounted' => 1]) }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl text-rose-400 hover:bg-white/5 transition-colors">
                <span class="flex items-center text-sm font-semibold"><i class="fa-solid fa-tags mr-3 text-rose-400 w-5 text-center"></i> Fırsat & İndirim</span>
                <span class="text-[10px] font-bold bg-rose-500/20 text-rose-300 px-2 py-0.5 rounded-md">İndirim</span>
            </a>

            <a href="{{ route('size.guide') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-ruler-combined mr-3 text-accent w-5 text-center"></i> Numara Rehberi</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('order.track.index') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request()->routeIs('order.track.*') ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-truck-fast mr-3 text-accent w-5 text-center"></i> Sipariş Takip</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('about') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request()->routeIs('about') ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-store mr-3 text-accent w-5 text-center"></i> Hakkımızda</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('contact') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl {{ request()->routeIs('contact') ? 'bg-accent/15 text-accent font-bold' : 'text-white/90 hover:bg-white/5' }} transition-colors">
                <span class="flex items-center text-sm"><i class="fa-solid fa-envelope mr-3 text-accent w-5 text-center"></i> İletişim</span>
                <i class="fa-solid fa-chevron-right text-xs opacity-50"></i>
            </a>

            <div class="border-t border-white/10 my-2 pt-2">
                @auth
                    <a href="{{ route('customer.dashboard') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl bg-accent/10 text-accent font-semibold mb-1">
                        <span class="flex items-center text-sm"><i class="fa-solid fa-user-circle mr-3 text-accent w-5 text-center"></i> {{ auth()->user()->name }} (Hesabım)</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-xl text-rose-400 hover:bg-white/5 text-sm text-left">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-3 text-rose-400 w-5 text-center"></i> Çıkış Yap
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" onclick="toggleMobileMenu(false)" class="flex items-center justify-between px-4 py-3 rounded-xl bg-accent/15 text-accent font-semibold">
                        <span class="flex items-center text-sm"><i class="fa-solid fa-user-lock mr-3 text-accent w-5 text-center"></i> Müşteri Girişi / Kayıt Ol</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @endauth
            </div>
        </nav>

        <!-- Drawer Footer Contact & Actions -->
        <div class="p-5 border-t border-white/10 bg-[#111111] space-y-3">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '905321234567')) }}" target="_blank" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center shadow-lg transition-colors">
                <i class="fa-brands fa-whatsapp mr-2 text-base"></i> WhatsApp ile Destek Al
            </a>
            <div class="flex items-center justify-between text-xs text-white/60 pt-1">
                <a href="tel:{{ \App\Models\Setting::get('site_phone', '+90 216 450 10 20') }}" class="hover:text-accent flex items-center">
                    <i class="fa-solid fa-phone mr-1.5 text-accent"></i> {{ \App\Models\Setting::get('site_phone', '+90 216 450 10 20') }}
                </a>
                <a href="{{ route('admin.login') }}" class="text-white/40 hover:text-accent text-[11px]">
                    Admin Girişi
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl"></i>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8 border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-white/10">
                
                <!-- Brand Info -->
                <div class="lg:col-span-2 space-y-4">
                    <a href="{{ route('home') }}" class="flex flex-col">
                        <span class="font-display font-bold text-2xl tracking-tight text-white">YUSUF AKBOĞA</span>
                        <span class="text-[11px] tracking-[0.25em] font-semibold text-accent uppercase">Ayakkabı / Footwear</span>
                    </a>
                    <p class="text-accent font-medium text-sm italic">
                        “Herkes yürür, tarzını sen belirlersin.”
                    </p>
                    <p class="text-white/60 text-sm leading-relaxed max-w-sm">
                        {{ \App\Models\Setting::get('about_mini', 'Yusuf Akboğa Ayakkabı, adımlarınıza prestij ve konfor katmak amacıyla kurulmuş seçkin bir ayakkabı mağazasıdır.') }}
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <a href="{{ \App\Models\Setting::get('site_instagram', 'https://instagram.com') }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 hover:bg-accent hover:text-dark text-white/80 transition-all flex items-center justify-center border border-white/10">
                            <i class="fa-brands fa-instagram text-base"></i>
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '905321234567')) }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 hover:bg-emerald-500 hover:text-white text-white/80 transition-all flex items-center justify-center border border-white/10">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </a>
                        <a href="tel:{{ \App\Models\Setting::get('site_phone', '+90 216 450 10 20') }}" class="w-10 h-10 rounded-full bg-white/5 hover:bg-accent hover:text-dark text-white/80 transition-all flex items-center justify-center border border-white/10">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h4 class="font-display font-bold text-sm tracking-wider uppercase text-white border-b border-white/10 pb-2">
                        Hızlı Linkler
                    </h4>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        <li><a href="{{ route('home') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> Ana Sayfa</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> Tüm Modeller</a></li>
                        <li><a href="{{ route('order.track.index') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> Sipariş Takip</a></li>
                        <li><a href="{{ route('products.index', ['discounted' => 1]) }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> İndirimli Ürünler</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> Hakkımızda</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> İletişim</a></li>
                        <li><a href="{{ route('size.guide') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> Numara Rehberi</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="space-y-4">
                    <h4 class="font-display font-bold text-sm tracking-wider uppercase text-white border-b border-white/10 pb-2">
                        Kategoriler
                    </h4>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        @foreach($globalCategories ?? [] as $gCat)
                            <li>
                                <a href="{{ route('products.index', ['category' => $gCat->slug]) }}" class="hover:text-accent transition-colors flex items-center">
                                    <i class="fa-solid fa-chevron-right text-[10px] mr-2 text-accent"></i> {{ $gCat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Kurumsal & Yasal -->
                <div class="space-y-4">
                    <h4 class="font-display font-bold text-sm tracking-wider uppercase text-white border-b border-white/10 pb-2">
                        Kurumsal & Yasal
                    </h4>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        <li><a href="{{ route('legal.kvkk') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-shield-halved text-[10px] mr-2 text-accent"></i> KVKK Aydınlatma</a></li>
                        <li><a href="{{ route('legal.privacy') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-user-shield text-[10px] mr-2 text-accent"></i> Gizlilik Politikası</a></li>
                        <li><a href="{{ route('legal.cookies') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-cookie text-[10px] mr-2 text-accent"></i> Çerez Politikası</a></li>
                        <li><a href="{{ route('legal.distance-selling') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-file-contract text-[10px] mr-2 text-accent"></i> Mesafeli Satış</a></li>
                        <li><a href="{{ route('legal.pre-info') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-circle-info text-[10px] mr-2 text-accent"></i> Ön Bilgilendirme</a></li>
                        <li><a href="{{ route('legal.return-policy') }}" class="hover:text-accent transition-colors flex items-center"><i class="fa-solid fa-rotate-left text-[10px] mr-2 text-accent"></i> İade & Değişim</a></li>
                    </ul>
                </div>

                <!-- Contact & Working Hours -->
                <div class="space-y-4">
                    <h4 class="font-display font-bold text-sm tracking-wider uppercase text-white border-b border-white/10 pb-2">
                        İletişim & Mağaza
                    </h4>
                    <div class="space-y-3 text-sm text-white/70">
                        <p class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1 mr-2.5 text-accent flex-shrink-0"></i>
                            <span>{{ \App\Models\Setting::get('site_address', 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul') }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fa-solid fa-phone mr-2.5 text-accent flex-shrink-0"></i>
                            <span>{{ \App\Models\Setting::get('site_phone', '+90 (216) 450 10 20') }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fa-solid fa-envelope mr-2.5 text-accent flex-shrink-0"></i>
                            <span>{{ \App\Models\Setting::get('site_email', 'info@yusufakboga.com') }}</span>
                        </p>
                        <p class="flex items-start text-xs text-white/50 pt-1">
                            <i class="fa-regular fa-clock mt-0.5 mr-2 text-accent flex-shrink-0"></i>
                            <span>{{ \App\Models\Setting::get('site_hours', 'Hafta İçi & Cmt: 09:30 - 20:30') }}</span>
                        </p>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Payment Badges -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-white/50 gap-4">
                <p>© 2026 Yusuf Akboğa Ayakkabı. Tüm hakları saklıdır.</p>
                <div class="flex items-center space-x-4 text-white/40">
                    <span class="flex items-center"><i class="fa-solid fa-lock text-accent mr-1"></i> 256-Bit SSL Güvenli Alışveriş</span>
                    <span>•</span>
                    <span>Kapıda Ödeme & Güvenli Havale</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Search Modal -->
    <div id="searchModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-start justify-center pt-20 px-4">
        <div class="bg-dark border border-white/10 w-full max-w-2xl rounded-2xl p-6 shadow-2xl relative text-white" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="font-display font-bold text-lg text-white flex items-center">
                    <i class="fa-solid fa-magnifying-glass text-accent mr-2.5"></i> Akıllı Ürün Arama
                </h3>
                <button onclick="toggleSearchModal(false)" class="text-white/60 hover:text-white text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="mt-4 relative">
                <input type="text" id="liveSearchInput" placeholder="Model adı, marka, kategori veya renk yazın (Örn: Nike Air Max, siyah spor)..." 
                       class="w-full bg-anthracite text-white placeholder-white/40 px-5 py-3.5 rounded-xl border border-white/10 focus:outline-none focus:border-accent text-sm"
                       oninput="handleLiveSearch(this.value)">
                <div id="searchSpinner" class="hidden absolute right-4 top-3.5 text-accent">
                    <i class="fa-solid fa-circle-notch fa-spin"></i>
                </div>
            </div>

            <!-- Search Results Container -->
            <div id="searchResults" class="mt-4 max-h-96 overflow-y-auto space-y-2 divide-y divide-white/5">
                <p class="text-white/40 text-sm text-center py-6">Aramak istediğiniz ayakkabıyı yukarıya yazabilirsiniz.</p>
            </div>
        </div>
    </div>

    <!-- Favorites Modal -->
    <div id="favoritesModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-dark border border-white/10 w-full max-w-xl rounded-2xl p-6 shadow-2xl relative text-white max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="font-display font-bold text-lg text-white flex items-center">
                    <i class="fa-solid fa-heart text-accent mr-2.5"></i> Favorilerim (<span id="favModalCount">0</span>)
                </h3>
                <button onclick="closeFavoritesModal()" class="text-white/60 hover:text-white text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div id="favItemsList" class="my-4 overflow-y-auto flex-grow space-y-3 pr-1">
                <!-- Dynamically populated via JS -->
            </div>

            <div class="border-t border-white/10 pt-4 flex items-center justify-between">
                <button onclick="clearAllFavorites()" class="text-xs text-rose-400 hover:text-rose-300">
                    <i class="fa-solid fa-trash-can mr-1"></i> Tümünü Temizle
                </button>
                <button onclick="closeFavoritesModal()" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg">
                    Kapat
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-50 flex flex-col space-y-3 pointer-events-none"></div>

    <!-- Global Scripts -->
    <script>
        // CSRF Header setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto transform transition-all duration-300 translate-y-2 opacity-0 flex items-center p-4 rounded-xl shadow-2xl text-sm font-medium border max-w-md ${
                type === 'success' 
                    ? 'bg-dark text-white border-accent/40 shadow-accent/10' 
                    : 'bg-rose-950 text-rose-100 border-rose-500/50'
            }`;
            
            const icon = type === 'success' 
                ? '<i class="fa-solid fa-circle-check text-accent text-lg mr-3"></i>' 
                : '<i class="fa-solid fa-circle-exclamation text-rose-400 text-lg mr-3"></i>';

            toast.innerHTML = `
                ${icon}
                <span class="flex-grow">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-3 text-white/50 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;

            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            // Auto dismiss after 4 seconds
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Mobile Menu Drawer Toggle
        function toggleMobileMenu(open = null) {
            const drawer = document.getElementById('mobileDrawer');
            const backdrop = document.getElementById('mobileMenuBackdrop');
            if (!drawer || !backdrop) return;

            const isOpen = !drawer.classList.contains('translate-x-full');
            const shouldOpen = open !== null ? open : !isOpen;

            if (shouldOpen) {
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    drawer.classList.remove('translate-x-full');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                drawer.classList.add('translate-x-full');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }
        }

        // Global ESC key listener to close active drawers/modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                toggleMobileMenu(false);
                toggleSearchModal(false);
                closeFavoritesModal();
                if (typeof toggleMobileFilter === 'function') {
                    toggleMobileFilter(false);
                }
            }
        });

        // Live Search Modal
        function toggleSearchModal(open) {
            const modal = document.getElementById('searchModal');
            if (open) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.getElementById('liveSearchInput').focus();
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        let searchDebounceTimer;
        function handleLiveSearch(keyword) {
            clearTimeout(searchDebounceTimer);
            const container = document.getElementById('searchResults');
            const spinner = document.getElementById('searchSpinner');

            if (keyword.trim().length < 2) {
                container.innerHTML = '<p class="text-white/40 text-sm text-center py-6">Aramak istediğiniz modeli yazın.</p>';
                return;
            }

            spinner.classList.remove('hidden');

            searchDebounceTimer = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(keyword)}`)
                    .then(r => r.json())
                    .then(data => {
                        spinner.classList.add('hidden');
                        if (data.results && data.results.length > 0) {
                            let html = '';
                            data.results.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="flex items-center space-x-4 p-3 rounded-xl hover:bg-white/5 transition-all group">
                                        <img src="${item.image}" alt="${item.name}" class="w-14 h-14 object-cover rounded-lg bg-anthracite border border-white/10 flex-shrink-0">
                                        <div class="flex-grow min-w-0">
                                            <span class="text-xs font-semibold text-accent uppercase tracking-wider">${item.brand}</span>
                                            <h4 class="text-sm font-semibold text-white truncate group-hover:text-accent transition-colors">${item.name}</h4>
                                            <div class="flex items-center space-x-2 mt-0.5">
                                                <span class="text-sm font-bold text-white">${item.price}</span>
                                                ${item.old_price ? `<span class="text-xs text-white/40 line-through">${item.old_price}</span>` : ''}
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-white/30 group-hover:text-accent group-hover:translate-x-1 transition-all text-xs"></i>
                                    </a>
                                `;
                            });
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = `
                                <div class="text-center py-8 text-white/50">
                                    <i class="fa-regular fa-face-frown text-3xl mb-2 text-white/30"></i>
                                    <p class="text-sm font-medium">"${keyword}" ile eşleşen ayakkabı bulunamadı.</p>
                                </div>
                            `;
                        }
                    })
                    .catch(() => {
                        spinner.classList.add('hidden');
                    });
            }, 300);
        }

        // Close search on click outside
        document.getElementById('searchModal')?.addEventListener('click', function(e) {
            if (e.target === this) toggleSearchModal(false);
        });

        // Favorites System (LocalStorage Guest Mode)
        function getFavorites() {
            try {
                return JSON.parse(localStorage.getItem('ysa_favorites') || '[]');
            } catch (e) {
                return [];
            }
        }

        function updateFavoritesBadge() {
            const favs = getFavorites();
            const badge = document.getElementById('headerFavBadge');
            if (badge) {
                if (favs.length > 0) {
                    badge.innerText = favs.length;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
            // Update heart icons on product cards if any
            document.querySelectorAll('[data-fav-id]').forEach(btn => {
                const id = parseInt(btn.getAttribute('data-fav-id'));
                const icon = btn.querySelector('i');
                if (favs.some(f => f.id === id)) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid', 'text-rose-500');
                } else {
                    icon.classList.remove('fa-solid', 'text-rose-500');
                    icon.classList.add('fa-regular');
                }
            });
        }

        function toggleFavorite(product) {
            let favs = getFavorites();
            const index = favs.findIndex(f => f.id === product.id);

            if (index > -1) {
                favs.splice(index, 1);
                showToast(`"${product.name}" favorilerden çıkarıldı.`);
            } else {
                favs.push(product);
                showToast(`"${product.name}" favorilerinize eklendi.`);
            }

            localStorage.setItem('ysa_favorites', JSON.stringify(favs));
            updateFavoritesBadge();
            renderFavoritesModal();
        }

        function toggleFavoriteFromButton(btn) {
            try {
                const product = JSON.parse(btn.getAttribute('data-product'));
                toggleFavorite(product);
            } catch (e) {
                console.error('Favorite toggle error:', e);
            }
        }

        function removeFavoriteById(id) {
            let favs = getFavorites();
            const item = favs.find(f => f.id === id);
            const name = item ? item.name : 'Ürün';
            favs = favs.filter(f => f.id !== id);
            localStorage.setItem('ysa_favorites', JSON.stringify(favs));
            updateFavoritesBadge();
            renderFavoritesModal();
            showToast(`"${name}" favorilerden çıkarıldı.`);
        }

        function openFavoritesModal() {
            renderFavoritesModal();
            const modal = document.getElementById('favoritesModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeFavoritesModal() {
            const modal = document.getElementById('favoritesModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderFavoritesModal() {
            const favs = getFavorites();
            const list = document.getElementById('favItemsList');
            const countSpan = document.getElementById('favModalCount');
            if (countSpan) countSpan.innerText = favs.length;

            if (favs.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-10 text-white/50">
                        <i class="fa-regular fa-heart text-4xl mb-3 text-white/20"></i>
                        <p class="text-sm font-medium">Henüz favori ürününüz bulunmuyor.</p>
                        <a href="{{ route('products.index') }}" onclick="closeFavoritesModal()" class="mt-4 inline-block px-5 py-2 bg-accent text-dark font-bold text-xs rounded-xl hover:bg-accent-light transition-colors">
                            Modelleri Keşfet
                        </a>
                    </div>
                `;
                return;
            }

            let html = '';
            favs.forEach(item => {
                const safeName = escapeHtml(item.name);
                const safeBrand = escapeHtml(item.brand || 'YSA');
                const safePrice = escapeHtml(item.price);
                const safeUrl = encodeURI(item.url || '#');
                const safeImg = encodeURI(item.image || '');

                html += `
                    <div class="flex items-center justify-between p-3 rounded-xl bg-anthracite border border-white/5 hover:border-accent/30 transition-all">
                        <a href="${safeUrl}" class="flex items-center space-x-3 min-w-0">
                            <img src="${safeImg}" alt="${safeName}" class="w-14 h-14 object-cover rounded-lg bg-dark flex-shrink-0">
                            <div class="min-w-0">
                                <span class="text-[11px] font-semibold text-accent uppercase">${safeBrand}</span>
                                <h4 class="text-xs font-semibold text-white truncate">${safeName}</h4>
                                <span class="text-xs font-bold text-white/90">${safePrice}</span>
                            </div>
                        </a>
                        <div class="flex items-center space-x-2">
                            <a href="${safeUrl}" class="p-2 bg-accent text-dark rounded-lg text-xs font-bold hover:bg-accent-light transition-colors" title="İncele">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            <button type="button" onclick="removeFavoriteById(${item.id})" class="p-2 text-rose-400 hover:text-rose-300 text-xs" title="Kaldır">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        function clearAllFavorites() {
            localStorage.removeItem('ysa_favorites');
            updateFavoritesBadge();
            renderFavoritesModal();
            showToast('Tüm favoriler temizlendi.');
        }

        document.getElementById('favoritesModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeFavoritesModal();
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            updateFavoritesBadge();
        });
    </script>

    @stack('scripts')
</body>
</html>
