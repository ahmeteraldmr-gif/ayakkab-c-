@extends('layouts.admin')

@section('title', 'Stok Hareket Geçmişi | VELORA')
@section('page_title', 'Stok Hareketleri & Denetim Kayıtları')

@section('header_actions')
    <a href="{{ route('admin.stocks.index') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">
        <i class="fa-solid fa-boxes-stacked mr-1"></i> Stok Matrisine Dön
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-xs">
        <form method="GET" action="{{ route('admin.stocks.movements') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Ürün</label>
                <select name="product_id" class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-blue-600 focus:outline-none">
                    <option value="">Tüm Ürünler</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Numara</label>
                <select name="size_id" class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-blue-600 focus:outline-none">
                    <option value="">Tüm Numaralar</option>
                    @foreach($sizes as $sz)
                        <option value="{{ $sz->id }}" {{ request('size_id') == $sz->id ? 'selected' : '' }}>{{ $sz->size_number }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">İşlem Türü</label>
                <select name="type" class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-blue-600 focus:outline-none">
                    <option value="">Tüm İşlemler</option>
                    <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>Sipariş Düşümü</option>
                    <option value="order_cancel" {{ request('type') === 'order_cancel' ? 'selected' : '' }}>Sipariş İptali</option>
                    <option value="manual_update" {{ request('type') === 'manual_update' ? 'selected' : '' }}>Manuel Düzenleme</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Stok Sayımı</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Başlangıç Tarihi</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Bitiş Tarihi</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-blue-600 focus:outline-none">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filtrele</span>
                </button>
                @if(request()->hasAny(['product_id', 'size_id', 'type', 'date_from', 'date_to']))
                    <a href="{{ route('admin.stocks.movements') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-xl" title="Filtreyi Temizle">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-gray-800">Hareket Geçmişi</h3>
            <span class="text-xs text-gray-500">Toplam {{ $movements->total() }} kayıt</span>
        </div>

        @if($movements->isEmpty())
            <div class="p-12 text-center text-gray-500 text-xs">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-gray-400 mb-2 block"></i>
                Kayıtlı stok hareketi bulunamadı.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider">
                            <th class="p-4">Tarih</th>
                            <th class="p-4">Ürün & Numara</th>
                            <th class="p-4">İşlem Türü</th>
                            <th class="p-4">Önceki Stok</th>
                            <th class="p-4">Değişim</th>
                            <th class="p-4">Kalan Stok</th>
                            <th class="p-4">Açıklama / Referans</th>
                            <th class="p-4">İşlemi Yapan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        @foreach($movements as $m)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4 text-gray-500 whitespace-nowrap">
                                    {{ $m->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-900">{{ $m->product?->name ?? 'Silinmiş Ürün' }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $m->size?->size_number ?? '-' }} Numara • SKU: {{ $m->product?->sku ?? '-' }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $m->type_badge_class }}">
                                        {{ $m->type_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-600 font-mono">
                                    {{ $m->quantity_before }}
                                </td>
                                <td class="p-4 font-mono font-bold">
                                    @if($m->quantity_change > 0)
                                        <span class="text-emerald-600">+{{ $m->quantity_change }}</span>
                                    @elseif($m->quantity_change < 0)
                                        <span class="text-rose-600">{{ $m->quantity_change }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-900 font-mono font-bold">
                                    {{ $m->quantity_after }}
                                </td>
                                <td class="p-4 text-gray-600">
                                    <div>{{ $m->reason }}</div>
                                    @if($m->reference_type && $m->reference_id)
                                        <div class="text-[10px] text-gray-400 font-mono">{{ $m->reference_type }} #{{ $m->reference_id }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-500">
                                    {{ $m->user?->name ?? 'Sistem / Müşteri' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $movements->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
