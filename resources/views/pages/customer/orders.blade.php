@extends('layouts.app')

@section('title', 'Sipariş Geçmişim | VELORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-white/50 mb-6">
        <a href="{{ route('home') }}" class="hover:text-accent">Ana Sayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-accent">Hesabım</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-semibold">Siparişlerim</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6 sticky top-24">
                <div class="flex items-center space-x-3 pb-6 border-b border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-accent/15 text-accent font-bold text-lg flex items-center justify-center border border-accent/30">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h2 class="font-display font-bold text-sm text-white truncate">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <nav class="space-y-1.5 text-xs font-medium">
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-gauge w-5 text-sm text-accent"></i>
                        <span>Hesap Özeti</span>
                    </a>
                    <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-3 rounded-xl bg-accent text-dark font-bold shadow-md">
                        <i class="fa-solid fa-box-open w-5 text-sm"></i>
                        <span>Siparişlerim</span>
                    </a>
                    <a href="{{ route('customer.addresses') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-location-dot w-5 text-sm text-accent"></i>
                        <span>Adres Defterim</span>
                    </a>
                    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-user-pen w-5 text-sm text-accent"></i>
                        <span>Profil & Güvenlik</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Orders List Content -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                        <h1 class="font-display font-bold text-xl text-white">Sipariş Geçmişim</h1>
                        <p class="text-xs text-white/50 mt-0.5">Daha önce verdiğiniz tüm siparişlerin durumunu ve detaylarını inceleyebilirsiniz.</p>
                    </div>
                    <span class="text-xs font-semibold text-accent bg-accent/10 px-3 py-1 rounded-full border border-accent/20">
                        {{ $orders->total() }} Sipariş
                    </span>
                </div>

                @if($orders->isEmpty())
                    <div class="text-center py-16 space-y-4">
                        <div class="w-20 h-20 rounded-full bg-white/5 text-white/40 flex items-center justify-center mx-auto text-3xl">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-white">Kayıtlı Sipariş Bulunamadı</h3>
                        <p class="text-xs text-white/60 max-w-sm mx-auto">Henüz hesabınızla verilmiş bir sipariş bulunmuyor. Yeni sezon modellerimize göz atabilirsiniz.</p>
                        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-accent hover:bg-accent-light text-dark font-bold text-xs rounded-xl transition-colors shadow">
                            Alışverişe Başla
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="bg-dark/50 border border-white/5 rounded-2xl p-5 sm:p-6 space-y-4 hover:border-accent/40 transition-colors">
                                <!-- Order Header -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/5">
                                    <div class="space-y-1">
                                        <div class="flex items-center space-x-3">
                                            <span class="font-mono font-bold text-sm text-white">{{ $order->order_number }}</span>
                                            <span class="text-[11px] px-2.5 py-0.5 rounded-full font-semibold
                                                {{ $order->status === 'teslim_edildi' ? 'bg-emerald-500/20 text-emerald-400' : '' }}
                                                {{ $order->status === 'kargolandi' ? 'bg-sky-500/20 text-sky-400' : '' }}
                                                {{ $order->status === 'hazirlaniyor' ? 'bg-amber-500/20 text-amber-400' : '' }}
                                                {{ $order->status === 'yeni' ? 'bg-indigo-500/20 text-indigo-400' : '' }}
                                                {{ $order->status === 'iptal_edildi' ? 'bg-rose-500/20 text-rose-400' : '' }}
                                            ">
                                                {{ $order->status_label }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-white/50">
                                            <span>Sipariş Tarihi: {{ $order->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <div class="sm:text-right">
                                            <div class="text-base font-bold text-accent">{{ number_format($order->total_amount, 2, ',', '.') }} TL</div>
                                            <div class="text-[11px] text-white/50">{{ $order->payment_method_label }}</div>
                                        </div>
                                        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-4 py-2 bg-accent/15 hover:bg-accent text-accent hover:text-dark rounded-xl text-xs font-bold transition-colors">
                                            Sipariş Detayı
                                        </a>
                                    </div>
                                </div>

                                <!-- Items Preview -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center space-x-3 bg-[#111111] p-2.5 rounded-xl border border-white/5">
                                            <img src="{{ $item->product_image ?? asset('favicon.ico') }}" alt="{{ $item->product_name }}" class="w-12 h-12 rounded-lg object-cover bg-white/5">
                                            <div class="overflow-hidden text-xs">
                                                <h4 class="font-bold text-white truncate">{{ $item->product_name }}</h4>
                                                <p class="text-white/50 text-[11px]">Beden: {{ $item->size_number }} • Adet: {{ $item->quantity }}</p>
                                                <p class="text-accent font-semibold">{{ number_format($item->total, 2, ',', '.') }} TL</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Shipping Info if Available -->
                                @if($order->tracking_number)
                                    <div class="pt-2 flex items-center justify-between text-xs bg-sky-500/10 border border-sky-500/20 p-3 rounded-xl text-sky-300">
                                        <div class="flex items-center space-x-2">
                                            <i class="fa-solid fa-truck-fast"></i>
                                            <span>Kargo: <strong>{{ $order->shipping_company ?? 'Kargo Firması' }}</strong> (Takip: {{ $order->tracking_number }})</span>
                                        </div>
                                        @if($order->tracking_url)
                                            <a href="{{ $order->tracking_url }}" target="_blank" class="text-xs font-bold text-sky-200 underline">Kargom Nerede?</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
