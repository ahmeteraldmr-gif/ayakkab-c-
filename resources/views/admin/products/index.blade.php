@extends('layouts.admin')

@section('title', 'Ürün Yönetimi | VELORA Yönetim Paneli')
@section('page_title', 'Ürün Yönetimi')

@section('content')

    <div class="space-y-6">
        
        <!-- Quick Campaign Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.products.index') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ !request('campaign') ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                Tüm Ürünler <span class="ml-1 opacity-80 font-normal">({{ $totalProductsCount }})</span>
            </a>

            <a href="{{ route('admin.products.index', array_merge(request()->query(), ['campaign' => 'discounted'])) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 {{ request('campaign') === 'discounted' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-amber-800 border border-amber-200 hover:bg-amber-50' }}">
                <span>🔥 Kampanyalı & İndirimli Ürünler</span>
                <span class="px-1.5 py-0.5 rounded-md {{ request('campaign') === 'discounted' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-900' }} text-[11px] font-extrabold">{{ $discountedCount }}</span>
            </a>

            <a href="{{ route('admin.products.index', array_merge(request()->query(), ['campaign' => 'featured'])) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 {{ request('campaign') === 'featured' ? 'bg-purple-600 text-white shadow-xs' : 'bg-white text-purple-800 border border-purple-200 hover:bg-purple-50' }}">
                <span>⭐ Öne Çıkanlar (Vitrin)</span>
            </a>
        </div>

        <!-- Header Actions & Filters -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Search & Filters Form -->
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                @if(request('campaign'))
                    <input type="hidden" name="campaign" value="{{ request('campaign') }}">
                @endif

                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Model veya SKU ara..." 
                           class="bg-white border border-[#D1D5DB] rounded-lg pl-8 pr-3 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 w-44 sm:w-60">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                </div>

                <select name="category_id" class="bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="brand_id" class="bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Tüm Markalar</option>
                    @foreach($brands as $brn)
                        <option value="{{ $brn->id }}" {{ request('brand_id') == $brn->id ? 'selected' : '' }}>{{ $brn->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 border border-gray-200">
                    <i class="fa-solid fa-filter text-[11px] text-gray-500"></i>
                    <span>Filtrele</span>
                </button>

                @if(request()->hasAny(['q', 'category_id', 'brand_id', 'campaign']))
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-red-600 hover:underline font-medium">Temizle</a>
                @endif
            </form>

            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center space-x-1.5 flex-shrink-0">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Yeni Ürün Ekle</span>
            </a>

        </div>

        <!-- Products Table -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-[13px]">
                    <thead class="bg-[#F9FAFB] text-gray-500 font-semibold border-b border-[#E5E7EB] text-[11px] uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Görsel</th>
                            <th class="py-3.5 px-4">Ürün Bilgisi</th>
                            <th class="py-3.5 px-4">Kategori & Marka</th>
                            <th class="py-3.5 px-4">Fiyat / Kampanya</th>
                            <th class="py-3.5 px-4">Toplam Stok</th>
                            <th class="py-3.5 px-4">Durum</th>
                            <th class="py-3.5 px-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F4F6]">
                        @forelse($products as $prod)
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                <!-- Image -->
                                <td class="py-3 px-4 w-16">
                                    <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-contain bg-[#F8FAFC] p-1 border border-[#E5E7EB]">
                                </td>

                                <!-- Title & SKU -->
                                <td class="py-3 px-4">
                                    <h4 class="font-bold text-[#111827] text-sm">{{ $prod->name }}</h4>
                                    <div class="flex items-center space-x-2 text-[11px] text-gray-400 mt-0.5 font-mono">
                                        <span>SKU: {{ $prod->sku }}</span>
                                        @if($prod->color)
                                            <span>• Renk: {{ $prod->color }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-1.5 mt-1">
                                        @if($prod->is_featured)
                                            <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-bold">⭐ Öne Çıkan</span>
                                        @endif
                                        @if($prod->is_new)
                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold">Yeni</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Category & Brand -->
                                <td class="py-3 px-4 text-gray-600">
                                    <span class="font-semibold text-[#111827] block">{{ $prod->category?->name ?? '-' }}</span>
                                    <span class="text-[11px] text-blue-600 font-medium">{{ $prod->brand?->name ?? 'YSA' }}</span>
                                </td>

                                <!-- Price & Campaign -->
                                <td class="py-3 px-4">
                                    @if($prod->has_discount)
                                        <div class="flex items-center space-x-1.5 mb-0.5">
                                            <span class="px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-extrabold">
                                                -%{{ $prod->discount_percent }} İndirim
                                            </span>
                                        </div>
                                        <span class="line-through text-gray-400 text-[11px] block">{{ $prod->formatted_price }}</span>
                                        <span class="font-extrabold text-rose-600 text-sm">{{ $prod->formatted_effective_price }}</span>
                                    @else
                                        <span class="font-bold text-[#111827] text-sm">{{ $prod->formatted_price }}</span>
                                    @endif
                                </td>

                                <!-- Stock Status -->
                                <td class="py-3 px-4">
                                    @php $totStock = $prod->total_stock; @endphp
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $totStock > 3 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($totStock > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                        {{ $totStock }} Çift
                                    </span>
                                </td>

                                <!-- Active Status -->
                                <td class="py-3 px-4">
                                    @if($prod->is_active)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-200 text-[11px] font-bold">
                                            Pasif
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3 px-4 text-right space-x-1.5">
                                    <a href="{{ route('product.detail', $prod->slug) }}" target="_blank" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors inline-block" title="Sitede Gör">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors inline-block" title="Düzenle">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $prod->id) }}" class="inline-block" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Sil">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-400 text-xs">
                                    Kayıtlı ürün bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#E5E7EB] bg-[#F9FAFB]">
                {{ $products->links('pagination::tailwind') }}
            </div>
        </div>

    </div>

@endsection
