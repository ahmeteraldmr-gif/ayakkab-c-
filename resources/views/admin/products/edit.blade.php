@extends('layouts.admin')

@section('title', 'Ürünü Düzenle | VELORA Yönetim Paneli')
@section('page_title', 'Ürünü Düzenle: ' . $product->name)

@section('content')

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

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
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kategori</label>
                            <select name="category_id" class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Kategori Seçin</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Marka</label>
                            <select name="brand_id" class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Marka Seçin</option>
                                @foreach($brands as $brn)
                                    <option value="{{ $brn->id }}" {{ old('brand_id', $product->brand_id) == $brn->id ? 'selected' : '' }}>{{ $brn->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cinsiyet <span class="text-red-500">*</span></label>
                            <select name="gender" required class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="unisex" {{ old('gender', $product->gender) === 'unisex' ? 'selected' : '' }}>Unisex</option>
                                <option value="erkek" {{ old('gender', $product->gender) === 'erkek' ? 'selected' : '' }}>Erkek</option>
                                <option value="kadin" {{ old('gender', $product->gender) === 'kadin' ? 'selected' : '' }}>Kadın</option>
                                <option value="cocuk" {{ old('gender', $product->gender) === 'cocuk' ? 'selected' : '' }}>Çocuk</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ürün Kodu (SKU) <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] font-mono focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Renk Adı</label>
                            <input type="text" name="color" value="{{ old('color', $product->color) }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Renk Kodu (HEX)</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" id="colorPicker" value="{{ old('color_code', $product->color_code ?? '#000000') }}" onchange="document.getElementById('colorHex').value = this.value" class="w-9 h-8 rounded border border-gray-300 cursor-pointer bg-transparent">
                                <input type="text" name="color_code" id="colorHex" value="{{ old('color_code', $product->color_code ?? '#000000') }}" 
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
                                <input type="number" step="0.01" name="price" id="productPrice" value="{{ old('price', $product->price) }}" required 
                                       oninput="updateDiscountFromPercent()"
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] font-semibold focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">
                                    Kampanya İndirim Oranı (%)
                                </label>
                                <div class="relative">
                                    <input type="number" step="1" min="0" max="99" id="discountPercent" placeholder="Örn: 20" 
                                           value="{{ $product->has_discount ? $product->discount_percent : '' }}"
                                           oninput="updateDiscountFromPercent()"
                                           class="w-full bg-white border border-amber-300 rounded-lg pl-8 pr-3.5 py-2.5 text-xs text-amber-900 font-bold focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-400/20">
                                    <span class="absolute left-3 top-2.5 text-amber-600 font-bold text-xs">%</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Kampanyalı Fiyat (TL, Opsiyonel)
                                </label>
                                <input type="number" step="0.01" name="discount_price" id="discountPrice" value="{{ old('discount_price', $product->discount_price) }}" placeholder="Hesaplanan tutar" 
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
                        <div id="discountInfoBox" class="{{ $product->has_discount ? '' : 'hidden' }} p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span id="discountInfoText">
                                @if($product->has_discount)
                                    🎉 <strong>%{{ $product->discount_percent }} Kampanya İndirimi:</strong> Müşteri <strong>{{ number_format($product->price - $product->discount_price, 2, ',', '.') }} TL</strong> indirim kazanacak.
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <div>
                            <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-2">Kalıp / Beden Türü</label>
                            <select name="fit_type" class="w-full bg-white border border-blue-200 rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600">
                                <option value="tam_kalip" {{ old('fit_type', $product->fit_type ?? 'tam_kalip') === 'tam_kalip' ? 'selected' : '' }}>Tam Kalıp (Kendi numaranızı alabilirsiniz)</option>
                                <option value="dar_kalip" {{ old('fit_type', $product->fit_type) === 'dar_kalip' ? 'selected' : '' }}>Dar Kalıp (1 numara büyük önerilir)</option>
                                <option value="genis_kalip" {{ old('fit_type', $product->fit_type) === 'genis_kalip' ? 'selected' : '' }}>Geniş Kalıp (1 numara küçük tercih edilebilir)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-2">Özel Kalıp / Beden Notu</label>
                            <input type="text" name="size_note" value="{{ old('size_note', $product->size_note) }}" placeholder="Örn: Taraklı ayaklar için 1 numara büyük önerilir." 
                                   class="w-full bg-white border border-blue-200 rounded-lg px-3.5 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kısa Açıklama (Özet)</label>
                        <textarea name="short_description" rows="2" 
                                  class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Detaylı Açıklama & Kalıp Bilgisi</label>
                        <textarea name="description" rows="5" 
                                  class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- SIZE BASED STOCK MATRIX -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <div>
                            <h3 class="font-sans font-bold text-sm text-[#111827] flex items-center">
                                <i class="fa-solid fa-boxes-stacked text-blue-600 mr-2"></i> Numara Bazlı Stok Miktarları
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Ayakkabının her numarasına ait mevcut stok adetlerini belirleyin.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        @foreach($sizes as $size)
                            @php
                                $stockVal = $currentStocks[$size->id] ?? 0;
                            @endphp
                            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-3 text-center">
                                <span class="font-sans font-bold text-sm text-blue-600 block mb-1">
                                    {{ $size->size_number }} Numara
                                </span>
                                <input type="number" min="0" max="999" name="stocks[{{ $size->id }}]" value="{{ old("stocks.{$size->id}", $stockVal) }}" 
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg py-1 text-center text-xs font-bold text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-500">
                                <span class="text-[10px] text-gray-400 mt-1 block">Çift</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Existing Images & Upload New -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-6">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center">
                        <i class="fa-solid fa-images text-blue-600 mr-2"></i> Fotoğraf Galerisi
                    </h3>

                    <!-- Current Images -->
                    @if($product->images->count() > 0)
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-3">Mevcut Fotoğraflar</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach($product->images as $img)
                                    <div class="relative bg-[#F8FAFC] rounded-xl p-2 border {{ $img->is_primary ? 'border-blue-600 ring-2 ring-blue-500/20' : 'border-[#E5E7EB]' }} group">
                                        <img src="{{ $img->url }}" alt="" class="w-full h-24 object-contain rounded-lg">
                                        
                                        @if($img->is_primary)
                                            <span class="absolute top-2 left-2 bg-blue-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                                Kapak
                                            </span>
                                        @endif

                                        <div class="mt-2 flex items-center justify-between pt-1 border-t border-[#E5E7EB]">
                                            @if(!$img->is_primary)
                                                <button type="submit" formaction="{{ route('admin.products.set-primary-image', $img->id) }}" formmethod="POST" class="text-[11px] text-blue-600 hover:underline font-medium">
                                                    Kapak Yap
                                                </button>
                                            @else
                                                <span class="text-[11px] text-gray-400 font-medium">Ana Görsel</span>
                                            @endif

                                            <button type="submit" formaction="{{ route('admin.products.delete-image', $img->id) }}" formmethod="POST" onclick="return confirm('Bu görseli silmek istediğinize emin misiniz?');" class="text-red-500 hover:text-red-700 text-xs p-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Add New Images -->
                    <div class="pt-4 border-t border-[#E5E7EB]">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Yeni Fotoğraf Ekle</label>
                        <input type="file" name="images[]" multiple accept="image/*" 
                               class="w-full bg-[#F8FAFC] border border-[#D1D5DB] rounded-lg p-3 text-xs text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

            </div>

            <!-- RIGHT: PRODUCT SETTINGS & STATUS (lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Performance Statistics -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center justify-between">
                        <span><i class="fa-solid fa-chart-line text-blue-600 mr-2"></i> Ürün Performansı</span>
                        <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="text-xs text-blue-600 hover:underline font-normal">
                            Sitede Gör <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </h3>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-[#F8FAFC] rounded-xl border border-[#E2E8F0]">
                            <span class="text-[11px] font-medium text-gray-500 block">Görüntülenme</span>
                            <strong class="text-base font-bold text-[#111827]">{{ number_format($viewCount ?? 0) }}</strong>
                        </div>
                        <div class="p-3 bg-[#F8FAFC] rounded-xl border border-[#E2E8F0]">
                            <span class="text-[11px] font-medium text-gray-500 block">Toplam Satış</span>
                            <strong class="text-base font-bold text-emerald-600">{{ number_format($totalUnitsSold ?? 0) }} adet</strong>
                        </div>
                        <div class="p-3 bg-[#F8FAFC] rounded-xl border border-[#E2E8F0]">
                            <span class="text-[11px] font-medium text-gray-500 block">Elde Edilen Ciro</span>
                            <strong class="text-base font-bold text-blue-600">{{ number_format($totalRevenueGenerated ?? 0, 2, ',', '.') }} ₺</strong>
                        </div>
                        <div class="p-3 bg-[#F8FAFC] rounded-xl border border-[#E2E8F0]">
                            <span class="text-[11px] font-medium text-gray-500 block">Mevcut Stok</span>
                            <strong class="text-base font-bold {{ ($totalAvailableStock ?? 0) <= 5 ? 'text-amber-600' : 'text-[#111827]' }}">
                                {{ number_format($totalAvailableStock ?? 0) }} adet
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-6 sticky top-24">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3">
                        Yayınlama & Etiketler
                    </h3>

                    <div class="space-y-3 text-xs">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="font-semibold text-[#111827]">Sitede Yayında (Aktif)</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="font-semibold text-[#111827]">"Yeni Ürün" Olarak İşaretle</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="font-semibold text-[#111827]">"Öne Çıkan / Vitrin" Ürünü Yap</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-[#E5E7EB] space-y-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">SEO Meta Başlık</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" 
                               class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">

                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider pt-2">SEO Meta Açıklama</label>
                        <textarea name="meta_description" rows="3" 
                                  class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-[#E5E7EB]">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-xs flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-rotate"></i>
                            <span>Değişiklikleri Güncelle</span>
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
    </script>

@endsection
