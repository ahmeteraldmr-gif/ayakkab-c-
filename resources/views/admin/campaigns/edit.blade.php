@extends('layouts.admin')

@section('title', 'Kampanyayı Düzenle | VELORA Yönetim Paneli')
@section('page_title')
    Kampanyayı Düzenle: {{ $campaign->title }}
@endsection

@section('content')

    <div class="max-w-2xl bg-white border border-[#E5E7EB] rounded-xl p-6 sm:p-8 shadow-sm space-y-6">
        <form method="POST" action="{{ route('admin.campaigns.update', $campaign->id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Başlık <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $campaign->title) }}" required 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Alt Açıklama (Subtitle)</label>
                <textarea name="subtitle" rows="2" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">{{ old('subtitle', $campaign->subtitle) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Rozet / Etiket</label>
                    <input type="text" name="badge" value="{{ old('badge', $campaign->badge) }}" 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">İndirim Metni</label>
                    <input type="text" name="discount_text" value="{{ old('discount_text', $campaign->discount_text) }}" 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Buton Yazısı</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $campaign->button_text) }}" 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Buton Linki (URL)</label>
                    <input type="text" name="button_url" value="{{ old('button_url', $campaign->button_url) }}" 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 font-mono transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Banner Görseli</label>
                @if($campaign->image_url)
                    <div class="mb-3">
                        <img src="{{ $campaign->image_url }}" class="w-full h-28 rounded-lg object-cover border border-[#E5E7EB]">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg p-2.5 text-sm text-slate-700 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Sıralama</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $campaign->sort_order) }}" 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-2.5 cursor-pointer text-sm font-medium text-slate-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $campaign->is_active) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span>Yayında (Aktif)</span>
                    </label>
                </div>
            </div>

            <div class="pt-5 flex items-center justify-between border-t border-[#E5E7EB]">
                <a href="{{ route('admin.campaigns.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">İptal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Değişiklikleri Kaydet</span>
                </button>
            </div>
        </form>
    </div>

@endsection
