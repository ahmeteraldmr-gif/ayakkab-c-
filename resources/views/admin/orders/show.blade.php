@extends('layouts.admin')

@section('title', 'Sipariş Detayı #' . $order->order_number . ' | Yusuf Akboğa Yönetim Paneli')
@section('page_title', 'Sipariş Detayı: ' . $order->order_number)

@section('content')

    <div class="space-y-6">
        
        <!-- Back Link & Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blue-600 flex items-center transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Tüm Siparişlere Dön
            </a>

            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                if (strlen($cleanPhone) == 10 && str_starts_with($cleanPhone, '5')) {
                    $cleanPhone = '90' . $cleanPhone;
                } elseif (strlen($cleanPhone) == 11 && str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '9' . $cleanPhone;
                }
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Merhaba ' . $order->customer_name . ', Yusuf Akboğa Ayakkabı\'dan ' . $order->order_number . ' numaralı siparişiniz hakkında yazıyoruz.') }}" 
               target="_blank" 
               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-2 shadow-xs">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Müşteriye WhatsApp'tan Yaz</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">
            
            <!-- LEFT: ORDER ITEMS & DETAILS (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Order Items Card -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center justify-between">
                        <span>Sipariş Edilen Ayakkabılar ({{ $order->items->count() }} Kalem)</span>
                        <span class="text-blue-600 font-mono text-xs font-bold">{{ $order->order_number }}</span>
                    </h3>

                    <div class="divide-y divide-[#F3F4F6]">
                        @foreach($order->items as $item)
                            <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-xl bg-[#F8FAFC] object-contain p-1 border border-[#E5E7EB] flex-shrink-0">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-[#111827] text-sm">{{ $item->product_name }}</h4>
                                        <p class="text-xs text-gray-500">
                                            Numara: <strong class="text-blue-600">{{ $item->size_number }}</strong> • Adet: <strong class="text-gray-900">{{ $item->quantity }}</strong>
                                        </p>
                                        <span class="text-[11px] text-gray-400">Birim: {{ $item->formatted_price }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-sans font-extrabold text-[#111827] text-base block">
                                        {{ $item->formatted_total }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Calculations -->
                    <div class="pt-4 border-t border-[#E5E7EB] space-y-2 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span>Ara Toplam:</span>
                            <strong class="text-[#111827]">{{ number_format($order->subtotal, 2, ',', '.') }} ₺</strong>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                <span>Kupon İndirimi {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}:</span>
                                <strong class="font-bold">-{{ number_format($order->discount_amount, 2, ',', '.') }} ₺</strong>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Kargo Ücreti:</span>
                            <strong class="text-[#111827]">{{ $order->shipping_cost == 0 ? 'Ücretsiz' : number_format($order->shipping_cost, 2, ',', '.') . ' ₺' }}</strong>
                        </div>
                        <div class="flex justify-between text-base font-extrabold text-[#111827] pt-2 border-t border-[#E5E7EB]">
                            <span>Genel Toplam:</span>
                            <span class="text-blue-600 text-xl font-extrabold">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer & Delivery Info -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3">
                        Teslimat ve Alıcı Bilgileri
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                        <div class="space-y-2">
                            <span class="text-gray-400 block font-medium">Alıcı Adı:</span>
                            <strong class="text-[#111827] text-sm block">{{ $order->customer_name }}</strong>
                            
                            <span class="text-gray-400 block pt-2 font-medium">Telefon:</span>
                            <a href="tel:{{ $order->customer_phone }}" class="text-blue-600 font-semibold block hover:underline">{{ $order->customer_phone }}</a>

                            @if($order->customer_email)
                                <span class="text-gray-400 block pt-2 font-medium">E-posta:</span>
                                <span class="text-[#111827] block">{{ $order->customer_email }}</span>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <span class="text-gray-400 block font-medium">Teslimat Adresi:</span>
                            <p class="text-[#111827] leading-relaxed">{{ $order->address }}</p>
                            <p class="text-blue-600 font-bold">{{ $order->district }} / {{ $order->city }}</p>

                            @if($order->order_notes)
                                <div class="mt-3 p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                                    <span class="text-gray-500 block font-bold text-[10px] uppercase tracking-wider">Müşteri Notu:</span>
                                    <p class="text-gray-700 italic text-xs mt-1">{{ $order->order_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT: ORDER STATUS & UPDATE FORM (lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Status & Shipping Updater -->
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-5 sticky top-24">
                    <h3 class="font-sans font-bold text-sm text-[#111827] border-b border-[#E5E7EB] pb-3 flex items-center justify-between">
                        <span>Sipariş & Kargo Yönetimi</span>
                        <span class="px-2.5 py-1 rounded-lg font-bold text-[11px] {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </h3>

                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sipariş Durumu</label>
                            <select name="status" class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                <option value="yeni" {{ $order->status === 'yeni' ? 'selected' : '' }}>Yeni Sipariş</option>
                                <option value="hazirlaniyor" {{ $order->status === 'hazirlaniyor' ? 'selected' : '' }}>Hazırlanıyor</option>
                                <option value="kargoda" {{ $order->status === 'kargoda' ? 'selected' : '' }}>Kargoya Verildi</option>
                                <option value="tamamlandi" {{ $order->status === 'tamamlandi' ? 'selected' : '' }}>Tamamlandı / Teslim Edildi</option>
                                <option value="iptal" {{ $order->status === 'iptal' ? 'selected' : '' }}>İptal Edildi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ödeme Durumu</label>
                            <input type="text" name="payment_status" value="{{ old('payment_status', $order->payment_status) }}" 
                                   class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div class="pt-3 border-t border-[#E5E7EB] space-y-3">
                            <h4 class="font-bold text-xs text-gray-900 flex items-center">
                                <i class="fa-solid fa-truck text-blue-600 mr-2"></i> Kargo & Gönderi Bilgileri
                            </h4>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Kargo Firması</label>
                                <select name="shipping_company" class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                                    <option value="">Firma Seçiniz</option>
                                    @foreach(['Yurtiçi Kargo', 'Aras Kargo', 'MNG Kargo', 'PTT Kargo', 'Sürat Kargo', 'Hepsijet', 'Trendyol Express'] as $cargo)
                                        <option value="{{ $cargo }}" {{ old('shipping_company', $order->shipping_company) === $cargo ? 'selected' : '' }}>{{ $cargo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Kargo Takip Numarası</label>
                                <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Örn: 123456789012" 
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] font-mono focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Özel Takip Linki (Opsiyonel)</label>
                                <input type="url" name="tracking_url" value="{{ old('tracking_url', $order->tracking_url) }}" placeholder="https://..." 
                                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            </div>

                            @if($order->effective_tracking_url)
                                <div class="pt-2">
                                    <a href="{{ $order->effective_tracking_url }}" target="_blank" class="w-full py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg flex items-center justify-center transition-colors border border-blue-200">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-[11px]"></i> Kargo Takip Sayfasını Aç
                                    </a>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-xs">
                            Bilgileri Kaydet & Güncelle
                        </button>
                    </form>

                    <div class="pt-4 border-t border-[#E5E7EB] text-xs text-gray-400 space-y-1">
                        <p>Oluşturulma: {{ $order->created_at->format('d.m.Y H:i') }}</p>
                        @if($order->shipped_at)
                            <p class="text-blue-600 font-medium">Kargoya Verilme: {{ $order->shipped_at->format('d.m.Y H:i') }}</p>
                        @endif
                        @if($order->delivered_at)
                            <p class="text-emerald-600 font-medium">Teslim Edilme: {{ $order->delivered_at->format('d.m.Y H:i') }}</p>
                        @endif
                        <p>Son Güncelleme: {{ $order->updated_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
