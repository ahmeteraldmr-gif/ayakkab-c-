@extends('layouts.app')

@section('title', 'Siparişi Tamamla | Yusuf Akboğa Ayakkabı')

@section('content')

    <!-- Breadcrumb & Header -->
    <div class="bg-dark text-white py-6 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-xs text-white/50 mb-2">
                <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Ana Sayfa</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('cart.index') }}" class="hover:text-accent transition-colors">Sepet</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-accent font-semibold">Ödeme & Teslimat</span>
            </nav>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white">
                Teslimat ve Sipariş Bilgileri
            </h1>
        </div>
    </div>

    <!-- Main Checkout Form Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- LEFT: FORM FIELDS (lg:col-span-8) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- 1. Customer Personal Information -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-5">
                        <h3 class="font-display font-extrabold text-base text-dark border-b border-black/5 pb-3 flex items-center">
                            <span class="w-7 h-7 rounded-full bg-dark text-accent text-xs font-bold flex items-center justify-center mr-2.5">1</span>
                            İletişim Bilgileri
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">Ad Soyad <span class="text-rose-500">*</span></label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Örn: Ahmet Yılmaz" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">Telefon Numarası <span class="text-rose-500">*</span></label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="05XXXXXXXXX" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">E-posta Adresi (İsteğe Bağlı)</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="ahmet@example.com (Sipariş takibi için)" 
                                   class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                        </div>
                    </div>

                    <!-- 2. Delivery Address -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-5">
                        <h3 class="font-display font-extrabold text-base text-dark border-b border-black/5 pb-3 flex items-center">
                            <span class="w-7 h-7 rounded-full bg-dark text-accent text-xs font-bold flex items-center justify-center mr-2.5">2</span>
                            Teslimat Adresi
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">İl <span class="text-rose-500">*</span></label>
                                <input type="text" name="city" value="{{ old('city', 'İstanbul') }}" required placeholder="Örn: İstanbul" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">İlçe <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" value="{{ old('district') }}" required placeholder="Örn: Kadıköy" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">Açık Adres (Mahalle, Cadde, Sokak, No, Daire) <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="3" required placeholder="Kargonun teslim edileceği detaylı adres..." 
                                      class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl p-4 text-xs text-dark focus:outline-none focus:border-accent">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">Sipariş Notu (Opsiyonel)</label>
                            <input type="text" name="order_notes" value="{{ old('order_notes') }}" placeholder="Kargo kuryesi için özel notunuz..." 
                                   class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                        </div>
                    </div>

                    <!-- 3. Payment Method -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-4">
                        <h3 class="font-display font-extrabold text-base text-dark border-b border-black/5 pb-3 flex items-center">
                            <span class="w-7 h-7 rounded-full bg-dark text-accent text-xs font-bold flex items-center justify-center mr-2.5">3</span>
                            Ödeme Yöntemi
                        </h3>

                        <div class="space-y-3">
                            <!-- Option 1: Kapıda Ödeme -->
                            <label class="flex items-start p-4 rounded-2xl border-2 border-accent bg-[#FBF7F0] cursor-pointer">
                                <input type="radio" name="payment_method" value="kapida_odeme" checked class="mt-1 text-dark focus:ring-accent w-4 h-4">
                                <div class="ml-3">
                                    <span class="block text-xs font-extrabold text-dark">Kapıda Ödeme (Nakit veya Kredi Kartı)</span>
                                    <span class="block text-[11px] text-black/60 mt-0.5">Kargonuz kapınıza geldiğinde ister nakit ister pos cihazıyla kart ile güvenle ödeyin.</span>
                                </div>
                            </label>

                            <!-- Option 2: Havale / EFT -->
                            <label class="flex items-start p-4 rounded-2xl border border-black/10 bg-[#F7F7F5] hover:border-black/30 cursor-pointer transition-colors">
                                <input type="radio" name="payment_method" value="havale" class="mt-1 text-dark focus:ring-accent w-4 h-4">
                                <div class="ml-3">
                                    <span class="block text-xs font-extrabold text-dark">Banka Havalesi / EFT</span>
                                    <span class="block text-[11px] text-black/60 mt-0.5">Sipariş oluşturulduktan sonra banka hesap numaralarımıza transfer yapabilirsiniz.</span>
                                </div>
                            </label>

                            <!-- Option 3: Online Kredi Kartı -->
                            <label class="flex items-start p-4 rounded-2xl border border-black/10 bg-[#F7F7F5] hover:border-black/30 cursor-pointer transition-colors">
                                <input type="radio" name="payment_method" value="online_kart" class="mt-1 text-dark focus:ring-accent w-4 h-4">
                                <div class="ml-3 flex items-center justify-between w-full">
                                    <div>
                                        <span class="block text-xs font-extrabold text-dark">Online Kredi Kartı ile Ödeme (Ön Provizyon)</span>
                                        <span class="block text-[11px] text-black/60 mt-0.5">3D Secure ile güvenli ödeme altyapısı.</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5 text-black/40 text-lg">
                                        <i class="fa-brands fa-cc-visa"></i>
                                        <i class="fa-brands fa-cc-mastercard"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: ORDER SUMMARY & SUBMIT (lg:col-span-4) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-6 sticky top-28">
                        <h3 class="font-display font-extrabold text-lg text-dark border-b border-black/5 pb-4">
                            Siparişiniz ({{ $cartSummary['count'] }} Ürün)
                        </h3>

                        <!-- Mini Cart List -->
                        <div class="space-y-3 max-h-60 overflow-y-auto divide-y divide-black/5 pr-1">
                            @foreach($cartSummary['items'] as $item)
                                <div class="flex items-center justify-between pt-3 first:pt-0">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="w-12 h-12 rounded-xl bg-[#F7F7F5] border border-black/5 p-1 object-contain flex-shrink-0">
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-bold text-dark truncate">{{ $item['product_name'] }}</h4>
                                            <p class="text-[10px] text-black/50">Numara: {{ $item['size_number'] }} • Adet: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-extrabold text-dark flex-shrink-0">{{ number_format($item['total'], 2, ',', '.') }} ₺</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-2.5 pt-4 border-t border-black/5 text-xs text-black/70">
                            <div class="flex justify-between">
                                <span>Ara Toplam:</span>
                                <strong class="text-dark">{{ $cartSummary['subtotal_formatted'] }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Kargo:</span>
                                <strong class="{{ $cartSummary['shipping'] == 0 ? 'text-emerald-600' : 'text-dark' }}">
                                    {{ $cartSummary['shipping_formatted'] }}
                                </strong>
                            </div>
                            <div class="flex justify-between text-base font-extrabold text-dark pt-2 border-t border-black/5">
                                <span>Toplam Tutar:</span>
                                <span class="text-xl text-dark">{{ $cartSummary['total_formatted'] }}</span>
                            </div>
                        </div>

                        <!-- Submit Order Button -->
                        <button type="submit" id="checkoutSubmitBtn" class="w-full h-14 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-sm uppercase tracking-wider rounded-2xl transition-all shadow-xl shadow-black/10 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Siparişi Onayla</span>
                        </button>

                        <p class="text-[10px] text-center text-black/40">
                            "Siparişi Onayla" butonuna tıklayarak <a href="#" class="underline">Mesafeli Satış Sözleşmesi</a>'ni kabul etmiş olursunuz.
                        </p>
                    </div>
                </div>

            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    document.querySelector('form[action="{{ route('checkout.process') }}"]')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('checkoutSubmitBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i><span>Siparişiniz Oluşturuluyor...</span>';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });
</script>
@endpush
