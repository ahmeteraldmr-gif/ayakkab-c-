@extends('layouts.app')

@section('title', 'Siparişiniz Alındı | VELORA')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-black/5 shadow-xl space-y-8 text-center">
            
            <!-- Success Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-3xl shadow-inner animate-bounce">
                <i class="fa-solid fa-check"></i>
            </div>

            <!-- Title & Order Code -->
            <div class="space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Siparişiniz Başarıyla Oluşturuldu</span>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-dark">
                    Teşekkür Ederiz, {{ $order->customer_name }}!
                </h1>
                <p class="text-black/60 text-sm max-w-lg mx-auto">
                    Siparişiniz sistemimize kaydedildi ve paketleme için hazırlandı. Kargonuz yola çıktığında bilgilendirme yapılacaktır.
                </p>
                <div class="inline-block mt-4 px-5 py-2.5 bg-dark text-white rounded-2xl border border-accent/40 font-mono text-sm font-bold">
                    Sipariş No: <span class="text-accent">{{ $order->order_number }}</span>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="bg-[#F7F7F5] rounded-2xl p-6 text-left border border-black/5 space-y-6">
                
                <h3 class="font-display font-bold text-base text-dark border-b border-black/10 pb-3 flex items-center">
                    <i class="fa-solid fa-receipt text-accent mr-2"></i> Sipariş Özeti ve Ürünler
                </h3>

                <!-- Items -->
                <div class="space-y-3 divide-y divide-black/5">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between pt-3 first:pt-0">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-14 h-14 rounded-xl bg-white border border-black/5 p-1 object-contain">
                                <div>
                                    <h4 class="text-xs font-bold text-dark">{{ $item->product_name }}</h4>
                                    <p class="text-[11px] text-black/60">Numara: <strong class="text-dark">{{ $item->size_number }}</strong> • Adet: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-dark">{{ $item->formatted_total }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-black/10 text-xs">
                    <div>
                        <span class="text-black/50 block">Teslimat Adresi:</span>
                        <strong class="text-dark block">{{ $order->customer_name }}</strong>
                        <p class="text-black/70">{{ $order->address }}</p>
                        <p class="text-black/70">{{ $order->district }} / {{ $order->city }}</p>
                        <p class="text-black/70">Tel: {{ $order->customer_phone }}</p>
                    </div>

                    <div>
                        <span class="text-black/50 block">Ödeme ve Durum:</span>
                        <strong class="text-dark block capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</strong>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-[11px] font-bold {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                        <p class="text-black/70 mt-2 font-bold text-sm">
                            Toplam: {{ $order->formatted_total }}
                        </p>
                    </div>
                </div>

            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-8 py-3.5 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md">
                    Ana Sayfaya Dön
                </a>
                <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-black/10 hover:border-black text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all">
                    Alışverişe Devam Et
                </a>
            </div>

        </div>
    </div>

@endsection
