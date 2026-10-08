@extends('layouts.app')

@section('title', 'Hakkımızda | VELORA')

@section('content')

    <!-- Header Banner -->
    <div class="bg-dark text-white py-12 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-accent">Markamız & Hikayemiz</span>
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white tracking-wider">
                VELORA
            </h1>
            <p class="text-white/60 text-sm max-w-xl mx-auto italic">
                “Tarzın Adımlarında — Lüks ve Konforun Buluştuğu Nokta.”
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        
        <!-- Story & Founder Block -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 space-y-6">
                <span class="inline-block px-3.5 py-1.5 rounded-full bg-accent/10 border border-accent/30 text-accent text-xs font-bold uppercase tracking-wider">
                    Marka Vizyonumuz
                </span>
                
                <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-dark leading-tight">
                    Tutkuyla Başlayan Bir <span class="text-accent">Ayakkabı Sevdası</span>
                </h2>

                <div class="space-y-4 text-black/70 text-sm leading-relaxed">
                    <p>
                        {{ \App\Models\Setting::get('about_full', 'VELORA, ayakkabı sektöründe kalite, zarafet ve estetiği bir araya getirme vizyonuyla yola çıkmıştır. Müşteri memnuniyetini en üst düzeyde tutarak, dünya standartlarında orijinal modelleri ve seçkin koleksiyonları sunmaktayız.') }}
                    </p>
                    <p>
                        Ayakkabı yalnızca bir giyim eşyası değil, bir duruş ve özgüven ifadesidir. Bu anlayışla VELORA bünyesinde yer alan her bir sneaker ve klasik model, kalite kontrol süreçlerimizden titizlikle geçirilerek sizlerin beğenisine sunulur.
                    </p>
                </div>

                <div class="pt-2 flex items-center space-x-6">
                    <div>
                        <p class="font-display font-bold text-2xl text-dark">2016</p>
                        <p class="text-xs text-black/50">Kuruluş Yılı</p>
                    </div>
                    <div class="h-10 w-px bg-black/10"></div>
                    <div>
                        <p class="font-display font-bold text-2xl text-dark">50.000+</p>
                        <p class="text-xs text-black/50">Teslim Edilen Çift</p>
                    </div>
                    <div class="h-10 w-px bg-black/10"></div>
                    <div>
                        <p class="font-display font-bold text-2xl text-dark">%100</p>
                        <p class="text-xs text-black/50">Müşteri Memnuniyeti</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="relative rounded-3xl overflow-hidden border border-black/5 shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=1000&auto=format&fit=crop&q=80" 
                         alt="VELORA Mağazası" 
                         class="w-full h-[450px] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-dark/80 backdrop-blur border border-white/10 text-white">
                        <p class="font-display font-bold text-sm">VELORA Showroom & Konsept Mağaza</p>
                        <p class="text-accent text-xs">{{ \App\Models\Setting::get('site_address', 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul') }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Vision & Values -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-black/5 shadow-sm space-y-8">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Değerlerimiz</span>
                <h3 class="font-display font-extrabold text-2xl text-dark">Bizi Farklı Kılan Standartlarımız</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl bg-[#F7F7F5] border border-black/5 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl">
                        <i class="fa-solid fa-gem"></i>
                    </div>
                    <h4 class="font-display font-bold text-base text-dark">Seçkin ve Orijinal Koleksiyon</h4>
                    <p class="text-black/60 text-xs leading-relaxed">
                        Sadece dünya çapında kabul görmüş, orijinalliği tescilli ve yüksek malzeme kalitesine sahip markaları vitrinimize taşıyoruz.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-[#F7F7F5] border border-black/5 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h4 class="font-display font-bold text-base text-dark">Kişiye Özel İlgi ve Destek</h4>
                    <p class="text-black/60 text-xs leading-relaxed">
                        Her müşterimizin ayak anatomisine en uygun kalıbı ve modeli bulabilmesi için WhatsApp üzerinden birebir uzman danışmanlık sunuyoruz.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-[#F7F7F5] border border-black/5 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-dark text-accent flex items-center justify-center text-xl">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <h4 class="font-display font-bold text-base text-dark">Şeffaf ve Güvenilir Ticaret</h4>
                    <p class="text-black/60 text-xs leading-relaxed">
                        Kapıda ödeme, 14 gün koşulsuz değişim hakkı ve faturalı gönderim ile alışverişinizin her anında güveninizi koruyoruz.
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection
