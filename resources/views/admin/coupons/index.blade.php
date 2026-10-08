@extends('layouts.admin')

@section('title', 'Kuponlar | VELORA Yönetim')
@section('page_title', 'İndirim Kuponları')

@section('header_actions')
    <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
        <i class="fa-solid fa-plus"></i>
        <span>Yeni Kupon Ekle</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-gray-800">Tanımlı Kupon Listesi</h3>
            <span class="text-xs text-gray-500">Toplam {{ $coupons->total() }} kupon</span>
        </div>

        @if($coupons->isEmpty())
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 text-lg">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800">Henüz Kupon Bulunmuyor</h4>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Müşterilerinize özel indirimler ve kampanyalar tanımlamak için yeni bir kupon oluşturun.</p>
                <a href="{{ route('admin.coupons.create') }}" class="mt-4 inline-flex items-center space-x-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Kupon Oluştur</span>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider">
                            <th class="p-4">Kupon Kodu</th>
                            <th class="p-4">İndirim Türü / Değeri</th>
                            <th class="p-4">Min. Sepet / Max. İndirim</th>
                            <th class="p-4">Kullanım / Limit</th>
                            <th class="p-4">Geçerlilik Tarihi</th>
                            <th class="p-4">Durum</th>
                            <th class="p-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        @foreach($coupons as $coupon)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                                            <i class="fa-solid fa-ticket"></i>
                                        </div>
                                        <span class="font-mono font-bold text-gray-900 text-[13px] tracking-wide">{{ $coupon->code }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($coupon->type === 'percentage')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 text-xs font-bold">
                                            %{{ (int)$coupon->value }} İndirim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                            {{ number_format((float)$coupon->value, 2, ',', '.') }} ₺ Sabit
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-600">
                                    <div>Min: {{ $coupon->minimum_order_amount ? number_format((float)$coupon->minimum_order_amount, 2, ',', '.') . ' ₺' : 'Yok' }}</div>
                                    <div class="text-[11px] text-gray-400">Max: {{ $coupon->maximum_discount_amount ? number_format((float)$coupon->maximum_discount_amount, 2, ',', '.') . ' ₺' : 'Limitsiz' }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-gray-900">{{ $coupon->used_count }}</span>
                                    <span class="text-gray-400">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                                </td>
                                <td class="p-4 text-gray-600">
                                    @if($coupon->expires_at)
                                        <span class="{{ $coupon->expires_at->isPast() ? 'text-rose-600 font-bold' : '' }}">
                                            {{ $coupon->expires_at->format('d.m.Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Süresiz</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($coupon->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[11px] font-bold">
                                            Pasif
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="p-1.5 text-gray-500 hover:text-blue-600 transition-colors" title="Düzenle">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" class="inline-block" data-confirm-message="'{{ $coupon->code }}' kuponunu silmek istediğinize emin misiniz?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 transition-colors" title="Sil">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
