@extends('layouts.admin')

@section('title', 'Yorum Yönetimi | Yönetim Paneli')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#111827]">Ürün Yorumları</h1>
            <p class="text-xs text-[#6B7280] mt-1">Müşterilerden gelen ürün değerlendirmelerini inceleyin ve onaylayın.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-2 border-b border-gray-200 pb-2">
        <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ !request('status') ? 'bg-[#2563EB] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Tümü ({{ $counts['total'] }})
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Onay Bekleyenler ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Onaylananlar ({{ $counts['approved'] }})
        </a>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($reviews->isEmpty())
            <div class="text-center py-16 text-gray-500 text-sm">
                <i class="fa-regular fa-star text-3xl text-gray-300 mb-2 block"></i>
                Kayıtlı ürün yorumu bulunamadı.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                            <th class="py-3.5 px-4">Ürün</th>
                            <th class="py-3.5 px-4">Müşteri</th>
                            <th class="py-3.5 px-4">Puan</th>
                            <th class="py-3.5 px-4">Yorum</th>
                            <th class="py-3.5 px-4">Durum</th>
                            <th class="py-3.5 px-4">Tarih</th>
                            <th class="py-3.5 px-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $review->product->primary_image_url ?? asset('favicon.ico') }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 border border-gray-200">
                                        <div class="font-bold text-gray-900 max-w-xs truncate">{{ $review->product->name ?? 'Silinmiş Ürün' }}</div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-gray-900">{{ $review->customer_name }}</div>
                                    @if($review->is_verified_purchase)
                                        <span class="inline-flex items-center text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded font-semibold mt-0.5">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Doğrulanmış Alışveriş
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star text-xs"></i>
                                        @endfor
                                        <span class="ml-1 text-gray-700 font-bold">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 max-w-sm">
                                    @if($review->title)
                                        <p class="font-bold text-gray-900 truncate">{{ $review->title }}</p>
                                    @endif
                                    <p class="text-gray-600 line-clamp-2 leading-relaxed">{{ $review->comment }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($review->is_approved)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Onaylandı</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Bekliyor</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-gray-500 text-[11px]">
                                    {{ $review->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-1">
                                    @if(!$review->is_approved)
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors" title="Onayla">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors" title="Onayı Kaldır">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" class="inline-block" onsubmit="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition-colors" title="Sil">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
