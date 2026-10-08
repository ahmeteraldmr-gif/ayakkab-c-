@extends('layouts.app')

@section('title', 'Hesap Özeti | VELORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-white/50 mb-6">
        <a href="{{ route('home') }}" class="hover:text-accent">Ana Sayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-semibold">Hesabım</span>
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
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl bg-accent text-dark font-bold shadow-md">
                        <i class="fa-solid fa-gauge w-5 text-sm"></i>
                        <span>Hesap Özeti</span>
                    </a>
                    <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-box-open w-5 text-sm text-accent"></i>
                        <span>Siparişlerim</span>
                        <span class="ml-auto bg-white/10 text-white/70 px-2 py-0.5 rounded-full text-[10px]">{{ $recentOrders->count() }}</span>
                    </a>
                    <a href="{{ route('customer.addresses') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-location-dot w-5 text-sm text-accent"></i>
                        <span>Adres Defterim</span>
                    </a>
                    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-user-pen w-5 text-sm text-accent"></i>
                        <span>Profil & Güvenlik</span>
                    </a>
                    <div class="pt-4 border-t border-white/10">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 rounded-xl text-rose-400 hover:bg-white/5 transition-colors text-left">
                                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-sm"></i>
                                <span>Çıkış Yap</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-[#1c1c1c] to-[#161616] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold text-accent uppercase tracking-widest">Hoş Geldiniz</span>
                    <h1 class="font-display font-black text-2xl text-white mt-1">{{ auth()->user()->name }}</h1>
                    <p class="text-xs text-white/60 mt-1">Siparişlerinizi takip edebilir, adreslerinizi ve hesap tercihlerinizi güncelleyebilirsiniz.</p>
                </div>
                <a href="{{ route('products.index') }}" class="px-5 py-3 bg-accent hover:bg-accent-light text-dark font-bold text-xs rounded-xl transition-colors shadow flex items-center space-x-2 flex-shrink-0">
                    <i class="fa-solid fa-shoe-prints"></i>
                    <span>Alışverişe Başla</span>
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-[#161616] border border-white/10 rounded-2xl p-5 shadow-lg">
                    <div class="text-accent text-lg mb-2"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="text-2xl font-bold text-white">{{ $stats['total_orders'] }}</div>
                    <div class="text-xs text-white/50 mt-1">Toplam Sipariş</div>
                </div>
                <div class="bg-[#161616] border border-white/10 rounded-2xl p-5 shadow-lg">
                    <div class="text-amber-400 text-lg mb-2"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="text-2xl font-bold text-white">{{ $stats['active_orders'] }}</div>
                    <div class="text-xs text-white/50 mt-1">Aktif Sipariş</div>
                </div>
                <div class="bg-[#161616] border border-white/10 rounded-2xl p-5 shadow-lg">
                    <div class="text-emerald-400 text-lg mb-2"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="text-2xl font-bold text-white">{{ $stats['delivered_orders'] }}</div>
                    <div class="text-xs text-white/50 mt-1">Tamamlanan</div>
                </div>
                <div class="bg-[#161616] border border-white/10 rounded-2xl p-5 shadow-lg">
                    <div class="text-sky-400 text-lg mb-2"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="text-2xl font-bold text-white">{{ $stats['saved_addresses'] }}</div>
                    <div class="text-xs text-white/50 mt-1">Kayıtlı Adres</div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h3 class="font-display font-bold text-base text-white flex items-center">
                        <i class="fa-solid fa-box-open text-accent mr-2.5"></i> Son Siparişleriniz
                    </h3>
                    <a href="{{ route('customer.orders') }}" class="text-xs text-accent hover:underline flex items-center">
                        <span>Tümünü Gör</span>
                        <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                    </a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-10 space-y-3">
                        <div class="w-16 h-16 rounded-full bg-white/5 text-white/40 flex items-center justify-center mx-auto text-2xl">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <p class="text-sm text-white/60">Henüz kayıtlı bir siparişiniz bulunmuyor.</p>
                        <a href="{{ route('products.index') }}" class="inline-block px-5 py-2.5 bg-accent hover:bg-accent-light text-dark font-bold text-xs rounded-xl transition-colors">
                            Koleksiyonu Keşfet
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-white/5">
                        @foreach($recentOrders as $order)
                            <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-3">
                                        <span class="font-bold text-white text-sm font-mono">{{ $order->order_number }}</span>
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
                                    <p class="text-xs text-white/50">{{ $order->created_at->format('d.m.Y H:i') }} • {{ $order->items->count() }} Ürün</p>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-accent">{{ number_format($order->total_amount, 2, ',', '.') }} TL</div>
                                        <div class="text-[11px] text-white/40">{{ $order->payment_method_label }}</div>
                                    </div>
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl text-xs font-semibold transition-colors">
                                        Detay
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Default Address Snippet -->
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl">
                <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-4">
                    <h3 class="font-display font-bold text-base text-white flex items-center">
                        <i class="fa-solid fa-map-pin text-accent mr-2.5"></i> Birincil Teslimat Adresi
                    </h3>
                    <a href="{{ route('customer.addresses') }}" class="text-xs text-accent hover:underline">
                        Adresleri Yönet
                    </a>
                </div>

                @if($defaultAddress)
                    <div class="p-4 bg-dark/60 rounded-xl border border-white/5 space-y-1 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white text-sm">{{ $defaultAddress->title }}</span>
                            <span class="text-[10px] bg-accent/20 text-accent font-semibold px-2 py-0.5 rounded">Varsayılan</span>
                        </div>
                        <p class="text-white/80 font-medium">{{ $defaultAddress->full_name }} • {{ $defaultAddress->phone }}</p>
                        <p class="text-white/60 leading-relaxed">{{ $defaultAddress->address }} - {{ $defaultAddress->district }} / {{ $defaultAddress->city }}</p>
                    </div>
                @else
                    <p class="text-xs text-white/50">Henüz kayıtlı bir adresiniz yok. Hızlı sipariş vermek için hemen adres ekleyebilirsiniz.</p>
                    <a href="{{ route('customer.addresses') }}" class="inline-block mt-3 px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl text-xs font-semibold transition-colors">
                        + Yeni Adres Ekle
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
