@extends('layouts.app')

@section('title', 'Alışveriş Sepetim | Yusuf Akboğa Ayakkabı')

@section('content')

    <!-- Breadcrumb & Header -->
    <div class="bg-dark text-white py-6 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-xs text-white/50 mb-2">
                <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Ana Sayfa</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-accent font-semibold">Alışveriş Sepeti</span>
            </nav>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white">
                Alışveriş Sepetiniz
            </h1>
        </div>
    </div>

    <!-- Main Cart Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        @if(count($cartSummary['items']) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- CART ITEMS LIST (lg:col-span-8) -->
                <div class="lg:col-span-8 space-y-4">
                    
                    <!-- Free Shipping Threshold Meter -->
                    <div class="bg-white rounded-2xl p-4 border border-black/5 shadow-sm space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-dark">
                            <span class="flex items-center text-accent">
                                <i class="fa-solid fa-truck-fast mr-2"></i> Ücretsiz Kargo Durumu
                            </span>
                            <span>
                                @if($cartSummary['free_shipping_remaining'] <= 0)
                                    <strong class="text-emerald-600">Tebrikler, Kargo Ücretsiz! 🎉</strong>
                                @else
                                    Kargonuzun ücretsiz olması için <strong>{{ number_format($cartSummary['free_shipping_remaining'], 2, ',', '.') }} TL</strong> daha ürün ekleyin
                                @endif
                            </span>
                        </div>
                        <div class="w-full bg-[#F0F0EE] h-2 rounded-full overflow-hidden">
                            <div class="bg-accent h-full rounded-full transition-all duration-500" style="width: {{ $cartSummary['free_shipping_percent'] }}%"></div>
                        </div>
                    </div>

                    <!-- Items Container -->
                    <div class="bg-white rounded-3xl border border-black/5 shadow-sm divide-y divide-black/5 overflow-hidden">
                        @foreach($cartSummary['items'] as $item)
                            <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4 cart-row" data-key="{{ $item['key'] }}">
                                
                                <!-- Product Image & Title -->
                                <div class="flex items-center space-x-4 w-full sm:w-auto">
                                    <a href="{{ route('product.detail', $item['product_slug']) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[#F7F7F5] border border-black/5 p-2 flex-shrink-0 flex items-center justify-center">
                                        <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                    </a>
                                    <div class="space-y-1 min-w-0 flex-grow">
                                        <span class="text-[11px] font-bold text-accent uppercase tracking-wider">{{ $item['brand_name'] }}</span>
                                        <a href="{{ route('product.detail', $item['product_slug']) }}" class="block">
                                            <h3 class="font-display font-bold text-sm text-dark hover:text-accent transition-colors truncate max-w-xs">
                                                {{ $item['product_name'] }}
                                            </h3>
                                        </a>
                                        <div class="flex items-center space-x-3 text-xs text-black/60 font-semibold">
                                            <span class="bg-[#F0F0EE] px-2 py-0.5 rounded-md text-dark font-bold">Numara: {{ $item['size_number'] }}</span>
                                            <span>Birim: {{ number_format($item['price'], 2, ',', '.') }} ₺</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Adjuster & Subtotal & Delete -->
                                <div class="flex items-center justify-between sm:justify-end space-x-6 w-full sm:w-auto border-t sm:border-t-0 pt-3 sm:pt-0 border-black/5">
                                    
                                    <!-- Qty Control -->
                                    <div class="flex items-center border border-black/10 rounded-xl bg-[#F7F7F5] p-1">
                                        <button type="button" 
                                                onclick="updateCartItem('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})" 
                                                class="w-8 h-8 rounded-lg hover:bg-white text-dark font-bold text-xs flex items-center justify-center transition-colors"
                                                aria-label="Adet Azalt">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <span class="w-8 text-center text-xs font-bold text-dark">{{ $item['quantity'] }}</span>
                                        <button type="button" 
                                                onclick="updateCartItem('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})" 
                                                class="w-8 h-8 rounded-lg hover:bg-white text-dark font-bold text-xs flex items-center justify-center transition-colors"
                                                aria-label="Adet Artır">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[80px] sm:min-w-[90px]">
                                        <span class="font-display font-extrabold text-sm sm:text-base text-dark block">
                                            {{ number_format($item['total'], 2, ',', '.') }} ₺
                                        </span>
                                    </div>

                                    <!-- Remove Button -->
                                    <button type="button" 
                                            onclick="removeCartItem('{{ $item['key'] }}')" 
                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors" 
                                            title="Ürünü Sepetten Kaldır">
                                        <i class="fa-solid fa-trash-can text-xs sm:text-sm"></i>
                                    </button>

                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Actions (Clear Cart / Continue Shopping) -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('products.index') }}" class="text-xs font-bold text-dark hover:text-accent flex items-center">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Alışverişe Devam Et
                        </a>

                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Sepetinizi tamamen temizlemek istediğinize emin misiniz?');">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700">
                                <i class="fa-solid fa-trash-can mr-1"></i> Sepeti Boşalt
                            </button>
                        </form>
                    </div>

                </div>

                <!-- ORDER SUMMARY SIDEBAR (lg:col-span-4) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-6 sticky top-28">
                        <h3 class="font-display font-extrabold text-lg text-dark border-b border-black/5 pb-4">
                            Sipariş Özeti
                        </h3>

                        <div class="space-y-3 text-xs">
                            <div class="flex items-center justify-between text-black/70">
                                <span>Ürünlerin Toplamı:</span>
                                <strong class="text-dark font-bold text-sm">{{ $cartSummary['subtotal_formatted'] }}</strong>
                            </div>

                            <div class="flex items-center justify-between text-black/70">
                                <span>Kargo Ücreti:</span>
                                <strong class="{{ $cartSummary['shipping'] == 0 ? 'text-emerald-600 font-bold' : 'text-dark' }} text-sm">
                                    {{ $cartSummary['shipping_formatted'] }}
                                </strong>
                            </div>

                            <div class="border-t border-black/5 pt-3 flex items-center justify-between text-sm">
                                <span class="font-display font-extrabold text-dark">Ödenecek Tutar:</span>
                                <span class="font-display font-extrabold text-2xl text-dark">
                                    {{ $cartSummary['total_formatted'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Checkout CTA -->
                        <a href="{{ route('checkout.index') }}" class="w-full h-14 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-sm uppercase tracking-wider rounded-2xl transition-all shadow-xl shadow-black/10 flex items-center justify-center space-x-2">
                            <span>Siparişi Tamamla</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <!-- Security Badges -->
                        <div class="pt-4 border-t border-black/5 space-y-2 text-[11px] text-black/50">
                            <p class="flex items-center">
                                <i class="fa-solid fa-lock text-accent mr-2"></i> 256-Bit SSL Güvenli Sipariş
                            </p>
                            <p class="flex items-center">
                                <i class="fa-solid fa-hand-holding-dollar text-accent mr-2"></i> Kapıda Nakit veya Kredi Kartı ile Ödeme
                            </p>
                            <p class="flex items-center">
                                <i class="fa-solid fa-shield-halved text-accent mr-2"></i> 14 Gün Kolay Değişim & İade
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- Empty Cart State -->
            <div class="bg-white rounded-3xl p-12 text-center border border-black/5 shadow-sm max-w-xl mx-auto my-12 space-y-6">
                <div class="w-24 h-24 rounded-full bg-[#F7F7F5] flex items-center justify-center mx-auto text-4xl text-accent/60">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div class="space-y-2">
                    <h2 class="font-display font-extrabold text-2xl text-dark">Sepetiniz Boş</h2>
                    <p class="text-black/50 text-sm">Henüz sepetinize bir ürün eklemediniz. En trend modelleri hemen inceleyebilirsiniz.</p>
                </div>
                <div>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-8 py-4 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-lg">
                        <span>Alışverişe Başla</span>
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        @endif

    </div>

@endsection

@push('scripts')
<script>
    function updateCartItem(key, quantity) {
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ key: key, quantity: quantity })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showToast(data.message || 'Hata oluştu', 'error');
            }
        })
        .catch(() => showToast('İşlem sırasında hata oluştu', 'error'));
    }

    function removeCartItem(key) {
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ key: key })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
