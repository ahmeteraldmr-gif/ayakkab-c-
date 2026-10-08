@extends('layouts.admin')

@section('title', 'Sipariş Yönetimi | VELORA Yönetim Paneli')
@section('page_title', 'Sipariş Yönetimi')

@section('content')

    <div class="space-y-6">
        
        <!-- Status Tabs & Search -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
            
            <!-- Status Tabs -->
            <div class="flex flex-wrap gap-2 border-b border-[#E5E7EB] pb-4 text-xs font-medium">
                <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ !request('status') ? 'bg-blue-600 text-white font-semibold shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Tümü ({{ $statusCounts['all'] }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'yeni']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ request('status') === 'yeni' ? 'bg-blue-600 text-white font-semibold shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Yeni ({{ $statusCounts['yeni'] }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'hazirlaniyor']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ request('status') === 'hazirlaniyor' ? 'bg-amber-500 text-white font-semibold shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Hazırlanıyor ({{ $statusCounts['hazirlaniyor'] }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'kargoda']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ request('status') === 'kargoda' ? 'bg-purple-600 text-white font-semibold shadow-xs' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    Kargoda ({{ $statusCounts['kargoda'] }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'tamamlandi']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ request('status') === 'tamamlandi' ? 'bg-emerald-600 text-white font-semibold shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Tamamlandı ({{ $statusCounts['tamamlandi'] }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'iptal']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ request('status') === 'iptal' ? 'bg-red-600 text-white font-semibold shadow-xs' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                    İptal ({{ $statusCounts['iptal'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-3">
                <div class="relative w-full sm:w-80">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Sipariş no, müşteri, telefon veya şehir..." 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg pl-8 pr-3 py-2 text-xs text-[#111827] placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                </div>
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-xs font-semibold transition-colors border border-gray-200">
                    Ara
                </button>
            </form>

        </div>

        <!-- Orders Table -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-[13px]">
                    <thead class="bg-[#F9FAFB] text-gray-500 font-semibold border-b border-[#E5E7EB] text-[11px] uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Sipariş No</th>
                            <th class="py-3.5 px-4">Müşteri</th>
                            <th class="py-3.5 px-4">İçerik (Ürün / Numara)</th>
                            <th class="py-3.5 px-4">Ödeme</th>
                            <th class="py-3.5 px-4">Tutar</th>
                            <th class="py-3.5 px-4">Durum</th>
                            <th class="py-3.5 px-4">Tarih</th>
                            <th class="py-3.5 px-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3F4F6]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                <!-- Order Number -->
                                <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                                    {{ $order->order_number }}
                                </td>

                                <!-- Customer -->
                                <td class="py-3.5 px-4">
                                    <strong class="text-[#111827] block">{{ $order->customer_name }}</strong>
                                    <span class="text-[11px] text-gray-500">{{ $order->customer_phone }}</span>
                                    <span class="text-[11px] text-gray-400 block">{{ $order->district }} / {{ $order->city }}</span>
                                </td>

                                <!-- Items -->
                                <td class="py-3.5 px-4 text-gray-600">
                                    <div class="space-y-1">
                                        @foreach($order->items as $it)
                                            <div class="truncate max-w-xs text-[11px]">
                                                • {{ $it->product_name }} (<strong class="text-blue-600 font-semibold">{{ $it->size_number }}</strong> x {{ $it->quantity }})
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Payment Method -->
                                <td class="py-3.5 px-4 text-gray-600 capitalize">
                                    <span class="block font-semibold text-[#111827]">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                    <span class="text-[11px] text-gray-400">{{ $order->payment_status }}</span>
                                </td>

                                <!-- Total -->
                                <td class="py-3.5 px-4 font-extrabold text-[#111827] text-sm">
                                    {{ $order->formatted_total }}
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="py-3.5 px-4 text-gray-500 text-xs">
                                    {{ $order->created_at->format('d.m.Y H:i') }}
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 text-right space-x-1.5">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors inline-block" title="Sipariş Detayı & Durum">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" class="inline-block" onsubmit="return confirm('Bu siparişi silmek istediğinize emin misiniz?');">
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
                                <td colspan="8" class="py-10 text-center text-gray-400 text-xs">
                                    Sipariş kaydı bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#E5E7EB] bg-[#F9FAFB]">
                {{ $orders->links('pagination::tailwind') }}
            </div>
        </div>

    </div>

@endsection
