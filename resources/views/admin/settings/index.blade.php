@extends('layouts.admin')

@section('title', 'Site ve Mağaza Ayarları | Yusuf Akboğa Yönetim Paneli')
@section('page_title', 'Site ve Mağaza Ayarları')

@section('content')

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">
            
            <!-- LEFT: SETTINGS FORM (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. General & Branding -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center">
                        <i class="fa-solid fa-store text-blue-600 mr-2"></i> Mağaza ve Marka Bilgileri
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Mağaza / Şirket Adı</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Yusuf Akboğa Ayakkabı' }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Site Başlığı (SEO Title)</label>
                            <input type="text" name="site_title" value="{{ $settings['site_title'] ?? 'Yusuf Akboğa Ayakkabı | Modern & Premium Sneaker' }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Site Genel Açıklaması (Meta Description)</label>
                        <textarea name="site_description" rows="2" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ $settings['site_description'] ?? 'Yusuf Akboğa kalitesiyle en yeni ve tarz ayakkabı modellerini keşfedin.' }}</textarea>
                    </div>
                </div>

                <!-- 2. Contact & Social -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center">
                        <i class="fa-solid fa-phone text-blue-600 mr-2"></i> İletişim ve Sosyal Medya
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Sabit / Mağaza Telefonu</label>
                            <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '+90 (216) 450 10 20' }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">WhatsApp Sipariş Hattı</label>
                            <input type="text" name="site_whatsapp" value="{{ $settings['site_whatsapp'] ?? '905321234567' }}" placeholder="905XXXXXXXXX" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">E-Posta Adresi</label>
                            <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'info@yusufakboga.com' }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Instagram Profili URL</label>
                            <input type="text" name="site_instagram" value="{{ $settings['site_instagram'] ?? 'https://instagram.com/yusufakboga_ayakkabi' }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fiziki Mağaza Adresi</label>
                        <input type="text" name="site_address" value="{{ $settings['site_address'] ?? 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul' }}" 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Çalışma Saatleri</label>
                        <input type="text" name="site_hours" value="{{ $settings['site_hours'] ?? 'Pazartesi - Cumartesi: 09:30 - 20:30 | Pazar: 11:00 - 19:00' }}" 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>

                <!-- 3. About Texts -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center">
                        <i class="fa-solid fa-feather-pointed text-blue-600 mr-2"></i> Mağaza Tanıtım Metinleri
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ana Sayfa & Footer Kısa Tanıtım</label>
                        <textarea name="about_mini" rows="3" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ $settings['about_mini'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Hakkımızda Sayfası Detaylı Metin</label>
                        <textarea name="about_full" rows="6" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ $settings['about_full'] ?? '' }}</textarea>
                    </div>
                </div>

            </div>

            <!-- RIGHT: E-COMMERCE RULES & SAVE (lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-5 sticky top-24">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3">
                        Kargo ve Sipariş Kuralları
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ücretsiz Kargo Barajı (TL)</label>
                        <input type="number" step="1" name="free_shipping_threshold" value="{{ $settings['free_shipping_threshold'] ?? 1500 }}" 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        <span class="text-[11px] text-gray-400 mt-1 block">Bu tutar ve üzeri sepetlerde kargo ücretsiz olur.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Standart Kargo Ücreti (TL)</label>
                        <input type="number" step="0.01" name="shipping_cost" value="{{ $settings['shipping_cost'] ?? 89.90 }}" 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="pt-4 border-t border-[#E5E7EB]">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-xs flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Tüm Ayarları Kaydet</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection
