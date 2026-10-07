@extends('layouts.admin')

@section('title', 'Talep Detayı #' . $returnRequest->id . ' | Yönetim Paneli')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.returns.index') }}" class="text-gray-500 hover:text-gray-700 text-xs">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Taleplere Dön
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#111827] mt-1">İade / Değişim Talebi #{{ $returnRequest->id }}</h1>
        </div>
        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold
            {{ $returnRequest->status === 'tamamlandi' ? 'bg-emerald-100 text-emerald-700' : '' }}
            {{ $returnRequest->status === 'onaylandi' ? 'bg-indigo-100 text-indigo-700' : '' }}
            {{ $returnRequest->status === 'bekliyor' ? 'bg-amber-100 text-amber-700' : '' }}
            {{ $returnRequest->status === 'inceleniyor' ? 'bg-blue-100 text-blue-700' : '' }}
            {{ $returnRequest->status === 'reddedildi' ? 'bg-rose-100 text-rose-700' : '' }}
        ">
            {{ $returnRequest->status_label }}
        </span>
    </div>

    <!-- Request Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <!-- Request Content -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">Talep Detayları</h3>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 block">Talep Türü:</span>
                        <strong class="text-gray-900 uppercase text-sm">{{ $returnRequest->type_label }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Talep Tarihi:</span>
                        <strong class="text-gray-900">{{ $returnRequest->created_at->format('d.m.Y H:i') }}</strong>
                    </div>
                </div>

                <div class="text-xs space-y-1">
                    <span class="text-gray-500 block">Müşteri Açıklaması:</span>
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-800 leading-relaxed border border-gray-100">
                        {{ $returnRequest->reason }}
                    </div>
                </div>

                @if($returnRequest->requested_size)
                    <div class="text-xs">
                        <span class="text-gray-500 block">İstenen Değişim Numarası:</span>
                        <span class="inline-block mt-1 font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">
                            {{ $returnRequest->requested_size }} Numara
                        </span>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">İlgili Ürün</h3>
                <div class="flex items-center space-x-4">
                    <img src="{{ $returnRequest->orderItem->product_image ?? asset('favicon.ico') }}" class="w-16 h-16 rounded-xl object-cover bg-gray-50 border border-gray-200">
                    <div class="text-xs space-y-1">
                        <h4 class="font-bold text-gray-900 text-sm">{{ $returnRequest->orderItem->product_name ?? 'Ürün' }}</h4>
                        <p class="text-gray-500">Sipariş Edilen Beden: <strong class="text-gray-900">{{ $returnRequest->orderItem->size_number ?? '-' }}</strong></p>
                        <p class="text-gray-500">Adet: <strong class="text-gray-900">{{ $returnRequest->orderItem->quantity ?? 1 }}</strong> | Fiyat: <strong class="text-blue-600">{{ number_format($returnRequest->orderItem->total ?? 0, 2, ',', '.') }} TL</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action / Status Update Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">Durum Güncelle</h3>

                <form method="POST" action="{{ route('admin.returns.update-status', $returnRequest->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 mb-1.5">Talep Durumu</label>
                        <select name="status" id="status" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-blue-600 font-semibold">
                            <option value="bekliyor" {{ $returnRequest->status === 'bekliyor' ? 'selected' : '' }}>Bekliyor</option>
                            <option value="inceleniyor" {{ $returnRequest->status === 'inceleniyor' ? 'selected' : '' }}>İnceleniyor</option>
                            <option value="onaylandi" {{ $returnRequest->status === 'onaylandi' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="reddedildi" {{ $returnRequest->status === 'reddedildi' ? 'selected' : '' }}>Reddedildi</option>
                            <option value="tamamlandi" {{ $returnRequest->status === 'tamamlandi' ? 'selected' : '' }}>Tamamlandı (Stok Geri Al)</option>
                        </select>
                    </div>

                    <div>
                        <label for="admin_note" class="block text-xs font-semibold text-gray-700 mb-1.5">Yönetici Notu (Müşteri Görebilir)</label>
                        <textarea name="admin_note" id="admin_note" rows="3" placeholder="Müşteriye iletilecek açıklama..." class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-blue-600">{{ old('admin_note', $returnRequest->admin_note) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold text-xs rounded-xl transition-colors shadow-sm">
                        Durumu Kaydet
                    </button>
                </form>
            </div>

            <!-- Order Link -->
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5 text-xs space-y-2">
                <span class="text-gray-500 font-semibold block">Sipariş Bilgisi:</span>
                <p class="font-mono font-bold text-gray-900">{{ $returnRequest->order->order_number ?? '-' }}</p>
                <p class="text-gray-700">{{ $returnRequest->order->customer_name ?? '-' }} ({{ $returnRequest->order->customer_phone ?? '-' }})</p>
                @if($returnRequest->order)
                    <a href="{{ route('admin.orders.show', $returnRequest->order->id) }}" class="inline-block mt-2 text-blue-600 font-bold hover:underline">
                        Sipariş Detayına Git →
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
