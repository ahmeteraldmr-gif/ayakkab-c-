@extends('layouts.app')

@section('title', 'İletişim & Mağaza | VELORA')

@section('content')

    <!-- Header Banner -->
    <div class="bg-dark text-white py-12 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-accent">Bize Ulaşın</span>
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-white">
                İletişim & Mağaza Bilgileri
            </h1>
            <p class="text-white/60 text-sm max-w-lg mx-auto">
                Sorularınız, numara danışmanlığı veya toptan/perakende talepleriniz için dilediğiniz zaman bizimle iletişime geçebilirsiniz.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- LEFT: CONTACT DETAILS & MAP (lg:col-span-5) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-6">
                    <h3 class="font-display font-extrabold text-xl text-dark border-b border-black/5 pb-3">
                        Mağaza İletişim
                    </h3>

                    <div class="space-y-4 text-xs text-black/70">
                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-dark text-accent flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span class="font-bold text-dark block text-xs">Adres</span>
                                <p class="mt-0.5 leading-relaxed">{{ \App\Models\Setting::get('site_address', 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-dark text-accent flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span class="font-bold text-dark block text-xs">Telefon</span>
                                <a href="tel:{{ \App\Models\Setting::get('site_phone', '+90 216 450 10 20') }}" class="hover:text-accent font-semibold block mt-0.5">
                                    {{ \App\Models\Setting::get('site_phone', '+90 (216) 450 10 20') }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-base flex-shrink-0">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <span class="font-bold text-dark block text-xs">WhatsApp Destek Hattı</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '905321234567')) }}" target="_blank" class="text-emerald-700 font-bold block mt-0.5 hover:underline">
                                    +{{ \App\Models\Setting::get('site_whatsapp', '90 532 123 45 67') }} (Tıkla Mesaj At)
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-dark text-accent flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="font-bold text-dark block text-xs">E-Posta</span>
                                <a href="mailto:{{ \App\Models\Setting::get('site_email', 'info@velora.com') }}" class="hover:text-accent font-semibold block mt-0.5">
                                    {{ \App\Models\Setting::get('site_email', 'info@velora.com') }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-dark text-accent flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <span class="font-bold text-dark block text-xs">Çalışma Saatleri</span>
                                <p class="mt-0.5 leading-relaxed">{{ \App\Models\Setting::get('site_hours', 'Pazartesi - Cumartesi: 09:30 - 20:30 | Pazar: 11:00 - 19:00') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Map Placeholder / Interactive Box -->
                <div class="bg-white rounded-3xl p-4 border border-black/5 shadow-sm overflow-hidden">
                    <div class="w-full h-56 rounded-2xl bg-[#E5E3DF] relative flex items-center justify-center text-center p-6 border border-black/5">
                        <div class="space-y-2">
                            <i class="fa-solid fa-map-location-dot text-3xl text-dark"></i>
                            <h4 class="font-display font-bold text-xs text-dark">Google Maps Konumu</h4>
                            <p class="text-[11px] text-black/60">{{ \App\Models\Setting::get('site_address', 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul') }}</p>
                            <a href="https://maps.google.com/?q={{ urlencode(\App\Models\Setting::get('site_address', 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul')) }}" 
                               target="_blank" 
                               class="inline-block px-4 py-2 bg-dark text-white font-bold text-[10px] rounded-xl hover:bg-accent hover:text-dark transition-colors">
                                Haritada Aç <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT: CONTACT FORM (lg:col-span-7) -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-black/5 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-accent">Mesaj Gönderin</span>
                        <h3 class="font-display font-extrabold text-2xl text-dark mt-1">Size Nasıl Yardımcı Olabiliriz?</h3>
                        <p class="text-black/50 text-xs mt-1">Form üzerinden ilettiğiniz mesajlar en geç 2 saat içerisinde yanıtlanır.</p>
                    </div>

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">Adınız ve Soyadınız <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Örn: Ahmet Yılmaz" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">Telefon veya E-posta <span class="text-rose-500">*</span></label>
                                <input type="text" name="email_or_phone" value="{{ old('email_or_phone') }}" required placeholder="05XXXXXXXXX veya e-posta" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">Konu</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Örn: Ayakkabı Numarası / Stok Durumu / Sipariş Takibi" 
                                   class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">Mesajınız <span class="text-rose-500">*</span></label>
                            <textarea name="message" rows="5" required placeholder="İletmek istediğiniz tüm soruları buraya yazabilirsiniz..." 
                                      class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl p-4 text-xs text-dark focus:outline-none focus:border-accent">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="px-8 py-4 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-lg flex items-center space-x-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Mesajı Gönder</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
