@extends('layouts.admin')

@section('title', 'Yeni Ürün Ekle | Yusuf Akboğa Yönetim Paneli')
@section('page_title', 'Yeni Ürün Ekle')

@section('content')

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">
            
            <!-- LEFT: PRODUCT MAIN INFO (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Basic Information -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3">
                        Temel Ürün Bilgileri
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ürün Adı <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Örn: Nike Air Max 270 Black Gold" 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kategori</label>
                            <select name="category_id" class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Kategori Seçin</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Marka</label>
                            <select name="brand_id" class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Marka Seçin</option>
                                @foreach($brands as $brn)
                                    <option value="{{ $brn->id }}" {{ old('brand_id') == $brn->id ? 'selected' : '' }}>{{ $brn->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cinsiyet <span class="text-red-500">*</span></label>
                            <select name="gender" required class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="unisex" {{ old('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                                <option value="erkek" {{ old('gender') === 'erkek' ? 'selected' : '' }}>Erkek</option>
                                <option value="kadin" {{ old('gender') === 'kadin' ? 'selected' : '' }}>Kadın</option>
                                <option value="cocuk" {{ old('gender') === 'cocuk' ? 'selected' : '' }}>Çocuk</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ürün Kodu (SKU)</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Boşsa otomatik üretilir" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Renk Adı</label>
                            <input type="text" name="color" value="{{ old('color') }}" placeholder="Örn: Siyah / Beyaz" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Renk Kodu (HEX)</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" id="colorPicker" value="{{ old('color_code', '#000000') }}" onchange="document.getElementById('colorHex').value = this.value" class="w-9 h-8 rounded border border-gray-300 cursor-pointer bg-transparent">
                                <input type="text" name="color_code" id="colorHex" value="{{ old('color_code', '#000000') }}" placeholder="#000000" 
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] font-mono focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                        </div>
                    </div>

                    <!-- PRICE & CAMPAIGN DISCOUNT SECTION -->
                    <div class="bg-amber-50/40 border border-amber-200/80 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between border-b border-amber-200/60 pb-2.5">
                            <h4 class="font-bold text-xs text-amber-900 uppercase tracking-wider flex items-center">
                                <i class="fa-solid fa-tags text-amber-600 mr-2"></i> Fiyat & Kampanya İndirimi
                            </h4>
                            <span class="text-[11px] text-amber-700 font-medium">Yüzde veya tutar girerek kampanya tanımlayın</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Normal Satış Fiyatı (TL) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="price" id="productPrice" value="{{ old('price') }}" required placeholder="0.00" 
                                       oninput="updateDiscountFromPercent()"
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] font-semibold focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">
                                    Kampanya İndirim Oranı (%)
                                </label>
                                <div class="relative">
                                    <input type="number" step="1" min="0" max="99" id="discountPercent" placeholder="Örn: 20" 
                                           oninput="updateDiscountFromPercent()"
                                           class="w-full bg-white border border-amber-300 rounded-lg pl-8 pr-3.5 py-2.5 text-xs text-amber-900 font-bold focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-400/20">
                                    <span class="absolute left-3 top-2.5 text-amber-600 font-bold text-xs">%</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Kampanyalı Fiyat (TL, Opsiyonel)
                                </label>
                                <input type="number" step="0.01" name="discount_price" id="discountPrice" value="{{ old('discount_price') }}" placeholder="Hesaplanan tutar" 
                                       oninput="updateDiscountFromPrice()"
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-emerald-700 font-bold focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                        </div>

                        <!-- Quick Discount Buttons -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                            <span class="text-[11px] font-bold text-gray-500 uppercase mr-1">Hızlı Kampanya:</span>
                            @foreach([10, 15, 20, 25, 30, 40, 50] as $rate)
                                <button type="button" onclick="applyQuickDiscount({{ $rate }})" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 border border-amber-300 rounded-md text-xs font-bold transition-colors">
                                    %{{ $rate }} İndirim
                                </button>
                            @endforeach
                            <button type="button" onclick="applyQuickDiscount(0)" class="px-2.5 py-1 bg-white hover:bg-red-50 text-red-600 border border-red-200 rounded-md text-xs font-semibold transition-colors">
                                <i class="fa-solid fa-xmark mr-1"></i> İndirimi Kaldır
                            </button>
                        </div>

                        <!-- Live Discount Notice -->
                        <div id="discountInfoBox" class="hidden p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span id="discountInfoText"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <div>
                            <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-2">Kalıp / Beden Türü</label>
                            <select name="fit_type" class="w-full bg-white border border-blue-200 rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600">
                                <option value="tam_kalip" {{ old('fit_type') === 'tam_kalip' ? 'selected' : '' }}>Tam Kalıp (Kendi numaranızı alabilirsiniz)</option>
                                <option value="dar_kalip" {{ old('fit_type') === 'dar_kalip' ? 'selected' : '' }}>Dar Kalıp (1 numara büyük önerilir)</option>
                                <option value="genis_kalip" {{ old('fit_type') === 'genis_kalip' ? 'selected' : '' }}>Geniş Kalıp (1 numara küçük tercih edilebilir)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-2">Özel Kalıp / Beden Notu</label>
                            <input type="text" name="size_note" value="{{ old('size_note') }}" placeholder="Örn: Taraklı ayaklar için 1 numara büyük önerilir." 
                                   class="w-full bg-white border border-blue-200 rounded-lg px-3.5 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kısa Açıklama (Özet)</label>
                        <textarea name="short_description" rows="2" placeholder="Ürünün öne çıkan özellikleri..." 
                                  class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('short_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Detaylı Açıklama & Kalıp Bilgisi</label>
                        <textarea name="description" rows="5" placeholder="Kullanılan malzemeler, taban teknolojisi, kombin önerileri..." 
                                  class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3.5 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- SIZE BASED STOCK MATRIX -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <div>
                            <h3 class="font-sans font-bold text-sm text-[#111827] flex items-center">
                                <i class="fa-solid fa-boxes-stacked text-blue-600 mr-2"></i> Numara Bazlı Stok Miktarları
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Seçilebilecek ayakkabı numaralarını ve adetlerini girin.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        @foreach($sizes as $size)
                            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-3 text-center">
                                <span class="font-sans font-bold text-sm text-blue-600 block mb-1">
                                    {{ $size->size_number }}
                                </span>
                                <input type="number" min="0" max="999" name="stocks[{{ $size->id }}]" value="{{ old("stocks.{$size->id}", 0) }}" 
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg py-1 text-center text-xs font-bold text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-500">
                                <span class="text-[10px] text-gray-400 mt-1 block">Çift</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product Images Upload -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center">
                        <i class="fa-solid fa-images text-blue-600 mr-2"></i> Ürün Fotoğrafları
                    </h3>
                    <p class="text-xs text-gray-500">Birden fazla görsel seçebilirsiniz. İlk yüklenen fotoğraf ana kapak görseli olacaktır.</p>
                    
                    <input type="file" name="images[]" multiple accept="image/*" 
                           class="w-full bg-[#F8FAFC] border border-[#D1D5DB] rounded-lg p-3 text-xs text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

            </div>

            <!-- RIGHT: PRODUCT SETTINGS & STATUS (lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-6 sticky top-24">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3">
                        Yayınlama & Etiketler
                    </h3>

                    <div class="space-y-3 text-xs">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="font-semibold text-[#111827]">Sitede Yayında (Aktif)</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="font-semibold text-[#111827]">"Yeni Ürün" Olarak İşaretle</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="font-semibold text-[#111827]">"Öne Çıkan / Vitrin" Ürünü Yap</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-[#E5E7EB] space-y-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">SEO Meta Başlık</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Google arama başlığı..." 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">

                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider pt-2">SEO Meta Açıklama</label>
                        <textarea name="meta_description" rows="3" placeholder="Google arama açıklaması..." 
                                  class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('meta_description') }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-[#E5E7EB]">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-xs flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-check"></i>
                            <span>Ürünü Kaydet</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

    <script>
        function updateDiscountFromPercent() {
            const price = parseFloat(document.getElementById('productPrice').value) || 0;
            const percent = parseFloat(document.getElementById('discountPercent').value) || 0;
            const discountPriceInput = document.getElementById('discountPrice');
            const infoBox = document.getElementById('discountInfoBox');
            const infoText = document.getElementById('discountInfoText');

            if (price > 0 && percent > 0 && percent < 100) {
                const discounted = price * (1 - (percent / 100));
                discountPriceInput.value = discounted.toFixed(2);
                const saving = (price - discounted).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                infoBox.classList.remove('hidden');
                infoText.innerHTML = `🎉 <strong>%${percent} Kampanya İndirimi:</strong> Müşteri <strong>${saving} TL</strong> indirim kazanacak.`;
            } else {
                if (percent === 0 || !percent) {
                    discountPriceInput.value = '';
                }
                infoBox.classList.add('hidden');
            }
        }

        function updateDiscountFromPrice() {
            const price = parseFloat(document.getElementById('productPrice').value) || 0;
            const discounted = parseFloat(document.getElementById('discountPrice').value) || 0;
            const percentInput = document.getElementById('discountPercent');
            const infoBox = document.getElementById('discountInfoBox');
            const infoText = document.getElementById('discountInfoText');

            if (price > 0 && discounted > 0 && discounted < price) {
                const percent = Math.round(((price - discounted) / price) * 100);
                percentInput.value = percent;
                const saving = (price - discounted).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                infoBox.classList.remove('hidden');
                infoText.innerHTML = `🎉 <strong>%${percent} Kampanya İndirimi:</strong> Müşteri <strong>${saving} TL</strong> indirim kazanacak.`;
            } else {
                percentInput.value = '';
                infoBox.classList.add('hidden');
            }
        }

        function applyQuickDiscount(percent) {
            document.getElementById('discountPercent').value = percent > 0 ? percent : '';
            if (percent === 0) {
                document.getElementById('discountPrice').value = '';
                document.getElementById('discountInfoBox').classList.add('hidden');
            } else {
                updateDiscountFromPercent();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateDiscountFromPrice();
        });
    </script>

@endsection
