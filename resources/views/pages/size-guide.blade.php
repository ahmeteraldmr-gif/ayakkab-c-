@extends('layouts.app')

@section('title', 'Doğru Ayakkabı Numarası Seçimi Rehberi | VELORA')

@section('content')

    <div class="bg-dark text-white py-12 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-accent">Kusursuz Kalıp</span>
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-white">
                Ayakkabı Numarası ve Beden Rehberi
            </h1>
            <p class="text-white/60 text-sm max-w-xl mx-auto">
                Ayakkabınızın ayağınıza tam oturması ve maksimum konfor sağlaması için ayak ölçünüzü nasıl alabileceğinizi ve uluslararası beden karşılıklarını öğrenin.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
        
        <!-- Measurement Steps -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-black/5 shadow-sm space-y-8">
            <h2 class="font-display font-extrabold text-2xl text-dark">Adım Adım Ayak Ölçüsü Nasıl Alınır?</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-black/70">
                <div class="p-6 bg-[#F7F7F5] rounded-2xl border border-black/5 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-dark text-accent font-bold flex items-center justify-center text-sm">1</span>
                    <h3 class="font-display font-bold text-sm text-dark">Kağıda Basın</h3>
                    <p class="leading-relaxed">Düz ve sert bir zemine boş bir A4 kağıdı koyun. Ayakkabıyı giyeceğiniz çorapla kağıdın üzerine dik bir şekilde basın.</p>
                </div>

                <div class="p-6 bg-[#F7F7F5] rounded-2xl border border-black/5 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-dark text-accent font-bold flex items-center justify-center text-sm">2</span>
                    <h3 class="font-display font-bold text-sm text-dark">Uçları İşaretleyin</h3>
                    <p class="leading-relaxed">Topuğunuzun en arka noktası ile en uzun ayak parmağınızın uç noktasını bir kalem yardımıyla dik açıyla işaretleyin.</p>
                </div>

                <div class="p-6 bg-[#F7F7F5] rounded-2xl border border-black/5 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-dark text-accent font-bold flex items-center justify-center text-sm">3</span>
                    <h3 class="font-display font-bold text-sm text-dark">Cetvelle Ölçün</h3>
                    <p class="leading-relaxed">İki işaret arasındaki mesafeyi cetvel veya mezura ile santimetre (cm) olarak ölçün. Çıkan sonucu aşağıdaki tablo ile eşleştirin.</p>
                </div>
            </div>
        </div>

        <!-- Comprehensive Shoe Size Table -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-black/5 shadow-sm space-y-6">
            <h2 class="font-display font-extrabold text-2xl text-dark">Uluslararası Ayakkabı Numarası Karşılaştırma Tablosu</h2>

            <div class="overflow-x-auto border border-black/10 rounded-2xl">
                <table class="w-full text-center divide-y divide-black/10 text-xs">
                    <thead class="bg-dark text-white font-bold">
                        <tr>
                            <th class="p-3.5">EU (Avrupa / Türkiye)</th>
                            <th class="p-3.5">Ayak Uzunluğu (cm)</th>
                            <th class="p-3.5">US Erkek</th>
                            <th class="p-3.5">US Kadın</th>
                            <th class="p-3.5">UK (İngiltere)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 text-dark font-medium">
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">36</td><td>22.5 cm</td><td>4.0</td><td>5.5</td><td>3.5</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">37</td><td>23.5 cm</td><td>5.0</td><td>6.5</td><td>4.5</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">38</td><td>24.0 cm</td><td>5.5</td><td>7.0</td><td>5.0</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">39</td><td>24.5 cm</td><td>6.5</td><td>8.0</td><td>6.0</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">40</td><td>25.0 cm</td><td>7.0</td><td>8.5</td><td>6.5</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">41</td><td>26.0 cm</td><td>8.0</td><td>9.5</td><td>7.5</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">42</td><td>26.5 cm</td><td>8.5</td><td>10.0</td><td>8.0</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">43</td><td>27.5 cm</td><td>9.5</td><td>11.0</td><td>9.0</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">44</td><td>28.0 cm</td><td>10.0</td><td>11.5</td><td>9.5</td></tr>
                        <tr class="hover:bg-cream"><td class="p-3 font-bold bg-[#F7F7F5]">45</td><td>29.0 cm</td><td>11.0</td><td>12.5</td><td>10.5</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- WhatsApp Advice CTA -->
            <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-3xl"></i>
                    <div>
                        <h4 class="font-bold text-emerald-900 text-sm">Hangi numarayı seçeceğinizden emin değil misiniz?</h4>
                        <p class="text-xs text-emerald-700">Uzman ekibimiz ayakkabı kalıpları hakkında anında bilgi verir.</p>
                    </div>
                </div>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '905321234567')) }}?text={{ urlencode('Merhaba, ayakkabı numarası seçimi konusunda destek almak istiyorum.') }}" 
                   target="_blank"
                   class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm flex-shrink-0">
                    WhatsApp Danışmanına Sor
                </a>
            </div>
        </div>

    </div>

@endsection
