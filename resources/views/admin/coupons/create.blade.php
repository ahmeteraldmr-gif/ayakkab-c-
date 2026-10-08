@extends('layouts.admin')

@section('title', 'Yeni Kupon Oluştur | VELORA')
@section('page_title', 'Yeni İndirim Kuponu Ekle')

@section('header_actions')
    <a href="{{ route('admin.coupons.index') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">
        <i class="fa-solid fa-arrow-left mr-1"></i> Kuponlara Dön
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 sm:p-8 shadow-xs">
        
        <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <div>
                    <label for="code" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Kupon Kodu <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           id="code" 
                           value="{{ old('code') }}" 
                           required 
                           placeholder="Örn: VELORA10 veya 250TL" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 font-mono font-bold uppercase focus:border-blue-600 focus:outline-none transition-colors">
                    @error('code')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        İndirim Türü <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" id="type" required class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Yüzdelik (%) İndirim</option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Sabit Tutar (TL) İndirimi</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="value" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        İndirim Değeri <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="value" 
                           id="value" 
                           value="{{ old('value') }}" 
                           required 
                           placeholder="Örn: 10 (% için) veya 250 (TL için)" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                    @error('value')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="minimum_order_amount" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Minimum Sepet Tutarı (TL)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="minimum_order_amount" 
                           id="minimum_order_amount" 
                           value="{{ old('minimum_order_amount') }}" 
                           placeholder="Örn: 1000 (Boş ise limitsiz)" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                </div>

                <div>
                    <label for="maximum_discount_amount" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Maksimum İndirim Tavanı (TL)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="maximum_discount_amount" 
                           id="maximum_discount_amount" 
                           value="{{ old('maximum_discount_amount') }}" 
                           placeholder="Yüzdelik indirimler için tavan tutar" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                </div>

                <div>
                    <label for="usage_limit" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Toplam Kullanım Limiti
                    </label>
                    <input type="number" 
                           name="usage_limit" 
                           id="usage_limit" 
                           value="{{ old('usage_limit') }}" 
                           placeholder="Örn: 100 (Boş ise limitsiz)" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                </div>

                <div>
                    <label for="starts_at" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Başlangıç Tarihi
                    </label>
                    <input type="datetime-local" 
                           name="starts_at" 
                           id="starts_at" 
                           value="{{ old('starts_at') }}" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                </div>

                <div>
                    <label for="expires_at" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Bitiş Tarihi
                    </label>
                    <input type="datetime-local" 
                           name="expires_at" 
                           id="expires_at" 
                           value="{{ old('expires_at') }}" 
                           class="w-full bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none transition-colors">
                </div>

            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-bold text-gray-800">Kuponu aktif olarak kaydet</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">
                    Vazgeç
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                    Kuponu Kaydet
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
