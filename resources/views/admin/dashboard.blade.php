@extends('layouts.admin')

@section('title', 'Dashboard | Yusuf Akboğa Yönetim Paneli')
@section('page_title')
Dashboard &amp; Mağaza Özeti
@endsection

@section('header_actions')
<a href="{{ route('admin.products.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition-all shadow-xs flex items-center space-x-1.5">
    <i class="fa-solid fa-plus text-[11px]"></i>
    <span class="hidden sm:inline">Yeni Ürün Ekle</span>
    <span class="sm:hidden">Ürün</span>
</a>
@endsection

@section('content')

    <div class="space-y-7">
        
        <!-- 1. ÜST İSTATİSTİK KARTLARI (BEYAZ KARTLAR & TEMİZ SAAS TASARIMI) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            
            <!-- 1. Bugünkü Satış -->
            <div class="bg-white border border-[#E5E7EB] hover:border-blue-500/40 rounded-2xl p-4.5 relative overflow-hidden transition-all duration-200 shadow-xs hover:shadow-sm group">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11.5px] font-bold text-gray-500 uppercase tracking-wider">Bugünkü Satış</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>
                <div class="font-sans font-extrabold text-xl sm:text-2xl text-[#111827] tracking-tight">
                    {{ number_format($todayRevenue, 2, ',', '.') }} ₺
                </div>
                <span class="text-[12px] text-gray-500 mt-1 block">Bugün <strong class="text-gray-900">{{ $todayOrders }}</strong> sipariş</span>
            </div>

            <!-- 2. Bu Ayki Ciro -->
            <div class="bg-white border border-[#E5E7EB] hover:border-blue-500/40 rounded-2xl p-4.5 relative overflow-hidden transition-all duration-200 shadow-xs hover:shadow-sm group">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11.5px] font-bold text-gray-500 uppercase tracking-wider">Bu Ayki Ciro</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
                <div class="font-sans font-extrabold text-xl sm:text-2xl text-[#111827] tracking-tight">
                    {{ number_format($monthRevenue, 2, ',', '.') }} ₺
                </div>
                <span class="text-[12px] text-gray-500 mt-1 block">Cari ay net ciro</span>
            </div>

            <!-- 3. Toplam Sipariş -->
            <div class="bg-white border border-[#E5E7EB] hover:border-blue-500/40 rounded-2xl p-4.5 relative overflow-hidden transition-all duration-200 shadow-xs hover:shadow-sm group">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11.5px] font-bold text-gray-500 uppercase tracking-wider">Toplam Sipariş</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                </div>
                <div class="font-sans font-extrabold text-xl sm:text-2xl text-[#111827] tracking-tight">
                    {{ $totalOrders }}
                </div>
                <span class="text-[12px] text-amber-600 mt-1 block font-medium"><strong class="font-bold">{{ $pendingOrders }}</strong> bekleyen sipariş</span>
            </div>

            <!-- 4. Toplam Ürün -->
            <div class="bg-white border border-[#E5E7EB] hover:border-blue-500/40 rounded-2xl p-4.5 relative overflow-hidden transition-all duration-200 shadow-xs hover:shadow-sm group">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11.5px] font-bold text-gray-500 uppercase tracking-wider">Toplam Ürün</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-shoe-prints"></i>
                    </div>
                </div>
                <div class="font-sans font-extrabold text-xl sm:text-2xl text-[#111827] tracking-tight">
                    {{ $totalProducts }}
                </div>
                <span class="text-[12px] text-gray-500 mt-1 block">Aktif katalog modelleri</span>
            </div>

            <!-- 5. Kupon Kullanımları -->
            <div class="bg-white border border-[#E5E7EB] hover:border-blue-500/40 rounded-2xl p-4.5 relative overflow-hidden transition-all duration-200 shadow-xs hover:shadow-sm group">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11.5px] font-bold text-gray-500 uppercase tracking-wider">Kupon Kullanımı</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                </div>
                <div class="font-sans font-extrabold text-xl sm:text-2xl text-[#111827] tracking-tight">
                    {{ $totalCouponUsage }}
                </div>
                <span class="text-[12px] text-gray-500 mt-1 block">Toplam indirim kullanımı</span>
            </div>

            <!-- 6. Kritik Stok Uyarısı -->
            <div class="bg-white border border-[#E5E7EB] hover:border-red-500/40 rounded-2xl p-4.5 relative overflow-hidden transition-all duration-200 shadow-xs hover:shadow-sm group">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11.5px] font-bold text-gray-500 uppercase tracking-wider">Kritik Stok</span>
                    <div class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <div class="font-sans font-extrabold text-xl sm:text-2xl text-[#111827] tracking-tight">
                    {{ $lowStockCount + $outOfStockCount }}
                </div>
                <span class="text-[12px] text-red-600 mt-1 block font-medium"><strong class="font-bold">{{ $outOfStockCount }}</strong> adet tükenmiş</span>
            </div>

        </div>

        <!-- 2. ORTA BÖLÜM: SATIŞ GRAFİĞİ (SOL %65) & KRİTİK STOK LİSTESİ (SAĞ %35) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">
            
            <!-- SOL %65: SATIŞ & CİRO TREND GRAFİĞİ -->
            <div class="lg:col-span-8 bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-5">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#E5E7EB] pb-4">
                    <div>
                        <h3 class="font-sans font-bold text-base text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-chart-area text-blue-600"></i>
                            <span>Satış & Ciro Performansı</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Son 7 günlük sipariş ve ciro hareketleri</p>
                    </div>

                    <div class="flex items-center space-x-4 text-xs font-medium">
                        <span class="flex items-center text-gray-700">
                            <span class="w-3 h-3 rounded-full bg-blue-600 mr-1.5 inline-block"></span> Ciro (₺)
                        </span>
                        <span class="flex items-center text-gray-500">
                            <span class="w-3 h-3 rounded-full bg-slate-400 mr-1.5 inline-block"></span> Sipariş Adedi
                        </span>
                    </div>
                </div>

                <!-- Chart Canvas Container -->
                <div class="relative w-full h-64 sm:h-72">
                    <canvas id="salesChart"></canvas>
                </div>

                <!-- Mini Quick Analytics Ribbons -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-[#E5E7EB]">
                    <div class="bg-[#F8FAFC] rounded-xl p-3.5 border border-[#E2E8F0]">
                        <span class="text-[11px] font-semibold text-gray-500 block uppercase tracking-wider">Son 7 Gün Sipariş</span>
                        <span class="font-sans font-bold text-base text-[#111827] mt-0.5 block">{{ $last7DaysOrders }} Sipariş</span>
                    </div>
                    <div class="bg-[#F8FAFC] rounded-xl p-3.5 border border-[#E2E8F0]">
                        <span class="text-[11px] font-semibold text-gray-500 block uppercase tracking-wider">En Popüler Marka</span>
                        <span class="font-sans font-bold text-sm text-blue-600 mt-0.5 block truncate">
                            {{ $topBrand?->name ?? 'Nike / Adidas' }}
                        </span>
                    </div>
                    <div class="bg-[#F8FAFC] rounded-xl p-3.5 border border-[#E2E8F0]">
                        <span class="text-[11px] font-semibold text-gray-500 block uppercase tracking-wider">Bekleyen Stok Talebi</span>
                        <span class="font-sans font-bold text-sm text-purple-600 mt-0.5 block">
                            {{ $pendingStockAlertsCount }} Müşteri Talebi
                        </span>
                    </div>
                </div>

            </div>

            <!-- SAĞ %35: KRİTİK STOK LİSTESİ -->
            <div class="lg:col-span-4 bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3.5">
                    <div>
                        <h3 class="font-sans font-bold text-sm text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                            <span>Kritik Stok Uyarısı</span>
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">3 ve altı kalan ürünler</p>
                    </div>
                    <a href="{{ route('admin.stocks.index', ['stock_status' => 'low']) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-blue-50 text-blue-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                        <span>Yönet</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if($lowStockItems->count() > 0)
                    <div class="space-y-2.5 max-h-[340px] overflow-y-auto pr-1">
                        @foreach($lowStockItems as $lItem)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] hover:bg-[#F1F5F9] transition-colors">
                                <div class="min-w-0 pr-3">
                                    <h4 class="font-bold text-[#111827] text-xs truncate" title="{{ $lItem->product->name }}">
                                        {{ $lItem->product->name }}
                                    </h4>
                                    <div class="flex items-center space-x-2 text-[11px] text-gray-500 mt-1">
                                        <span>Numara: <strong class="text-gray-900">{{ $lItem->size?->size_number }}</strong></span>
                                        <span>•</span>
                                        <span class="text-blue-600 font-medium">{{ $lItem->product->brand?->name ?? 'YSA' }}</span>
                                    </div>
                                </div>

                                <!-- Visual Stock Badge Hierarchy -->
                                @if($lItem->stock == 0)
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-100 text-red-700 border border-red-200 shrink-0">
                                        Tükendi
                                    </span>
                                @elseif($lItem->stock <= 2)
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200 shrink-0">
                                        {{ $lItem->stock }} Adet
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 shrink-0">
                                        3 Adet
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 text-gray-400 text-xs">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-2 block"></i>
                        Tüm ürünlerin stok durumu yeterli seviyede.
                    </div>
                @endif
            </div>

        </div>

        <!-- 3. EN ÇOK SATAN 5 ÜRÜN & EN ÇOK SATAN NUMARALAR -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">
            
            <!-- EN ÇOK SATAN 5 ÜRÜN TABLOSU -->
            <div class="lg:col-span-8 bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3.5">
                    <div>
                        <h3 class="font-sans font-bold text-base text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-fire text-amber-500"></i>
                            <span>En Çok Satan 5 Ürün</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Gerçek sipariş satış adetleri ve ciro katkısı</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        <span>Tüm Ürünler</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if($top5Products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#F9FAFB] text-gray-500 border-b border-[#E5E7EB] font-semibold uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-3.5">Ürün</th>
                                    <th class="py-3 px-3.5 text-center">Satılan Adet</th>
                                    <th class="py-3 px-3.5 text-right">Toplam Satış Tutarı</th>
                                    <th class="py-3 px-3.5 text-right">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#F3F4F6]">
                                @foreach($top5Products as $idx => $tProd)
                                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                                        <td class="py-3 px-3.5">
                                            <div class="flex items-center space-x-3">
                                                <span class="w-6 h-6 rounded-full bg-amber-50 text-amber-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                    #{{ $idx + 1 }}
                                                </span>
                                                @if($tProd->product_image)
                                                    <img src="{{ $tProd->product_image }}" alt="{{ $tProd->product_name }}" class="w-10 h-10 object-cover rounded-lg border border-gray-100 flex-shrink-0">
                                                @endif
                                                <span class="font-bold text-gray-900 truncate max-w-xs">{{ $tProd->product_name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3.5 text-center">
                                            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold">
                                                {{ $tProd->total_qty }} Adet
                                            </span>
                                        </td>
                                        <td class="py-3 px-3.5 text-right font-extrabold text-gray-900">
                                            {{ number_format((float)$tProd->total_revenue, 2, ',', '.') }} ₺
                                        </td>
                                        <td class="py-3 px-3.5 text-right">
                                            @if($tProd->product_id)
                                                <a href="{{ route('admin.products.edit', $tProd->product_id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors" title="Düzenle">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 text-xs">
                        Henüz tamamlanmış satış verisi bulunmuyor.
                    </div>
                @endif
            </div>

            <!-- EN ÇOK SATAN NUMARALAR WIDGET -->
            <div class="lg:col-span-4 bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="border-b border-[#E5E7EB] pb-3.5">
                    <h3 class="font-sans font-bold text-sm text-[#111827] flex items-center gap-2">
                        <i class="fa-solid fa-ruler-horizontal text-purple-600"></i>
                        <span>En Çok Satan Numaralar</span>
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">Müşterilerin en çok tercih ettiği ayak bedenleri</p>
                </div>

                @if($topSizes->count() > 0)
                    <div class="space-y-3">
                        @foreach($topSizes as $s)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 font-extrabold text-sm flex items-center justify-center border border-purple-200">
                                        {{ $s->size_number }}
                                    </div>
                                    <span class="text-xs font-semibold text-gray-800">{{ $s->size_number }} Numara</span>
                                </div>
                                <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-100">
                                    {{ $s->total_qty }} Adet Satıldı
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 text-xs">
                        Henüz beden satış verisi yok.
                    </div>
                @endif
            </div>

        </div>

        <!-- 4. ALT BÖLÜM: SON SİPARİŞLER (SOL %65) & HIZLI İŞLEMLER + MESAJLAR (SAĞ %35) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">
            
            <!-- SOL %65: SON SİPARİŞLER TABLOSU -->
            <div class="lg:col-span-8 bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3.5">
                    <div>
                        <h3 class="font-sans font-bold text-base text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-blue-600"></i>
                            <span>Son Siparişler</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Mağazadan verilen en son müşteri siparişleri</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        <span>Tüm Siparişler</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-[13px]">
                            <thead class="bg-[#F9FAFB] text-gray-500 border-b border-[#E5E7EB] font-semibold text-[11px] uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-3.5">Sipariş No</th>
                                    <th class="py-3 px-3.5">Müşteri</th>
                                    <th class="py-3 px-3.5">Tutar</th>
                                    <th class="py-3 px-3.5">Durum</th>
                                    <th class="py-3 px-3.5">Tarih</th>
                                    <th class="py-3 px-3.5 text-right">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#F3F4F6]">
                                @foreach($recentOrders as $ord)
                                    <tr class="hover:bg-[#F8FAFC] transition-colors cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $ord->id) }}'">
                                        <td class="py-3.5 px-3.5 font-mono font-bold text-blue-600">
                                            {{ $ord->order_number }}
                                        </td>
                                        <td class="py-3.5 px-3.5">
                                            <span class="font-bold text-[#111827] block">{{ $ord->customer_name }}</span>
                                            <span class="text-[11px] text-gray-400">{{ $ord->city ?? 'İstanbul' }}</span>
                                        </td>
                                        <td class="py-3.5 px-3.5 font-extrabold text-[#111827]">
                                            {{ $ord->formatted_total }}
                                        </td>
                                        <td class="py-3.5 px-3.5">
                                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold inline-block {{ $ord->status_badge_class }}">
                                                {{ $ord->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-3.5 text-gray-500 text-xs">
                                            {{ $ord->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="py-3.5 px-3.5 text-right" onclick="event.stopPropagation()">
                                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="p-2 bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 rounded-lg transition-colors inline-block" title="Detay">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 text-xs">
                        Henüz sipariş kaydı bulunmuyor.
                    </div>
                @endif
            </div>

            <!-- SAĞ %35: HIZLI İŞLEMLER + GELEN MESAJLAR -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Hızlı İşlemler Kartı -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-3.5">
                    <div class="border-b border-[#E5E7EB] pb-3">
                        <h3 class="font-sans font-bold text-sm text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-blue-600"></i>
                            <span>Hızlı İşlemler</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <a href="{{ route('admin.products.create') }}" class="p-3 rounded-xl bg-[#F8FAFC] hover:bg-blue-50 text-gray-700 hover:text-blue-700 font-semibold transition-all border border-[#E2E8F0] hover:border-blue-200 flex flex-col items-center text-center group">
                            <i class="fa-solid fa-plus text-blue-600 group-hover:scale-110 text-base mb-1.5 transition-transform"></i>
                            <span>Yeni Ürün</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="p-3 rounded-xl bg-[#F8FAFC] hover:bg-blue-50 text-gray-700 hover:text-blue-700 font-semibold transition-all border border-[#E2E8F0] hover:border-blue-200 flex flex-col items-center text-center group">
                            <i class="fa-solid fa-bag-shopping text-blue-600 group-hover:scale-110 text-base mb-1.5 transition-transform"></i>
                            <span>Siparişler</span>
                        </a>
                        <a href="{{ route('admin.stocks.index') }}" class="p-3 rounded-xl bg-[#F8FAFC] hover:bg-blue-50 text-gray-700 hover:text-blue-700 font-semibold transition-all border border-[#E2E8F0] hover:border-blue-200 flex flex-col items-center text-center group">
                            <i class="fa-solid fa-boxes-stacked text-blue-600 group-hover:scale-110 text-base mb-1.5 transition-transform"></i>
                            <span>Stok Güncelle</span>
                        </a>
                        <a href="{{ route('admin.coupons.create') }}" class="p-3 rounded-xl bg-[#F8FAFC] hover:bg-blue-50 text-gray-700 hover:text-blue-700 font-semibold transition-all border border-[#E2E8F0] hover:border-blue-200 flex flex-col items-center text-center group">
                            <i class="fa-solid fa-ticket text-blue-600 group-hover:scale-110 text-base mb-1.5 transition-transform"></i>
                            <span>Kupon Ekle</span>
                        </a>
                    </div>
                </div>

                <!-- Gelen Mesajlar Özeti -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 sm:p-6 shadow-xs space-y-3.5">
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-envelope text-blue-600"></i>
                            <h4 class="font-sans font-bold text-sm text-[#111827]">Gelen Mesajlar</h4>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 font-bold text-xs border border-blue-100">
                            {{ $unreadMessagesCount }} Okunmamış
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed">Müşterilerinizin iletişim formundan gönderdiği soru ve talepleri inceleyin.</p>
                    <a href="{{ route('admin.messages.index') }}" class="block w-full text-center py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-xs">
                        Mesajları İncele & Yanıtla
                    </a>
                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        const labels = @json($chartLabels);
        const revenueData = @json($chartRevenue);
        const ordersData = @json($chartOrders);

        // Chart gradient for revenue (Modern Blue #2563EB)
        const chartCtx = ctx.getContext('2d');
        const revenueGradient = chartCtx.createLinearGradient(0, 0, 0, 240);
        revenueGradient.addColorStop(0, 'rgba(37, 99, 235, 0.18)');
        revenueGradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ciro (₺)',
                        data: revenueData,
                        borderColor: '#2563EB',
                        backgroundColor: revenueGradient,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563EB',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Sipariş Sayısı',
                        data: ordersData,
                        borderColor: '#94A3B8',
                        backgroundColor: 'transparent',
                        borderWidth: 1.8,
                        borderDash: [4, 4],
                        pointBackgroundColor: '#64748B',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.35,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleColor: '#FFFFFF',
                        bodyColor: '#F8FAFC',
                        borderColor: '#E2E8F0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y') {
                                    return 'Ciro: ' + context.parsed.y.toLocaleString('tr-TR', { minimumFractionDigits: 2 }) + ' ₺';
                                }
                                return 'Sipariş: ' + context.parsed.y + ' Adet';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#F1F5F9',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748B',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: '#F1F5F9',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748B',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return value.toLocaleString('tr-TR') + ' ₺';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: false,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
