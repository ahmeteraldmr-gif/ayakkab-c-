@extends('layouts.app')

@section('title', 'Siparişi Tamamla | VELORA')

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
        
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1.5 text-rose-600"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- LEFT: FORM FIELDS (lg:col-span-8) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Saved Addresses Quick Selection for Authenticated Users -->
                    @auth
                        @if(isset($savedAddresses) && $savedAddresses->isNotEmpty())
                            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-4">
                                <h3 class="font-display font-extrabold text-base text-dark border-b border-black/5 pb-3 flex items-center justify-between">
                                    <span class="flex items-center">
                                        <i class="fa-solid fa-location-dot text-accent mr-2"></i> Kayıtlı Adreslerimden Seç
                                    </span>
                                    <span class="text-xs text-black/50 font-normal">Hızlıca doldurmak için tıklayın</span>
                                </h3>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($savedAddresses as $sAddr)
                                        <div onclick="populateAddress({{ json_encode($sAddr) }})" 
                                             class="saved-addr-card p-4 rounded-2xl border border-black/10 bg-[#F7F7F5] hover:border-accent hover:bg-[#FBF7F0] cursor-pointer transition-all space-y-1 text-xs">
                                            <div class="flex items-center justify-between">
                                                <strong class="text-dark font-bold">{{ $sAddr->title }}</strong>
                                                @if($sAddr->is_default)
                                                    <span class="text-[9px] bg-accent/20 text-accent font-bold px-1.5 py-0.5 rounded">Varsayılan</span>
                                                @endif
                                            </div>
                                            <p class="text-black/70">{{ $sAddr->full_name }} • {{ $sAddr->phone }}</p>
                                            <p class="text-black/60 truncate">{{ $sAddr->address }}</p>
                                            <p class="text-dark font-semibold">{{ $sAddr->district }} / {{ $sAddr->city }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endauth

                    <!-- 1. Customer Personal Information -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-5">
                        <h3 class="font-display font-extrabold text-base text-dark border-b border-black/5 pb-3 flex items-center">
                            <span class="w-7 h-7 rounded-full bg-dark text-accent text-xs font-bold flex items-center justify-center mr-2.5">1</span>
                            İletişim Bilgileri
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">Ad Soyad <span class="text-rose-500">*</span></label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required placeholder="Örn: Ahmet Yılmaz" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">Telefon Numarası <span class="text-rose-500">*</span></label>
                                <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required placeholder="05XXXXXXXXX" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">E-posta Adresi (İsteğe Bağlı)</label>
                            <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" placeholder="ahmet@example.com (Sipariş takibi için)" 
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
                                <input type="text" name="city" id="city" value="{{ old('city', 'İstanbul') }}" required placeholder="Örn: İstanbul" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-dark mb-1.5">İlçe <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" id="district" value="{{ old('district') }}" required placeholder="Örn: Kadıköy" 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-3 text-xs text-dark focus:outline-none focus:border-accent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-dark mb-1.5">Açık Adres (Mahalle, Cadde, Sokak, No, Daire) <span class="text-rose-500">*</span></label>
                            <textarea name="address" id="address" rows="3" required placeholder="Kargonun teslim edileceği detaylı adres..." 
                                      class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl p-4 text-xs text-dark focus:outline-none focus:border-accent">{{ old('address') }}</textarea>
                        </div>

                        @auth
                            <div class="pt-2 flex flex-col sm:flex-row sm:items-center gap-3">
                                <label class="flex items-center space-x-2 text-xs text-dark cursor-pointer">
                                    <input type="checkbox" name="save_address" value="1" class="rounded text-dark focus:ring-accent w-4 h-4">
                                    <span>Bu adresi adres defterime kaydet</span>
                                </label>
                                <input type="text" name="address_title" placeholder="Adres Başlığı (Örn: Evim, Ofis)" class="px-3 py-1.5 bg-[#F7F7F5] border border-black/10 rounded-xl text-xs text-dark focus:outline-none focus:border-accent">
                            </div>
                        @endauth

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
                                        <span class="block text-xs font-extrabold text-dark">Online Kredi Kartı ile Ödeme</span>
                                        <span class="block text-[11px] text-black/60 mt-0.5">3D Secure ile güvenli ödeme altyapısı (PayTR / iyzico).</span>
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

                            @if(!empty($cartSummary['discount_amount']) && $cartSummary['discount_amount'] > 0)
                                <div class="flex items-center justify-between text-emerald-700 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-100">
                                    <span class="flex items-center font-medium">
                                        <i class="fa-solid fa-tag mr-1.5 text-emerald-600"></i> Kupon İndirimi ({{ $cartSummary['coupon_code'] }}):
                                    </span>
                                    <strong class="font-bold">-{{ $cartSummary['discount_formatted'] }}</strong>
                                </div>
                            @endif

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

                        <!-- Coupon Box -->
                        @if(empty($cartSummary['has_coupon']))
                            <div class="pt-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="checkoutCouponCode" placeholder="İndirim Kodu" class="flex-grow px-3 py-2 bg-[#F7F7F5] border border-black/10 rounded-xl text-xs uppercase tracking-wider font-semibold focus:outline-none focus:border-accent">
                                    <button type="button" onclick="applyCheckoutCoupon()" class="px-3.5 py-2 bg-dark hover:bg-accent text-white hover:text-dark text-xs font-bold rounded-xl transition-colors whitespace-nowrap">
                                        Uygula
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="pt-2 flex items-center justify-between text-xs text-emerald-700 bg-emerald-50 p-2.5 rounded-xl border border-emerald-200">
                                <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check mr-1.5"></i> Kupon: {{ $cartSummary['coupon_code'] }}</span>
                                <form method="POST" action="{{ route('coupon.remove') }}">
                                    @csrf
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold hover:underline">Kaldır</button>
                                </form>
                            </div>
                        @endif

                        <!-- Mandatory Legal Agreements -->
                        <div class="space-y-3 pt-4 border-t border-black/5 text-[11px] text-black/70">
                            <label class="flex items-start space-x-2 cursor-pointer">
                                <input type="checkbox" name="pre_info_approval" value="1" required class="mt-0.5 rounded text-dark focus:ring-accent w-4 h-4">
                                <span>
                                    <a href="{{ route('legal.pre-info') }}" target="_blank" class="text-dark font-bold underline hover:text-accent">Ön Bilgilendirme Formu</a>'nu okudum ve onaylıyorum. <span class="text-rose-500">*</span>
                                </span>
                            </label>

                            <label class="flex items-start space-x-2 cursor-pointer">
                                <input type="checkbox" name="distance_selling_approval" value="1" required class="mt-0.5 rounded text-dark focus:ring-accent w-4 h-4">
                                <span>
                                    <a href="{{ route('legal.distance-selling') }}" target="_blank" class="text-dark font-bold underline hover:text-accent">Mesafeli Satış Sözleşmesi</a>'ni okudum ve kabul ediyorum. <span class="text-rose-500">*</span>
                                </span>
                            </label>
                        </div>

                        <!-- Submit Order Button -->
                        <button type="submit" id="checkoutSubmitBtn" class="w-full h-14 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-sm uppercase tracking-wider rounded-2xl transition-all shadow-xl shadow-black/10 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Siparişi Onayla</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    function populateAddress(addr) {
        if (addr.full_name) document.getElementById('customer_name').value = addr.full_name;
        if (addr.phone) document.getElementById('customer_phone').value = addr.phone;
        if (addr.city) document.getElementById('city').value = addr.city;
        if (addr.district) document.getElementById('district').value = addr.district;
        if (addr.address) document.getElementById('address').value = addr.address;
        
        document.querySelectorAll('.saved-addr-card').forEach(el => el.classList.remove('ring-2', 'ring-accent'));
        event.currentTarget.classList.add('ring-2', 'ring-accent');
        
        showToast('Adres bilgileri aktarıldı.', 'success');
    }

    function applyCheckoutCoupon() {
        const codeInput = document.getElementById('checkoutCouponCode');
        const code = codeInput ? codeInput.value.trim() : '';
        if (!code) {
            showToast('Lütfen kupon kodunu girin.', 'error');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("coupon.apply") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = csrfToken;
        form.appendChild(csrf);

        const codeF = document.createElement('input');
        codeF.type = 'hidden';
        codeF.name = 'code';
        codeF.value = code;
        form.appendChild(codeF);

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
