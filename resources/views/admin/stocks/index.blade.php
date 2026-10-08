@extends('layouts.admin')

@section('title', 'Numara Bazlı Stok Yönetimi | VELORA Yönetim Paneli')
@section('page_title', 'Numara Bazlı Stok Matrisi')

@section('content')

    <div class="space-y-6">
        
        <!-- Filter and Search Bar -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.stocks.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Model veya SKU ara..." 
                           class="bg-white border border-[#D1D5DB] rounded-lg pl-8 pr-3 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 w-48 sm:w-60">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                </div>

                <select name="category_id" class="bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="stock_status" class="bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Tüm Stok Durumları</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Kritik Stoklu Olanlar (<=3)</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Tükenenler (0 Stok)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 border border-gray-200">
                    <i class="fa-solid fa-filter text-[11px] text-gray-500"></i>
                    <span>Filtrele</span>
                </button>
            </form>

            <button type="button" onclick="document.getElementById('bulkStockForm').submit()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center space-x-1.5 flex-shrink-0">
                <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                <span>Tüm Stok Değişikliklerini Kaydet</span>
            </button>
        </div>

        <!-- Stock Matrix Table -->
        <form id="bulkStockForm" method="POST" action="{{ route('admin.stocks.bulk-update') }}">
            @csrf

            <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-[13px]">
                        <thead class="bg-[#F9FAFB] text-gray-500 font-semibold border-b border-[#E5E7EB]">
                            <tr>
                                <th class="py-3.5 px-4 min-w-[220px]">Ayakkabı Modeli</th>
                                @foreach($sizes as $sz)
                                    <th class="py-3.5 px-2 text-center min-w-[65px] text-blue-600 font-bold">
                                        {{ $sz->size_number }}
                                    </th>
                                @endforeach
                                <th class="py-3.5 px-4 text-center min-w-[90px]">Toplam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F4F6]">
                            @forelse($products as $prod)
                                @php
                                    $prodStocks = $prod->sizeStocks->pluck('stock', 'size_id')->toArray();
                                    $rowTotal = array_sum($prodStocks);
                                @endphp
                                <tr class="hover:bg-[#F8FAFC] transition-colors">
                                    <!-- Product Info -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" class="w-10 h-10 rounded-lg object-contain bg-[#F8FAFC] p-1 border border-[#E5E7EB] flex-shrink-0">
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-[#111827] text-xs truncate max-w-xs">{{ $prod->name }}</h4>
                                                <span class="text-[10px] text-gray-400 font-mono">{{ $prod->sku }} • {{ $prod->brand?->name ?? 'YSA' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Size Stocks Matrix Inputs -->
                                    @foreach($sizes as $sz)
                                        @php
                                            $currVal = $prodStocks[$sz->id] ?? 0;
                                        @endphp
                                        <td class="py-2.5 px-1.5 text-center">
                                            <input type="number" 
                                                   name="stocks[{{ $prod->id }}][{{ $sz->id }}]" 
                                                   value="{{ $currVal }}" 
                                                   min="0" max="999"
                                                   onchange="quickSaveStock({{ $prod->id }}, {{ $sz->id }}, this.value)"
                                                   class="w-14 bg-white border {{ $currVal == 0 ? 'border-red-300 text-red-700 bg-red-50/50' : ($currVal <= 3 ? 'border-amber-300 text-amber-800 bg-amber-50/50' : 'border-[#D1D5DB] text-[#111827]') }} rounded-lg py-1 text-center text-xs font-bold focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-500">
                                        </td>
                                    @endforeach

                                    <!-- Total Stock -->
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $rowTotal > 3 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($rowTotal > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                            {{ $rowTotal }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($sizes) + 2 }}" class="py-10 text-center text-gray-400 text-xs">
                                        Filtre kriterlerine uygun ürün bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-[#E5E7EB] bg-[#F9FAFB]">
                    {{ $products->links('pagination::tailwind') }}
                </div>
            </div>
        </form>

    </div>

@endsection

@push('scripts')
<script>
    function quickSaveStock(productId, sizeId, stock) {
        fetch('{{ route("admin.stocks.quick-update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                size_id: sizeId,
                stock: stock
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // quick flash
            }
        });
    }
</script>
@endpush
