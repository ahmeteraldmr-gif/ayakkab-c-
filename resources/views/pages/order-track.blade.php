@extends('layouts.app')

@section('title', 'Sipariş Takip | Yusuf Akboğa Ayakkabı')
@section('meta_description', 'Yusuf Akboğa Ayakkabı siparişinizin güncel durumunu, kargo takip numarasını ve teslimat sürecini kolayca sorgulayın.')

@section('content')
<div class="bg-gray-950 py-10 sm:py-14 text-white min-h-[75vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-400/10 text-amber-400 border border-amber-400/20 mb-4 shadow-lg shadow-amber-500/5">
                <i class="fa-solid fa-truck-fast text-lg"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Sipariş Takibi</h1>
            <p class="text-sm text-gray-400 mt-2">
                Sipariş numaranız ve siparişte kullandığınız telefon numaranız ile anlık durumunuzu sorgulayın.
            </p>
        </div>

        <!-- Search Form Card -->
        <div class="bg-gray-900/90 border border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xl mb-10 backdrop-blur-md">
            @if(isset($trackError) && $trackError)
                <div class="mb-6 bg-rose-950/50 border border-rose-800 text-rose-300 px-4 py-3.5 rounded-xl text-xs sm:text-sm flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-base flex-shrink-0"></i>
                    <span>{{ $trackError }}</span>
                </div>
            @elseif(session('track_error'))
                <div class="mb-6 bg-rose-950/50 border border-rose-800 text-rose-300 px-4 py-3.5 rounded-xl text-xs sm:text-sm flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-base flex-shrink-0"></i>
                    <span>{{ session('track_error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('order.track.submit') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                @csrf

                <div class="sm:col-span-5">
                    <label for="order_number" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Sipariş Numarası <span class="text-amber-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                            <i class="fa-solid fa-hashtag text-xs"></i>
                        </div>
                        <input type="text" 
                               name="order_number" 
                               id="order_number" 
                               value="{{ old('order_number', $searchedOrderNumber ?? '') }}" 
                               required 
                               placeholder="Örn: YSA-2026-102938"
                               class="w-full bg-gray-950 border border-gray-700 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-white rounded-xl pl-9 pr-3.5 py-3 text-sm placeholder-gray-600 focus:outline-none transition-colors">
                    </div>
                    @error('order_number')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="phone" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
                        Telefon Numarası <span class="text-amber-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </div>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $searchedPhone ?? '') }}" 
                               required 
                               placeholder="05XX XXX XX XX"
                               class="w-full bg-gray-950 border border-gray-700 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 text-white rounded-xl pl-9 pr-3.5 py-3 text-sm placeholder-gray-600 focus:outline-none transition-colors">
                    </div>
                    @error('phone')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3 flex items-end">
                    <button type="submit" class="w-full py-3 px-4 bg-amber-400 hover:bg-amber-500 text-gray-950 font-bold text-sm rounded-xl transition-all shadow-md shadow-amber-400/10 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span>Sorgula</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Result Details (If order is found) -->
        @if($order)
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-2xl animate-fade-up">
                
                <!-- Order Top Info Banner -->
                <div class="p-6 sm:p-8 bg-gradient-to-r from-gray-900 via-gray-850 to-gray-900 border-b border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-3">
                            <h2 class="text-lg sm:text-xl font-bold text-white">{{ $order->order_number }}</h2>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            Sipariş Tarihi: <span class="text-gray-300 font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                        </p>
                    </div>

                    <div class="text-left md:text-right">
                        <span class="text-xs text-gray-400 block">Toplam Tutar</span>
                        <span class="text-xl sm:text-2xl font-extrabold text-amber-400">{{ $order->formatted_total }}</span>
                    </div>
                </div>

                <!-- Visual Timeline -->
                <div class="p-6 sm:p-8 border-b border-gray-800">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6">Sipariş Aşamaları</h3>

                    @if($order->status === 'iptal')
                        <div class="bg-red-950/40 border border-red-900/60 rounded-xl p-5 text-center text-red-300 text-sm">
                            <i class="fa-solid fa-circle-xmark text-red-500 text-2xl mb-2 block"></i>
                            <strong class="block font-bold text-red-200">Bu Sipariş İptal Edilmiştir</strong>
                            <p class="text-xs text-red-400 mt-1">İptal veya iade süreciyle ilgili sorularınız için bizimle iletişime geçebilirsiniz.</p>
                        </div>
                    @else
                        @php
                            $steps = [
                                'yeni' => ['title' => 'Sipariş Alındı', 'desc' => 'Ödeme onaylandı', 'icon' => 'fa-clipboard-check'],
                                'hazirlaniyor' => ['title' => 'Hazırlanıyor', 'desc' => 'Paketleniyor', 'icon' => 'fa-box-open'],
                                'kargoda' => ['title' => 'Kargoya Verildi', 'desc' => 'Yolda', 'icon' => 'fa-truck-fast'],
                                'tamamlandi' => ['title' => 'Teslim Edildi', 'desc' => 'Sipariş tamamlandı', 'icon' => 'fa-circle-check'],
                            ];

                            $statusOrder = ['yeni' => 1, 'hazirlaniyor' => 2, 'kargoda' => 3, 'tamamlandi' => 4];
                            $currentLevel = $statusOrder[$order->status] ?? 1;
                        @endphp

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative">
                            @foreach($steps as $key => $step)
                                @php
                                    $stepLevel = $statusOrder[$key];
                                    $isPassed = $stepLevel <= $currentLevel;
                                    $isCurrent = $stepLevel === $currentLevel;
                                @endphp
                                <div class="flex flex-col items-center text-center p-3 rounded-xl {{ $isCurrent ? 'bg-gray-800/60 border border-amber-400/30 shadow-lg shadow-amber-400/5' : '' }}">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold mb-2 transition-all {{ $isPassed ? 'bg-amber-400 text-gray-950 shadow-md shadow-amber-400/20' : 'bg-gray-800 text-gray-500' }}">
                                        <i class="fa-solid {{ $step['icon'] }}"></i>
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold {{ $isPassed ? 'text-white' : 'text-gray-500' }}">{{ $step['title'] }}</span>
                                    <span class="text-[11px] text-gray-400 mt-0.5">{{ $step['desc'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Cargo Tracking Card (If shipped) -->
                @if(in_array($order->status, ['kargoda', 'tamamlandi']) && ($order->shipping_company || $order->tracking_number))
                    <div class="p-6 sm:p-8 bg-purple-950/20 border-b border-gray-800">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-12 h-12 rounded-xl bg-purple-600/20 text-purple-400 border border-purple-500/30 flex items-center justify-center text-lg flex-shrink-0">
                                    <i class="fa-solid fa-truck-ramp-box"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">Kargo Bilgileri</h4>
                                    <p class="text-xs text-purple-300 mt-0.5">
                                        {{ $order->shipping_company ?? 'Kargo Şirketi' }} 
                                        @if($order->tracking_number)
                                            • Takip No: <span class="font-mono font-bold text-white bg-gray-950 px-2 py-0.5 rounded border border-gray-800">{{ $order->tracking_number }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($order->effective_tracking_url)
                                <a href="{{ $order->effective_tracking_url }}" target="_blank" class="py-2.5 px-5 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-purple-600/20 inline-flex items-center space-x-2">
                                    <span>Kargoyu Takip Et</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Order Items -->
                <div class="p-6 sm:p-8">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Sipariş Edilen Ürünler</h3>
                    
                    <div class="divide-y divide-gray-800">
                        @foreach($order->items as $item)
                            <div class="py-3.5 flex items-center justify-between gap-4">
                                <div class="flex items-center space-x-3.5 min-w-0">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-14 h-14 object-cover rounded-xl border border-gray-800 flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-gray-800 flex items-center justify-center text-gray-600 flex-shrink-0">
                                            <i class="fa-solid fa-shoe-prints"></i>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h4 class="text-xs sm:text-sm font-semibold text-white truncate">{{ $item->product_name }}</h4>
                                        <div class="flex items-center space-x-2 text-xs text-gray-400 mt-1">
                                            <span class="px-2 py-0.5 rounded bg-gray-800 text-gray-300 font-bold text-[11px]">{{ $item->size_number }} Numara</span>
                                            <span>• {{ $item->quantity }} Adet</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-xs sm:text-sm font-bold text-white">{{ number_format((float)$item->total, 2, ',', '.') }} ₺</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Financial Summary Breakdown -->
                    <div class="mt-6 pt-4 border-t border-gray-800 max-w-xs ml-auto text-xs space-y-1.5">
                        <div class="flex justify-between text-gray-400">
                            <span>Ara Toplam:</span>
                            <span class="text-gray-200 font-medium">{{ number_format((float)$order->subtotal, 2, ',', '.') }} ₺</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-400">
                                <span>İndirim ({{ $order->coupon_code }}):</span>
                                <span class="font-medium">-{{ number_format((float)$order->discount_amount, 2, ',', '.') }} ₺</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-400">
                            <span>Kargo:</span>
                            <span class="text-gray-200 font-medium">{{ $order->shipping_cost == 0 ? 'Ücretsiz' : number_format((float)$order->shipping_cost, 2, ',', '.') . ' ₺' }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-white pt-2 border-t border-gray-800">
                            <span>Toplam Tutar:</span>
                            <span class="text-amber-400">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <!-- WhatsApp Support Action Button -->
                    @php
                        $wpPhone = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '905321234567'));
                        $wpMsg = urlencode("Merhaba, #" . $order->order_number . " numaralı siparişim hakkında bilgi almak istiyorum.");
                        $wpUrl = "https://wa.me/{$wpPhone}?text={$wpMsg}";
                    @endphp
                    <div class="mt-8 pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-emerald-950/20 p-4 rounded-2xl border border-emerald-900/40">
                        <div class="text-xs space-y-0.5">
                            <h4 class="font-bold text-white flex items-center">
                                <i class="fa-brands fa-whatsapp text-emerald-400 mr-2 text-base"></i> Siparişinizle ilgili yardıma mı ihtiyacınız var?
                            </h4>
                            <p class="text-gray-400">Müşteri temsilcimize doğrudan WhatsApp üzerinden bağlanabilirsiniz.</p>
                        </div>
                        <a href="{{ $wpUrl }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-emerald-600/20 flex items-center space-x-2 flex-shrink-0">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>WhatsApp'tan Destek Al</span>
                        </a>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection
