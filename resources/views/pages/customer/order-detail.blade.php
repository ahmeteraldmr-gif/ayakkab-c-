@extends('layouts.app')

@section('title', 'Sipariş Detayı #' . $order->order_number . ' | VELORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-white/50 mb-6">
        <a href="{{ route('home') }}" class="hover:text-accent">Ana Sayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-accent">Hesabım</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('customer.orders') }}" class="hover:text-accent">Siparişlerim</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-semibold">#{{ $order->order_number }}</span>
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

        <!-- Order Detail Content -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Order Header Card -->
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
                    <div>
                        <span class="text-xs font-semibold text-accent uppercase tracking-widest">Sipariş Bilgisi</span>
                        <h1 class="font-mono font-bold text-2xl text-white mt-1">#{{ $order->order_number }}</h1>
                        <p class="text-xs text-white/50 mt-1">{{ $order->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-xs px-3.5 py-1.5 rounded-full font-bold
                            {{ $order->status === 'teslim_edildi' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}
                            {{ $order->status === 'kargolandi' ? 'bg-sky-500/20 text-sky-400 border border-sky-500/30' : '' }}
                            {{ $order->status === 'hazirlaniyor' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}
                            {{ $order->status === 'yeni' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30' : '' }}
                            {{ $order->status === 'iptal_edildi' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : '' }}
                        ">
                            {{ $order->status_label }}
                        </span>

                        @if($order->status === 'teslim_edildi')
                            <button type="button" onclick="openReturnModal()" class="px-4 py-2 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold transition-colors flex items-center space-x-1.5">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>İade / Değişim Talebi Oluştur</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Timeline for Active Orders -->
                @if($order->status !== 'iptal_edildi')
                    <div class="py-4">
                        <h4 class="text-xs font-bold text-white/80 uppercase tracking-wider mb-6">Sipariş Durum Takibi</h4>
                        <div class="grid grid-cols-4 gap-2 relative">
                            <!-- Line backdrop -->
                            <div class="absolute top-1/2 left-0 right-0 h-1 bg-white/10 -translate-y-1/2 z-0"></div>
                            
                            <!-- Steps -->
                            @php
                                $stepOrder = ['yeni' => 1, 'hazirlaniyor' => 2, 'kargolandi' => 3, 'teslim_edildi' => 4];
                                $currentStep = $stepOrder[$order->status] ?? 1;
                            @endphp

                            <div class="text-center relative z-10">
                                <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $currentStep >= 1 ? 'bg-accent text-dark ring-4 ring-dark' : 'bg-[#222] text-white/40' }}">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <span class="block text-[11px] font-semibold mt-2 {{ $currentStep >= 1 ? 'text-accent' : 'text-white/40' }}">Sipariş Alındı</span>
                            </div>

                            <div class="text-center relative z-10">
                                <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $currentStep >= 2 ? 'bg-accent text-dark ring-4 ring-dark' : 'bg-[#222] text-white/40' }}">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <span class="block text-[11px] font-semibold mt-2 {{ $currentStep >= 2 ? 'text-accent' : 'text-white/40' }}">Hazırlanıyor</span>
                            </div>

                            <div class="text-center relative z-10">
                                <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $currentStep >= 3 ? 'bg-accent text-dark ring-4 ring-dark' : 'bg-[#222] text-white/40' }}">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <span class="block text-[11px] font-semibold mt-2 {{ $currentStep >= 3 ? 'text-accent' : 'text-white/40' }}">Kargoda</span>
                            </div>

                            <div class="text-center relative z-10">
                                <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $currentStep >= 4 ? 'bg-emerald-500 text-dark ring-4 ring-dark' : 'bg-[#222] text-white/40' }}">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="block text-[11px] font-semibold mt-2 {{ $currentStep >= 4 ? 'text-emerald-400' : 'text-white/40' }}">Teslim Edildi</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-xl text-xs flex items-center space-x-3">
                        <i class="fa-solid fa-circle-xmark text-lg"></i>
                        <span>Bu sipariş iptal edilmiştir. Gerekli iade veya bilgilendirme işlemleri yapılmıştır.</span>
                    </div>
                @endif

                <!-- Shipping Tracking Box -->
                @if($order->tracking_number)
                    <div class="bg-sky-500/10 border border-sky-500/30 p-4 rounded-xl text-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-sky-200">
                        <div class="space-y-1">
                            <span class="font-bold uppercase tracking-wider text-[10px] text-sky-400">Kargo Takip Bilgisi</span>
                            <p>Firma: <strong>{{ $order->shipping_company ?? 'Kargo Firması' }}</strong> | Takip No: <strong class="font-mono">{{ $order->tracking_number }}</strong></p>
                        </div>
                        @if($order->tracking_url)
                            <a href="{{ $order->tracking_url }}" target="_blank" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-dark font-bold rounded-lg transition-colors flex items-center space-x-1.5">
                                <span>Kargoyu Canlı Takip Et</span>
                                <i class="fa-solid fa-external-link text-[10px]"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Items & Financials Card -->
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl space-y-6">
                <h3 class="font-display font-bold text-base text-white">Siparişteki Ürünler ({{ $order->items->count() }})</h3>
                
                <div class="divide-y divide-white/5">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->product_image ?? asset('favicon.ico') }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-xl object-cover bg-white/5 border border-white/10">
                                <div>
                                    <h4 class="font-bold text-white text-sm">{{ $item->product_name }}</h4>
                                    <p class="text-xs text-white/50 mt-0.5">Ayakkabı Numarası: <strong class="text-accent">{{ $item->size_number }}</strong> • Adet: <strong>{{ $item->quantity }}</strong></p>
                                    <p class="text-xs text-white/50">Birim Fiyat: {{ number_format($item->price, 2, ',', '.') }} TL</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-bold text-accent">{{ number_format($item->total, 2, ',', '.') }} TL</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-6 border-t border-white/10 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Delivery & Contact Info -->
                    <div class="space-y-2 text-xs">
                        <h4 class="font-bold text-white uppercase tracking-wider text-[11px]">Teslimat & İletişim Bilgileri</h4>
                        <p class="text-white/80"><strong>Alıcı:</strong> {{ $order->customer_name }}</p>
                        <p class="text-white/80"><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
                        @if($order->customer_email)
                            <p class="text-white/80"><strong>E-posta:</strong> {{ $order->customer_email }}</p>
                        @endif
                        <p class="text-white/80"><strong>Adres:</strong> {{ $order->address }} - {{ $order->district }} / {{ $order->city }}</p>
                        @if($order->order_notes)
                            <p class="text-white/60 italic pt-1"><strong>Sipariş Notu:</strong> {{ $order->order_notes }}</p>
                        @endif
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-dark/50 p-4 rounded-xl border border-white/5 space-y-2 text-xs">
                        <div class="flex justify-between text-white/70">
                            <span>Ara Toplam</span>
                            <span>{{ number_format($order->subtotal, 2, ',', '.') }} TL</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-400">
                                <span>Kupon İndirimi ({{ $order->coupon_code }})</span>
                                <span>-{{ number_format($order->discount_amount, 2, ',', '.') }} TL</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-white/70">
                            <span>Kargo Tutarı</span>
                            <span>{{ $order->shipping_cost == 0 ? 'ÜCRETSİZ' : number_format($order->shipping_cost, 2, ',', '.') . ' TL' }}</span>
                        </div>
                        <div class="pt-2 border-t border-white/10 flex justify-between font-bold text-sm text-white">
                            <span>Toplam Tutar</span>
                            <span class="text-accent">{{ number_format($order->total_amount, 2, ',', '.') }} TL</span>
                        </div>
                        <div class="text-[11px] text-white/40 pt-1">
                            Ödeme Yöntemi: {{ $order->payment_method_label }} ({{ $order->payment_status_label }})
                        </div>
                    </div>
                </div>
            </div>

            <!-- Existing Return Requests for this Order -->
            @if($order->returnRequests->isNotEmpty())
                <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl space-y-4">
                    <h3 class="font-display font-bold text-base text-white flex items-center">
                        <i class="fa-solid fa-rotate-left text-accent mr-2"></i> İade & Değişim Talepleriniz
                    </h3>
                    <div class="space-y-3">
                        @foreach($order->returnRequests as $req)
                            <div class="p-4 bg-dark/60 rounded-xl border border-white/5 text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-white uppercase">{{ $req->type_label }}</span>
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold
                                        {{ $req->status === 'tamamlandi' || $req->status === 'onaylandi' ? 'bg-emerald-500/20 text-emerald-400' : '' }}
                                        {{ $req->status === 'bekliyor' || $req->status === 'inceleniyor' ? 'bg-amber-500/20 text-amber-400' : '' }}
                                        {{ $req->status === 'reddedildi' ? 'bg-rose-500/20 text-rose-400' : '' }}
                                    ">
                                        {{ $req->status_label }}
                                    </span>
                                </div>
                                <p class="text-white/70"><strong>Talep Nedeni:</strong> {{ $req->reason }}</p>
                                @if($req->requested_size)
                                    <p class="text-accent"><strong>İstenen Numara:</strong> {{ $req->requested_size }}</p>
                                @endif
                                @if($req->admin_note)
                                    <p class="text-sky-300 bg-sky-500/10 p-2 rounded border border-sky-500/20"><strong>Yönetici Notu:</strong> {{ $req->admin_note }}</p>
                                @endif
                                <span class="text-[10px] text-white/40 block">Oluşturulma: {{ $req->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Return & Exchange Modal -->
<div id="returnModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-[#161616] border border-white/10 w-full max-w-lg rounded-2xl p-6 sm:p-8 shadow-2xl relative text-white space-y-5">
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <h3 class="font-display font-bold text-lg text-white flex items-center">
                <i class="fa-solid fa-rotate-left text-accent mr-2.5"></i> İade & Değişim Talebi
            </h3>
            <button onclick="closeReturnModal()" class="text-white/60 hover:text-white text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('customer.returns.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div>
                <label for="order_item_id" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">İşlem Yapılacak Ürün</label>
                <select name="order_item_id" id="order_item_id" required class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                    @foreach($order->items as $it)
                        <option value="{{ $it->id }}">{{ $it->product_name }} ({{ $it->size_number }} Numara) - {{ number_format($it->total, 2, ',', '.') }} TL</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Talep Türü</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center space-x-2 bg-dark/60 p-3 rounded-xl border border-white/10 cursor-pointer">
                        <input type="radio" name="type" value="return" checked class="text-accent" onchange="toggleExchangeSize(false)">
                        <span class="text-xs font-semibold">Ürün İadesi</span>
                    </label>
                    <label class="flex items-center space-x-2 bg-dark/60 p-3 rounded-xl border border-white/10 cursor-pointer">
                        <input type="radio" name="type" value="exchange" class="text-accent" onchange="toggleExchangeSize(true)">
                        <span class="text-xs font-semibold">Numara Değişimi</span>
                    </label>
                </div>
            </div>

            <div id="exchangeSizeContainer" class="hidden">
                <label for="requested_size" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">İstenen Yeni Numara</label>
                <input type="text" name="requested_size" id="requested_size" placeholder="Örn: 42 veya 43" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
            </div>

            <div>
                <label for="reason" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Talep Açıklaması / Neden</label>
                <textarea name="reason" id="reason" rows="3" required placeholder="Lütfen iade veya değişim nedeninizi kısaca belirtin..." class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-accent hover:bg-accent-light text-dark font-display font-bold text-sm rounded-xl transition-colors shadow-lg">
                Talebi Gönder
            </button>
        </form>
    </div>
</div>

<script>
    function openReturnModal() {
        const modal = document.getElementById('returnModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeReturnModal() {
        const modal = document.getElementById('returnModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function toggleExchangeSize(isExchange) {
        const container = document.getElementById('exchangeSizeContainer');
        if (isExchange) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection
