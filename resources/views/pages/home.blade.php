@extends('layouts.app')

@section('title', 'VELORA | Tarzın Adımlarında')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative bg-dark text-white overflow-hidden py-14 md:py-24 border-b border-white/10">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Hero Left Content -->
                <div class="lg:col-span-7 space-y-5 sm:space-y-6 text-center lg:text-left">
                    
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-white/5 border border-accent/30 text-accent text-xs sm:text-[13px] font-semibold tracking-normal">
                        <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
                        <span class="truncate">2026 VELORA Premium Sneaker Koleksiyonu</span>
                    </div>

                    <h1 class="hero-main-title text-white font-extrabold select-none">
                        Tarzını<br>
                        <span class="text-accent">Adımlarınla</span><br>
                        Göster.
                    </h1>

                    <p class="hero-description text-white/80 max-w-2xl mx-auto lg:mx-0 font-normal">
                        Yeni sezon ayakkabı modellerini keşfet. Günlük kullanımdan özel kombinlere kadar tarzına en uygun modeli VELORA güvencesiyle bul.
                    </p>

                    <!-- Hero CTAs -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                        <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-7 py-3.5 sm:py-4 bg-accent hover:bg-accent-light text-dark font-display font-bold text-xs sm:text-sm tracking-wide rounded-xl transition-all shadow-xl shadow-accent/20 flex items-center justify-center space-x-2.5 group">
                            <span>Modelleri Keşfet</span>
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#yeni-gelenler" class="w-full sm:w-auto px-7 py-3.5 sm:py-4 bg-white/5 hover:bg-white/10 text-white border border-white/20 font-display font-bold text-xs sm:text-sm tracking-wide rounded-xl transition-all flex items-center justify-center space-x-2.5">
                            <span>Yeni Gelenler</span>
                            <i class="fa-solid fa-sparkles text-accent text-xs"></i>
                        </a>
                    </div>

                    <!-- Micro Trust Metrics -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-6 pt-6 sm:pt-8 border-t border-white/10 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <p class="font-display font-extrabold text-xl sm:text-2xl md:text-3xl text-accent tracking-tight">%100</p>
                            <p class="text-[11px] sm:text-[13px] text-white/70 mt-0.5 leading-tight">Orijinal Garantisi</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="font-display font-extrabold text-xl sm:text-2xl md:text-3xl text-accent tracking-tight">5000+</p>
                            <p class="text-[11px] sm:text-[13px] text-white/70 mt-0.5 leading-tight">Mutlu Müşteri</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="font-display font-extrabold text-xl sm:text-2xl md:text-3xl text-accent tracking-tight">Hızlı</p>
                            <p class="text-[11px] sm:text-[13px] text-white/70 mt-0.5 leading-tight">Aynı Gün Kargo</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Right Image Showcase -->
                <div class="lg:col-span-5 relative flex items-center justify-center w-full px-2 sm:px-0">
                    <div class="relative w-full max-w-[320px] sm:max-w-md aspect-square rounded-3xl bg-gradient-to-tr from-anthracite via-dark-200 to-anthracite p-6 sm:p-8 border border-white/10 shadow-2xl flex items-center justify-center group mx-auto">
                        
                        <!-- Floating Badge -->
                        <div class="absolute -top-3 right-2 sm:-top-4 sm:-right-4 bg-dark border border-accent/40 text-accent px-3 py-1.5 sm:px-4 sm:py-2 rounded-2xl shadow-xl flex items-center space-x-1.5 sm:space-x-2 z-20">
                            <i class="fa-solid fa-award text-xs sm:text-sm"></i>
                            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider">Premium Kalite</span>
                        </div>

                        <!-- Sneaker Photo -->
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=900&auto=format&fit=crop&q=80" 
                             alt="VELORA Hero Sneaker" 
                             class="w-full h-auto max-h-56 sm:max-h-72 object-contain transform -rotate-12 group-hover:rotate-0 group-hover:scale-105 transition-all duration-700 filter drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)]">

                        <!-- Floating Bottom Tag -->
                        <div class="absolute -bottom-3 left-2 sm:-bottom-4 sm:-left-4 bg-dark/95 backdrop-blur border border-white/10 text-white p-2.5 sm:p-3 rounded-2xl shadow-xl flex items-center space-x-2.5 sm:space-x-3 z-20">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-accent/20 flex items-center justify-center text-accent">
                                <i class="fa-solid fa-shoe-prints text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] sm:text-xs font-bold text-white leading-tight">VELORA Signature</p>
                                <p class="text-[9px] sm:text-[10px] text-accent font-semibold leading-tight">Özel Seçim Modeller</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 1: KATEGORİLER -->
    <section class="py-16 bg-[#F7F7F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Koleksiyonlar</span>
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-dark mt-1">Öne Çıkan Kategoriler</h2>
                </div>
                <a href="{{ route('products.index') }}" class="mt-4 sm:mt-0 text-xs font-bold uppercase tracking-wider text-dark hover:text-accent transition-colors flex items-center">
                    Tüm Kategorileri Gör <i class="fa-solid fa-arrow-right ml-2 text-accent"></i>
                </a>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       class="group relative aspect-[4/5] rounded-2xl overflow-hidden bg-dark shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-end p-5 border border-black/5">
                        
                        <!-- Background Image -->
                        <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $category->name }}" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-70 group-hover:opacity-85">

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                        <!-- Content -->
                        <div class="relative z-10">
                            <span class="text-[10px] font-semibold tracking-wider text-accent uppercase">{{ $category->products_count }} Model</span>
                            <h3 class="font-display font-bold text-lg text-white group-hover:text-accent transition-colors">
                                {{ $category->name }}
                            </h3>
                            <div class="w-6 h-0.5 bg-accent group-hover:w-12 transition-all duration-300 mt-1"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 2: YENİ GELENLER -->
    <section id="yeni-gelenler" class="py-16 bg-white border-y border-black/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-10">
                <div>
                    <div class="inline-flex items-center space-x-1 text-xs font-bold uppercase tracking-widest text-accent">
                        <i class="fa-solid fa-sparkles mr-1"></i>
                        <span>En Son Eklenenler</span>
                    </div>
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-dark mt-1">Yeni Gelen Modeller</h2>
                </div>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="mt-4 sm:mt-0 px-5 py-2.5 bg-dark hover:bg-accent text-white hover:text-dark text-xs font-bold rounded-xl transition-all flex items-center space-x-2">
                    <span>Tüm Yeni Modeller</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($newArrivals as $prod)
                    @include('partials.product-card', ['product' => $prod])
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 3: KAMPANYA & PROMOSYON BANNER -->
    @php
        $heroCamp = $heroCampaigns->first();
    @endphp
    <section class="py-16 bg-[#F7F7F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-dark via-anthracite to-dark text-white p-8 sm:p-12 lg:p-16 border border-white/10 shadow-2xl">
                
                <!-- Background decoration -->
                <div class="absolute right-0 top-0 bottom-0 w-1/2 opacity-20 pointer-events-none hidden md:block">
                    <img src="{{ $heroCamp?->image_url ?: 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=1000&auto=format&fit=crop&q=80' }}" 
                         alt="Kampanya" 
                         class="w-full h-full object-cover">
                </div>

                <div class="relative z-10 max-w-xl space-y-5">
                    <span class="inline-block px-3.5 py-1.5 rounded-lg bg-accent text-dark font-display font-bold text-xs tracking-wider uppercase">
                        {{ $heroCamp?->badge ?? 'Fırsat Kampanyası' }}
                    </span>
                    
                    <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight">
                        {{ $heroCamp?->title ?? "Yeni Sezonda %20'ye Varan Fırsatlar" }}
                    </h2>

                    <p class="text-white/70 text-sm sm:text-base leading-relaxed">
                        {{ $heroCamp?->subtitle ?? "Seçili premium sneaker ve klasik ayakkabı modellerinde kaçırılmayacak sezon indirimleri seni bekliyor." }}
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="{{ $heroCamp?->button_url ?? route('products.index', ['discounted' => 1]) }}" class="px-8 py-3.5 bg-accent hover:bg-accent-light text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-accent/20 flex items-center space-x-2">
                            <span>{{ $heroCamp?->button_text ?? 'Fırsatları İncele' }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <span class="text-xs text-white/50 flex items-center">
                            <i class="fa-solid fa-clock text-accent mr-1.5"></i> Sınırlı Süre & Stok
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: ÇOK SATANLAR & ÖNE ÇIKANLAR -->
    <section class="py-16 bg-white border-b border-black/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Popüler Seçimler</span>
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-dark mt-1">Çok Satanlar & Trend Modeller</h2>
                </div>
                <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="mt-4 sm:mt-0 text-xs font-bold uppercase tracking-wider text-dark hover:text-accent transition-colors flex items-center">
                    Tüm Çok Satanlar <i class="fa-solid fa-arrow-right ml-2 text-accent"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($bestSellers as $bProduct)
                    @include('partials.product-card', ['product' => $bProduct])
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 5: NEDEN BİZİ TERCİH ETMELİSİNİZ? (4 AVANTAJ KARTI) -->
    <section class="py-16 bg-[#F7F7F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Güven & Kalite</span>
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-dark mt-1">Neden Bizi Tercih Etmelisiniz?</h2>
                <p class="text-black/60 text-sm mt-2">VELORA olarak her adımınızda yüksek kalite, lüks ve kusursuz alışveriş deneyimi sunuyoruz.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- 1. Güvenli Alışveriş -->
                <div class="bg-white rounded-2xl p-6 border border-black/5 shadow-sm hover:border-accent/40 hover:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-dark mb-1">Güvenli Alışveriş</h3>
                    <p class="text-black/60 text-xs leading-relaxed">256-bit SSL güvenlik altyapısı, kapıda ödeme ve güvenli sipariş sistemi.</p>
                </div>

                <!-- 2. Kaliteli Ürünler -->
                <div class="bg-white rounded-2xl p-6 border border-black/5 shadow-sm hover:border-accent/40 hover:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-dark mb-1">%100 Orijinal Ürünler</h3>
                    <p class="text-black/60 text-xs leading-relaxed">Tüm dünya markaları ve özel deri koleksiyonlarımız %100 orijinal ve faturalıdır.</p>
                </div>

                <!-- 3. Hızlı Teslimat -->
                <div class="bg-white rounded-2xl p-6 border border-black/5 shadow-sm hover:border-accent/40 hover:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-dark mb-1">Hızlı Teslimat</h3>
                    <p class="text-black/60 text-xs leading-relaxed">Saat 16:00'a kadar verilen siparişler aynı gün özenle kargolanır.</p>
                </div>

                <!-- 4. Kolay İletişim -->
                <div class="bg-white rounded-2xl p-6 border border-black/5 shadow-sm hover:border-accent/40 hover:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-dark mb-1">Birebir WhatsApp Destek</h3>
                    <p class="text-black/60 text-xs leading-relaxed">Numara seçimi ve tüm sorularınız için uzman ekibimiz anında yanınızda.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 6: MAĞAZA HAKKINDA MİNİ BÖLÜM -->
    <section class="py-20 bg-dark text-white relative overflow-hidden border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Story Content -->
                <div class="lg:col-span-6 space-y-6">
                    <span class="inline-block px-3.5 py-1.5 rounded-full bg-accent/10 border border-accent/30 text-accent text-xs font-semibold tracking-wider uppercase">
                        VELORA Hikayesi
                    </span>

                    <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight">
                        Adımlarınıza Değer Katan <span class="text-accent">Tutku & İşçilik</span>
                    </h2>

                    <p class="text-white/70 text-sm sm:text-base leading-relaxed">
                        {{ \App\Models\Setting::get('about_mini', 'VELORA, adımlarınıza prestij, estetik ve konfor katmak amacıyla kurulmuş seçkin ve modern bir ayakkabı markasıdır.') }}
                    </p>

                    <p class="text-white/60 text-xs sm:text-sm leading-relaxed">
                        Modern sneaker trendlerini, el işçiliği klasik deri koleksiyonlarıyla harmanlayarak Türkiye genelinde binlerce müşterimize güvenle hizmet veriyoruz.
                    </p>

                    <div class="pt-2">
                        <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-3.5 bg-accent hover:bg-accent-light text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all space-x-2">
                            <span>Bizi Tanıyın & Mağazamız</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Right Store Photo -->
                <div class="lg:col-span-6">
                    <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=1000&auto=format&fit=crop&q=80" 
                             alt="VELORA Mağaza" 
                             class="w-full h-80 sm:h-96 object-cover filter brightness-90 hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-dark/80 backdrop-blur border border-white/10">
                            <p class="font-display font-bold text-white text-sm">VELORA Showroom & Butik</p>
                            <p class="text-accent text-xs font-medium">{{ \App\Models\Setting::get('site_address', 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
