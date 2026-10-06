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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Satış Fiyatı (TL) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0.00" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">İndirimli Fiyat (TL, Opsiyonel)</label>
                            <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" placeholder="Boş bırakılabilir" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
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

@endsection
