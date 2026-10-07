@extends('layouts.admin')

@section('title', 'İade ve Değişim Talepleri | Yönetim Paneli')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#111827]">İade & Değişim Talepleri</h1>
            <p class="text-xs text-[#6B7280] mt-1">Müşterilerden gelen iade ve beden değişim taleplerini yönetin.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-2">
        <a href="{{ route('admin.returns.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ !request('status') ? 'bg-[#2563EB] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Tümü ({{ $counts['total'] }})
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'bekliyor']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'bekliyor' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Bekleyenler ({{ $counts['bekliyor'] }})
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'onaylandi']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'onaylandi' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Onaylananlar ({{ $counts['onaylandi'] }})
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'tamamlandi']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'tamamlandi' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Tamamlananlar ({{ $counts['tamamlandi'] }})
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'reddedildi']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'reddedildi' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Reddedilenler ({{ $counts['reddedildi'] }})
        </a>
    </div>

    <!-- Returns Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($returns->isEmpty())
            <div class="text-center py-16 text-gray-500 text-sm">
                <i class="fa-solid fa-rotate-left text-3xl text-gray-300 mb-2 block"></i>
                Kayıtlı talep bulunamadı.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                            <th class="py-3.5 px-4">Talep No / Sipariş</th>
                            <th class="py-3.5 px-4">Müşteri</th>
                            <th class="py-3.5 px-4">Tür</th>
                            <th class="py-3.5 px-4">Ürün Bilgisi</th>
                            <th class="py-3.5 px-4">Durum</th>
                            <th class="py-3.5 px-4">Tarih</th>
                            <th class="py-3.5 px-4 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($returns as $req)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-gray-900">#{{ $req->id }}</div>
                                    <div class="font-mono text-gray-500 text-[11px]">{{ $req->order->order_number ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-900">{{ $req->order->customer_name ?? '-' }}</div>
                                    <div class="text-gray-500 text-[11px]">{{ $req->order->customer_phone ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $req->type === 'return' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $req->type_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-gray-900">{{ $req->orderItem->product_name ?? 'Ürün' }}</div>
                                    <div class="text-gray-500 text-[11px]">Beden: {{ $req->orderItem->size_number ?? '-' }} | İstenen: {{ $req->requested_size ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $req->status === 'tamamlandi' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $req->status === 'onaylandi' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                        {{ $req->status === 'bekliyor' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $req->status === 'inceleniyor' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $req->status === 'reddedildi' ? 'bg-rose-100 text-rose-700' : '' }}
                                    ">
                                        {{ $req->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-gray-500 text-[11px]">
                                    {{ $req->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.returns.show', $req->id) }}" class="px-3 py-1.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">
                                        İncele
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200">
                {{ $returns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
